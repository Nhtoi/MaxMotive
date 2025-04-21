<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <title>Products</title>
</head>
<body>
    <nav>
        <div id="logo">
            <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="Logo">
            <span id="logo-text">MaxMotive</span>
        </div>
        <div id="frame">
            <div id="nav-menu">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ url('products') }}">Shop</a>
                <a href="{{ route('orders.view') }}">View Orders</a>
                <div id="btn">
                    <button id="viewcart-btn" data-route="{{ route('cart.index') }}">View Cart</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="all-products">
        <div id="product-container"> 
            @if (count($products))
                @foreach ($products as $product)
                <div class="product-card">
                    <div class="item-box">
                        @if($product->imageurl)
                            <img src="{{ $product->imageurl }}" alt="{{ $product->name }}" class="product-img">
                        @endif
                    </div>

                    <div class="item-body">   
                        <div class="item-title">
                            <h2 class="item-name">{{ $product->name }}</h2>
                            <p class="item-desc">{{ $product->description }}</p>
                        </div>

                        <div class="item-details">
                            <h2 class="item-price">${{ number_format($product->price, 2) }}</h2>
                            <p class="item-availability 
                            {{ $product->stock_quantity == 0 ? 'out-of-stock' : ($product->stock_quantity < 10 ? 'low-stock' : 'in-stock') }}">
                            @if($product->stock_quantity == 0)
                                Out of Stock
                            @elseif($product->stock_quantity < 10)
                                Low Stock
                            @else
                                In-Stock
                            @endif
                        </p>
                        </div>

                        <div class="details-container">
                            <a class="view-details" href="{{ route('products.show', $product->id) }}">View Details</a>
                        </div>
                        
                        <div class="buttons-container">
                            <form method="POST" action="{{ route('cart.add', $product->id) }}">
                                @csrf
                                <button type="submit" class="add-cart" 
                                    id="add-to-cart-btn-{{ $product->id }}" 
                                    {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                                    {{ $product->stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </form>
                        </div>
                    </div> 
                </div>
                @endforeach
            @else
                <div class="no-products">
                    No products available at the moment.
                </div>
            @endif
        </div>
    </div>
    
    <footer>
        <div id="content">
            <div id="info">
                <div id="logo">
                    <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="Logo">
                    <span id="logo-text">MaxMotive</span>
                </div>
                <p id="footer-info">
                    Max Motive &copy; {{ date('Y') }}.<br> All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewCartBtn = document.getElementById('viewcart-btn');
            if (viewCartBtn) {
                const cartRoute = viewCartBtn.getAttribute('data-route');
                viewCartBtn.addEventListener('click', () => {
                    window.location.href = cartRoute;
                });
            }
        });
    </script>

    @vite('resources/js/cart.js')
</body>
</html>
