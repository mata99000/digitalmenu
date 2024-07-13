@extends('layouts.user-order')

@section('content')
<div>
    <div id="preloader" class="preloader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div class="navbarFixed">
        <a href="./index.html">
            <i class="fa-solid fa-house"></i>
            <p>Home</p>
        </a>
        <a href="./ActiveOrder.html">
            <i class="fa-solid fa-cart-shopping"></i>
            <p>Order</p>
        </a>
    </div>

    <div class="cartFixed" id="cartFixed">
        <div class="head">
            <h1>My Cart</h1>
            <i class="fa-solid fa-xmark" id="cartToggleClose"></i>
        </div>
        <div id="divider"></div>
        <div class="cartSection" id="cartSection">
            <!-- Cart items will be inserted here -->
        </div>
        <p id="totalpriceHandler">Total Price: $0.00</p>
        <a href="./ActiveOrder.html">
            <button>Check out</button>
        </a>
    </div>

    <nav>
        <div class="navInput">
            <h1>Digital <br> Restaurant</h1>
            <div class="input">
                <input type="text" id="searchInput" placeholder="Search...">
                <i class="fa-solid fa-magnifying-glass"></i>

            </div>
        </div>
        <div class="right">
            <i class="fa-solid fa-cart-plus" id="cartToggle"></i>
            <span id="cartBadge " class="badge">0</span>
        </div>
    </nav>

    <div class="mainContainer">
        <div class="container">
            <div class="menu">
                <div class="head">
                    <h1>Menu Category</h1>
                </div>
                @livewire('category-list')
                @livewire('items-by-category')
            </div>
        </div>
    </div>
</div>
@endsection
