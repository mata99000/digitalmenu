<div class="flex flex-col lg:flex-row">
    <div class="flex-grow p-4 lg:pr-80"> <!-- Dodavanje margine desno za sidebar na većim ekranima -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"> <!-- Povećanje broja kolona i smanjenje veličine itema -->
            @foreach($items as $item)
            <div wire:click="addToOrder({{ $item->id }})" class="item-box cursor-pointer">
                <img src="{{ Storage::disk('public')->url($item->image) }}" alt="{{ $item->name }}" class="w-full h-24 object-cover"> <!-- Smanjenje visine slika -->
                <p class="item-name text-sm mt-1 text-center">{{ $item->name }}</p> <!-- Smanjenje teksta -->
            </div>
            @endforeach
        </div>
    </div>

    <!-- Sidebar za narudžbinu -->
    <div class="lg:w-80 lg:h-screen lg:fixed lg:top-0 lg:right-0 lg:overflow-y-auto w-full h-auto p-6 bg-gray-100 z-30"> <!-- Z-indeks povećan da osigura da sidebar ostaje iznad sadržaja -->
        <h3 class="text-lg font-bold mb-4">Narudžbina</h3>
        <ul class="space-y-2">
            @foreach($selectedItems as $item)
            @if($item['quantity'] > 0)
            <li class="bg-white p-2 rounded shadow">
                {{ $item['name'] }}:
                <input type="number" value={{$item['quantity']}} wire:model.lazy="item.quantity" wire:change="updateItemQuantity({{ $item['id'] }}, $event.target.value)"  min="0.5" step="0.5" class="w-20 text-center">
                x {{ $item['price'] }} RSD
                <button wire:click="removeItem({{ $item['id'] }})" class="text-red-500">Ukloni</button>
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
</div>
