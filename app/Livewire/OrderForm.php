<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\ItemOption;
use App\Models\OptionPrice;
use App\Events\OrderCreated;
use App\Models\OrderItemOption;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


class OrderForm extends Component
{
    public $items;
    public $selectedItems = [];
    public $showPropertiesModal = false;
    public $modalItem = null;

    protected $listeners = ['addItem' => 'addToOrder'];

    public function mount()
    {
        $this->items = Item::with('options')->get();
        $this->initSelectedItems();
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->selectedItems as $item) {
            $total += $item['quantity'] * $item['adjustedPrice'];
        }
        return $total;
    }

    public function recalculateAdjustedPrice($itemId)
    {
        $item = $this->selectedItems[$itemId];
        $adjustedPrice = $item['price'];

        if (isset($item['selectedOptions']) && is_array($item['selectedOptions'])) {
            foreach ($item['selectedOptions'] as $optionId) {
                if (isset($item['options'][$optionId])) {
                    $adjustedPrice += $item['options'][$optionId]['price'];
                }
            }
        }

        $this->selectedItems[$itemId]['adjustedPrice'] = $adjustedPrice;
    }

    public function updatedSelectedItems($value, $name)
    {
        if (preg_match('/\.selectedOptions$/', $name)) {
            $itemId = explode('.', $name)[1];
            $this->recalculateAdjustedPrice($itemId);
        }
    }

    public function updateItemQuantity($itemId, $newQuantity)
    {
        foreach ($this->selectedItems as $index => $item) {
            if ($item['id'] == $itemId) {
                if ($newQuantity <= 0) {
                    unset($this->selectedItems[$index]);
                } else {
                    $this->selectedItems[$index]['quantity'] = $newQuantity;
                }
            }
        }
        $this->recalculateAdjustedPrice($itemId);  // Recalculate price if quantity changes
    }

    private function initSelectedItems()
    {
        foreach ($this->items as $item) {
            $options = [];
            foreach ($item->options as $option) {
                $optionPrice = OptionPrice::where('option_id', $option->id)->value('amount');
                $options[$option->id] = [
                    'name' => $option->name,
                    'type' => $option->type,
                    'price' => $optionPrice,
                ];
            }

            $this->selectedItems[$item->id] = [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => 0,
                'price' => $item->price,
                'adjustedPrice' => $item->price,
                'options' => $options,
                'selectedOptions' => []
            ];
        }
    }

    public function updateSelectedOptions($itemId, $optionId, $isSelected)
    {
        if (!array_key_exists($itemId, $this->selectedItems)) {
            return;
        }

        if (!array_key_exists('selectedOptions', $this->selectedItems[$itemId])) {
            $this->selectedItems[$itemId]['selectedOptions'] = [];
        }

        $option = $this->selectedItems[$itemId]['options'][$optionId];

        if ($isSelected) {
            $this->selectedItems[$itemId]['selectedOptions'][$optionId] = $optionId;
        } else {
            unset($this->selectedItems[$itemId]['selectedOptions'][$optionId]);
        }

        $this->recalculateAdjustedPrice($itemId);  // Recalculate price if options change
    }

    public function addToOrder($itemId)
    {
        $item = Item::with('options')->find($itemId);
        $options = [];
        foreach ($item->options as $option) {
            $optionPrice = OptionPrice::where('option_id', $option->id)->value('amount');
            $options[$option->id] = [
                'name' => $option->name,
                'type' => $option->type,
                'price' => $optionPrice,
            ];
        }

        if (!isset($this->selectedItems[$itemId])) {
            $this->selectedItems[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => 1,
                'price' => $item->price,
                'adjustedPrice' => $item->price,
                'options' => $options,
                'selectedOptions' => []
            ];
        } else {
            $this->selectedItems[$itemId]['quantity']++;
        }
        $this->recalculateAdjustedPrice($itemId);  // Recalculate price when item is added
    }

    public function removeItem($itemId)
    {
        if (isset($this->selectedItems[$itemId])) {
            unset($this->selectedItems[$itemId]);
        }
    }

    public function openPropertiesModal($itemId)
    {
        $this->modalItem = $this->selectedItems[$itemId];
        $this->showPropertiesModal = true;
    }

    public function closePropertiesModal()
    {
        $this->modalItem = null;
        $this->showPropertiesModal = false;
    }

    public function saveProperties()
    {
        $itemId = $this->modalItem['id'];
        $this->selectedItems[$itemId]['selectedOptions'] = $this->modalItem['selectedOptions'];
        $this->closePropertiesModal();
        $this->recalculateAdjustedPrice($itemId);  // Recalculate price when properties are saved
    }

    public function addOrder()
    {
        if (array_sum(array_column($this->selectedItems, 'quantity')) === 0) {
            session()->flash('error', 'Narudžbina ne može biti prazna!');
            return;
        }

        $order = new Order();
        $order->waiter_id = auth()->id();
        $order->total = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['adjustedPrice'];
        }, $this->selectedItems));
        $order->status = 'pending';
        $order->save();

        foreach ($this->selectedItems as $item) {
            if ($item['quantity'] > 0) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->item_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['adjustedPrice'];
                $orderItem->save();

                if (array_key_exists('selectedOptions', $item) && !empty($item['selectedOptions'])) {
                    foreach ($item['selectedOptions'] as $optionId) {
                        $orderItemOption = new OrderItemOption();
                        $orderItemOption->order_item_id = $orderItem->id;
                        $orderItemOption->option_id = $optionId;
                        $orderItemOption->selected = true;
                        $orderItemOption->save();
                    }
                }
            }
        }

        $order = Order::with('orderItems.item')->find($order->id);

        $containsFood = $order->orderItems->contains(function ($orderItem) {
            return $orderItem->item->type == 'food';
        });
        $containsDrink = $order->orderItems->contains(function ($orderItem) {
            return $orderItem->item->type == 'drink';
        });

        if ($containsFood) {
            broadcast(new OrderCreated($order))->toOthers();
        }
        if ($containsDrink) {
            broadcast(new OrderCreated($order))->toOthers();
        }
        $this->reset('selectedItems');
        $this->initSelectedItems();
        //$this->printReceipt($order);
        session()->flash('message', 'Order successfully submitted!');
    }
    private function printReceipt($order)
    {
        try {
            // Connect to the printer
            $connector = new WindowsPrintConnector("smb://DESKTOP-9EJSBKM/BIXOLON");
            $printer = new Printer($connector);

            // Print Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("Company Name\n");
            $printer->setTextSize(1, 1);
            $printer->text("Address Line 1\n");
            $printer->text("Address Line 2\n");
            $printer->text("Phone: (000) 000-0000\n");
            $printer->text(str_repeat("-", 42) . "\n"); // Adjusted line width for 80mm
            $printer->setTextSize(2, 2);
            $printer->text("Order Receipt\n");
            $printer->setTextSize(1, 1);
            $printer->text(str_repeat("-", 42) . "\n"); // Adjusted line width for 80mm

            // Print Items Header
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($this->formatLine("Item", "Qty", "Unit", 42));
            $printer->text(str_repeat("-", 42) . "\n"); // Adjusted line width for 80mm

            // Print Items
            foreach ($order->orderItems as $item) {
                $wrappedItemName = $this->wrapText($item->item->name, 28); // Adjusted for wider width
                $unitPrice = number_format($item->item->price, 2);
                $quantity = $item->quantity;
                $totalPrice = number_format($quantity * $item->item->price, 2);

                // Print item name
                $printer->text($wrappedItemName[0] . "\n");
                if (isset($wrappedItemName[1])) {
                    $printer->text($wrappedItemName[1] . "\n");
                } else {
                    $printer->text("\n"); // Add an empty line if item name is only one line
                }

                // Print quantity and unit price
                $printer->text($this->formatLine("", $quantity, $unitPrice, 42));

                // Print total price for the item
                $printer->text($this->formatTotalLine("= $totalPrice", 42));

                // Print options if any
                if (!empty($item->selectedOptions)) {
                    foreach ($item->selectedOptions as $optionId) {
                        $option = ItemOption::find($optionId);
                        $optionName = $this->fitTextToWidth("+ {$option->name}", 38);
                        $printer->text("   {$optionName}\n");
                    }
                }

                // Print separator line between items
                $printer->text(str_repeat("-", 42) . "\n"); // Adjusted line width for 80mm
            }

            // Print Total
            $printer->text(str_repeat("-", 42) . "\n"); // Adjusted line width for 80mm
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Total: " . number_format($order->total, 2) . "\n");
            $printer->text(str_repeat("-", 42) . "\n"); // Adjusted line width for 80mm

            // Print Footer
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for your purchase!\n");
            $printer->text("Visit us again!\n");

            // Cut the paper
            $printer->cut();

            // Close the connection
            $printer->close();
        } catch (\Exception $e) {
            // Handle errors
            session()->flash('error', 'Error printing receipt: ' . $e->getMessage());
        }
    }

    private function formatLine($leftText, $centerText1, $centerText2, $lineWidth = 42)
    {
        $leftWidth = 20;
        $centerWidth = 10;
        $rightWidth = $lineWidth - $leftWidth - $centerWidth;

        $leftText = str_pad($leftText, $leftWidth);
        $centerText1 = str_pad($centerText1, $centerWidth, ' ', STR_PAD_BOTH);
        $centerText2 = str_pad($centerText2, $rightWidth, ' ', STR_PAD_LEFT);

        return $leftText . $centerText1 . $centerText2 . "\n";
    }

    private function formatTotalLine($rightText, $lineWidth = 42)
    {
        $rightWidth = $lineWidth;
        $rightText = str_pad($rightText, $rightWidth, ' ', STR_PAD_LEFT);

        return $rightText . "\n";
    }

    private function wrapText($text, $maxLength)
    {
        $wrappedText = wordwrap($text, $maxLength, "\n", true);
        return explode("\n", $wrappedText);
    }

    private function fitTextToWidth($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength - 1) . '…';
        }
        return str_pad($text, $maxLength);
    }
    public function render()
    {
        return view('livewire.order-form')->layout('layouts.order');
    }
}
