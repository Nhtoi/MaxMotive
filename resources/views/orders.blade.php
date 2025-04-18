<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Past Orders - MaxMotive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/orders.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet"> 
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div id="logo">
            <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo">
            <span id="logo-text">MaxMotive</span>
        </div>
        <div id="frame">
            <div id="nav-menu">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('cart.index') }}">View Cart</a>
            </div>
        </div>
    </nav>

    <main>
        <h1>Past Orders</h1>
        <div class="orders-container">
            @if($orders->isEmpty())
                <p>No past orders found.</p>
            @else
                @foreach($orders as $order)
                    <div class="order">
                        <h3>Order ID: {{ $order->id }}</h3>
                        <p>Date: {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</p>
                        <p><strong>Shipping to:</strong> {{ $order->full_name }}, {{ $order->address }}, {{ $order->city }}</p>
                        <ul>
                            @foreach($order->items as $item)
                            <li>{{ $item->name }} x {{ $item->quantity }} - ${{ number_format($item->price * $item->quantity, 2) }}</li>
                        @endforeach
                        
                        </ul>
                        <hr>
                    </div>
                @endforeach
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div id="content">
            <div id="info">
                <div id="logo">
                    <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo">
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
