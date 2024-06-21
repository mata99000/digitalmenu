<div class="flex flex-col lg:flex-row">
    <!-- Sidebar za narudžbinu -->
    <div class="lg:w-80 lg:h-screen lg:fixed lg:top-0 lg:right-0 lg:overflow-y-auto w-full h-auto p-6 bg-gray-100 z-30">
        <!-- Z-indeks povećan da osigura da sidebar ostaje iznad sadržaja -->
        <h3 class="text-lg font-bold mb-4">Narudžbina</h3>
        <ul class="space-y-2">
            @foreach($selectedItems as $key => $item)
            @if($item['quantity'] > 0)
            <li class="bg-white p-2 rounded shadow">
                {{ $item['name'] }}:
                <input type="number" value="{{ $item['quantity'] }}" wire:model.lazy="selectedItems.{{ $key }}.quantity" wire:change="updateItemQuantity({{ $item['id'] }}, $event.target.value)" min="0.5" step="0.5" class="w-20 text-center">
                x {{ $item['price'] }} RSD
                <button wire:click="removeItem({{ $item['id'] }})" class="text-red-500">Ukloni</button>
                <button wire:click="openPropertiesModal({{ $item['id'] }})" class="text-blue-500">Properties</button>

                <!-- Messages for selected options -->
                @if(isset($item['selectedOptions']) && !empty($item['selectedOptions']))
                    <ul>
                        @foreach($item['selectedOptions'] as $optionId)
                            @if(isset($item['options'][$optionId]))
                                @php
                                    $option = $item['options'][$optionId];
                                @endphp
                                @if($option['type'] === 'add')
                                    <li class="text-green-500">{{ $option['name'] }} (+{{ $option['price'] }} RSD)</li>
                                @elseif($option['type'] === 'remove')
                                    <li class="text-red-500">{{ $option['name'] }} (-{{ $option['price'] }} RSD)</li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                @endif
            </li>
            @endif
            @endforeach
        </ul>
        <button wire:click="addOrder" class="mt-4 w-full py-2 px-4 bg-green-500 text-white font-bold rounded hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-500 focus:ring-opacity-50">Predaj porudžbinu</button>
        @if(session()->has('message'))
        <div class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('message') }}</div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div>
        @endif
        
        <div class="text-xl font-bold mt-4">Ukupno: {{ $this->calculateTotal() }} RSD</div>
    </div>
    
    <!-- Modal za properties -->
    @if($showPropertiesModal)
    <div class="fixed inset-0 flex items-center justify-center z-40">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-lg font-bold mb-4">Izaberite opcije za: {{ $modalItem['name'] }}</h2>
            @foreach($modalItem['options'] as $optionId => $option)
            <div class="flex items-center mb-2">
                <input type="checkbox" id="option-{{ $optionId }}" wire:model="modalItem.selectedOptions" value="{{ $optionId }}">
                <label for="option-{{ $optionId }}" class="ml-2">{{ $option['name'] }} @if(isset($option['price'])) (+{{ $option['price'] }} RSD) @endif</label>
            </div>
            @endforeach
            <button wire:click="saveProperties" class="mt-4 w-full py-2 px-4 bg-green-500 text-white font-bold rounded hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-500 focus:ring-opacity-50">Save</button>
            <button wire:click="closePropertiesModal" class="mt-2 w-full py-2 px-4 bg-red-500 text-white font-bold rounded hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-500 focus:ring-opacity-50">Cancel</button>
        </div>
    </div>
    @endif
</div>
