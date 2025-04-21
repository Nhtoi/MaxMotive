<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - MaxMotive</title>
    
    @vite('resources/css/app.css')
</head>
<body>
    <nav>
        <div id="logo">
            <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo">
            <span id="logo-text">MaxMotive</span>
        </div>
        <div id="frame">
            <div id="nav-menu">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('products.index') }}">Shop</a>
            </div>
        </div>
    </nav>

    <main>
        @if (!$product)
        <div class="no-products">
            No Products Listed Yet.
        </div>
        @else
            <div class="product-details">
                <div class="product-image">
                    @if($product->imageurl)
                        <img class="image-details" src="{{ $product->imageurl }}" alt="{{ $product->name }}">
                    @endif
                </div>
                <div class="product-info">
                    <h2>{{ $product->name }}</h2>
                    <p>Price: ${{ number_format($product->price, 2) }}</p>
                    <p>Description: {{ $product->description }}</p>
                    <p>Net Weight: {{ $product->weight ?? 'N/A' }}</p> 
                    <p>Flavor: {{ $product->flavor ?? 'N/A' }}</p> 
    
                    <form method="POST" action="{{ route('cart.add', $product->id) }}">
                        @csrf
                        <button class="add-cart" id="add-to-cart-btn" type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
        @endif
    </main>

    <footer>
        <div id="content">
            <div id="info">
                <div id="logo">
                    <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo">
                    <span id="logo-text">MaxMotive</span>
                </div>
                <p id="footer-info">
                    MaxMotive &copy; {{ date('Y') }}. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    @vite('resources/js/script.js')
</body>
</html>
