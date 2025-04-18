<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Shopping Cart - MaxMotive</title>
</head>
<body>
    <nav>
        <div id="logo">
            <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo" />
            <span id="logo-text">MaxMotive</span>
        </div>
        <div id="frame">
            <div id="nav-menu">
                <a href="{{ route('products.index') }}">Shop</a>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('orders.view') }}">View Orders</a>
            </div>
        </div>
    </nav>

    <main>
        <h1>Your Shopping Cart</h1>
        <div class="shopping-cart">
            @if(count($cartItems) > 0)
                @foreach($cartItems as $item)
                    <div class="cart-card">
                        <div class="cart-img-box">
                            <img src="{{ $item->imageurl }}" alt="{{ $item->name }}" />
                        </div>

                        <div class="cart-content">
                            <div class="cart-description">
                                <p class="cart-title">{{ $item->name }}</p>
                                <p class="cart-price">Price: ${{ number_format($item->price, 2) }}</p>
                            </div>

                            <div class="cart-actions">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="cart-form">
                                    @csrf
                                    @method('PUT')
                                    <label class="cart-label">Qty:</label>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->stock_quantity }}" class="cart-qty" />
                                    <div class="cart-buttons">
                                        <button type="submit" class="cart-button">Update</button>
                                    </div>
                                </form>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="cart-form">
                                    @csrf
                                    @method('DELETE')
                                    <div class="cart-buttons">
                                        <button type="submit" class="cart-button">Remove</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Your cart is empty.</p>
            @endif
        </div>

        <div class="cart-summary">
            <p id="cart-total">Total: ${{ number_format($totalAmount, 2) }}</p>
        </div>

        <form action="{{ route('cart.checkout') }}" method="POST">
            @csrf
            <div class="shipping-info">
                <h3>Shipping Information</h3>
                <input type="text" name="fullname" placeholder="Full Name" required />
                <input type="text" name="address" placeholder="Address" required />
                <input type="text" name="city" placeholder="City" required />
            </div>
            <button type="submit" id="checkout-btn">Checkout</button>
        </form>
    </main>

    <footer>
        <div id="content">
            <div id="info">
                <div id="logo">
                    <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo" />
                    <span id="logo-text">MaxMotive</span>
                </div>
                <p id="footer-info">
                    Max Motive &copy; {{ date('Y') }}.<br> All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
