<div class="cardSection" id="cardSection">
    @if ($subcategories && $subcategories->isNotEmpty())
        @foreach ($subcategories as $subcategory)
            @if ($subcategory->items && $subcategory->items->isNotEmpty())
                @foreach ($subcategory->items as $item)
                    <div class="card" data-item-id="{{ $item->id }}">
                        <div>
                            <img src="https://134947091.cdn6.editmysite.com/uploads/1/3/4/9/134947091/s331987289957190480_p140_i1_w10000.png?width=2560" alt="{{ $item->name }}">
                        </div>
                        <p>{{ $item->name }}</p>
                        <div class="star">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < $item->rating)
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="price">
                            <p>{{ $item->price }} <b>RSD</b></p>
                            <i class="fa-solid fa-plus add-to-cart" data-item-id="{{ $item->id }}"></i>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    @else
        <p>No items found.</p>
    @endif
</div>
