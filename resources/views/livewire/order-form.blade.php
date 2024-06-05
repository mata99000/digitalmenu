<div class="flex">
    <!-- Prikaz stavki -->
    <div class="flex-grow p-4 space-y-4">
        @foreach($items as $item)
        <div wire:click="addToOrder({{ $item->id }})" class="flex items-center space-x-2 cursor-pointer">
            <img src="{{ Storage::disk('public')->url($item->image) }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-full">
            <img src="" alt="Image">
            <p class="text-sm font-semibold">{{ $item->name }}</p>
        </div>
        @endforeach
    </div>

    <!-- Sidebar za narudžbinu -->
    <div class="w-96 p-6 bg-gray-100">
        <h3 class="text-lg font-bold">Narudžbina</h3>
        <ul class="mt-4 space-y-2">
            @foreach($selectedItems as $item)
            @if($item['quantity'] > 0)
            <li>
                {{ $item['name'] }}: {{ $item['quantity'] }} x {{ $item['price'] }} RSD
                <button wire:click="removeItem({{ $item['id'] }})" class="text-red-500">Ukloni</button>
            </li>
            @endif
        @endforeach
        
        </ul>
        <button wire:click="addOrder" class="mt-4 w-full py-2 px-4 bg-green-500 text-white font-bold rounded hover:bg-green-600">Predaj porudžbinu</button>
        @if(session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
        <div class="text-xl font-bold">Ukupno: {{ $this->calculateTotal() }} RSD</div>

    </div>
</div>
