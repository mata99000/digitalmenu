<div class="cartFixed" id="cartFixed">
    <div class="head">
        <h1>My Cart</h1>
        <i class="fa-solid fa-xmark" id="cartToggleClose"></i>
    </div>
    <div id="divider"></div>
    <div class="cartSection" id="cartSection">
        @foreach($cart as $item)
            <div class="cartItem">
                <p>{{ $item['name'] }}</p>
                <p>${{ number_format($item['price'], 2) }}</p>
            </div>
        @endforeach
    </div>
    <p id="totalpriceHandler">Total Prize: ${{ number_format($totalPrice, 2) }}</p>
    <a href="./ActiveOrder.html">
        <button>Check out</button>
    </a>
</div>
