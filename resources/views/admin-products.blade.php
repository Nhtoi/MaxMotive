<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/admin.css')
    <title>Admin - Product Listing</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.upload') }}">Bulk Upload</a>
        <a href="{{ route('admin.products') }}">Product Listing</a>
        <a href="{{ route('admin.orders.all') }}">All Orders</a>
        <a href="{{ route('home') }}">Home</a>
    </nav>

    <h1>Product Listing</h1>

    <div id="search-filter">
        <form method="GET" action="{{ route('admin.products') }}">
            <input type="text" name="search" id="search" placeholder="Search by product name or category..." value="{{ request('search') }}">
            <button type="submit">Search</button>
        </form>
        <button type="button" onclick="window.location.href='{{ route('admin.products.create') }}'">Add New Product</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->category }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}">Edit</a> |
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form> |
                    <a href="{{ route('admin.products.archive', $product->id) }}">Archive</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
