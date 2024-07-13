<div class="flex space-x-4 overflow-x-auto category">
    @foreach ($categories as $category)
        <div class="items" wire:click="$dispatch('categorySelected', { categoryId: {{ $category->id }} })">
            <img src="https://icons.iconarchive.com/icons/aha-soft/desktop-buffet/64/Pizza-icon.png" width="64" height="64">
            <p>{{ $category->name }}</p>
            <div id="divider"></div>
            <i class="fa-solid fa-angle-right"></i>
        </div>
    @endforeach
</div>
