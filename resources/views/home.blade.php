<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxMotive</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet"> 
    @vite('resources/css/app.css')

</head>
<body>
    <nav>
        <div id="logo">
            <img id="logo-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="MaxMotive Logo" />
            <span id="logo-text">MaxMotive</span>
        </div>
        <div id="frame">
            <div id="nav-menu">
                <a href="{{ url('products') }}">Shop</a>
                <a href="{{ url('admin/products') }}">Admin Portal</a>
                <a href="{{ route('orders.view') }}">View Orders</a>
                <div id="btn">
                    <button id="signup-btn">Sign-up</button>
                    <button id="login-btn">Login</button>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <div id="text-block">
            <h2>Power Your Potential.</h2>
            <p>We provide top-tier supplements that boost performance and recovery, 
            helping everyone push their limits and achieve their fitness goals.</p>
            <a href="{{ url('products') }}">
                <button id="call-to-action">Shop-Now</button>
            </a>
        </div>
        <div id="image-container">
            <img id="hero-image" src="{{ Vite::asset('resources/Assets/Logo.png') }}" alt="Hero Image">
        </div>
    </header>
    
    <main>
        <div id="main-heading">
            <h1>Browse our Products</h1>
            <form method="GET" action="{{ route('products.search') }}" id="search-form">
                <input 
    type="text" 
    id="search-bar" 
    name="search-bar" 
    placeholder="Search for products..."
    style="background: url('{{ Vite::asset('resources/Assets/magnifying-glass-icon.png') }}') no-repeat 15px center; background-size: 20px;"
>
                <button type="submit" id="search-btn">Search</button>
            </form>
        </div>
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

    <!-- Scripts -->
    @vite('resources/js/script.js')
</body>
</html>
