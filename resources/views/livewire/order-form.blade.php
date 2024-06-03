<div>
    <div style="display: flex;">
        <!-- Prikaz stavki -->
        <div>
            @foreach($items as $item)
            <div wire:click="addToOrder({{ $item->id }})" style="cursor: pointer; padding: 10px;">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 50px; height: 50px;">
                <p>{{ $item->name }}</p>
            </div>
            @endforeach
        </div>

        <!-- Sidebar za narudžbinu -->
        <div style="margin-left: 20px; width: 300px; padding: 20px; background-color: #f4f4f4;">
            <h3>Narudžbina</h3>
            <ul>
                @foreach($selectedItems as $item)
                @if($item['quantity'] > 0)
                <li>{{ $item['name'] }}: {{ $item['quantity'] }} x {{ $item['price'] }} RSD</li>
                @endif
                @endforeach
            </ul>
            <button wire:click="addOrder" style="padding: 10px; background-color: green; color: white;">Predaj porudžbinu</button>
            @if(session()->has('message'))
            <div style="color: green;">{{ session('message') }}</div>
            @endif
        </div>
    </div>
</div>
