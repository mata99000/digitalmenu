<div>
    <div class="bg-gray-800 text-white p-3">
        @foreach($categories as $category)
            <button wire:click="selectCategory({{ $category->id }})" class="bg-gray-700 hover:bg-gray-600 p-2 rounded">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
        @if(!empty($items))
        @foreach($items as $item)
        <div wire:click="addItemToOrder({{ $item->id }})" class="item-box cursor-pointer">
            <img src="https://134947091.cdn6.editmysite.com/uploads/1/3/4/9/134947091/s331987289957190480_p140_i1_w10000.png?width=2560" alt="{{ $item->name }}" class="w-50 h-25 object-cover">
            <p class="item-name text-sm mt-1 text-center">{{ $item->name }}</p>
        </div>
        
        @endforeach
        @else
            <p>Nema dostupnih proizvoda za odabranu kategoriju.</p>
        @endif
    </div>
</div>
