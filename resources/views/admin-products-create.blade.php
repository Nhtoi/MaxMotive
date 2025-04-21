<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/admin.css')
    <title>Admin - {{ isset($product) ? 'Edit' : 'Create' }} Product</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.upload') }}">Bulk Upload</a>
        <a href="{{ route('admin.products') }}">Product Listing</a>
        <a href="{{ route('admin.orders.all') }}">All Orders</a>
        <a href="{{ route('home') }}">Home</a>
    </nav>

    <h1>{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>

    <form 
    action="{{ isset($product->id) 
        ? route('admin.products.update', $product->id) 
        : route('admin.products.store') 
    }}"
    method="POST"
>
    @csrf
    @if(isset($product->id))
        @method('PUT')
    @endif

        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" name="name" value="{{ old('name', $product->name ?? '') }}">

        <label for="product-description">Description:</label>
        <textarea id="product-description" name="description">{{ old('description', $product->description ?? '') }}</textarea>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="{{ old('category', $product->category ?? '') }}">

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}">

        <label for="image-path">Image Path:</label>
        <input type="text" id="image-path" name="imageurl" value="{{ old('imageurl', $product->imageurl ?? '') }}">

        <label for="stock_quantity">Stock Quantity:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}">

        <button type="submit">
            {{ isset($product) ? 'Update Product' : 'Create Product' }}
        </button>
    </form>
</body>
</html>
