<div id="orders-container" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 p-4"  >
    @foreach ($orders as $order)
        <div id="order-{{ $order->id }}" class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg font-bold mb-2">Order #{{ $order->id }}</h3>
            <ul class="mb-4">
                @foreach ($order->orderItems as $item)
                    @if ($item->item->type === 'food')
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $item->item->name }}</span>
                            <span class="text-gray-600">x{{ $item->quantity }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if($order->status !== 'completed')
                <button wire:click="completeOrder({{ $order->id }})" class="complete-order bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Mark as Completed
                </button>
            @endif
        </div>
    @endforeach


    

    <script>document.addEventListener('DOMContentLoaded', function () {
           Echo.channel('orders')
    .listen('OrderCreated', (e) => {
        console.log('Complete event data:', e);
        console.log('Order ID being sent:', e.order.id);
        window.Livewire.dispatch('order-created', { orderId: e.order.id });
    });



        });

    </script>
    
    
</div>
