<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/admin.css')
    <title>Admin - Bulk Upload</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.upload') }}">Bulk Upload</a>
        <a href="{{ route('admin.products') }}">Product Listing</a>
        <a href="{{ route('admin.orders.all') }}">All Orders</a>
        <a href="{{ route('home') }}">Home</a>
    </nav>

    <h1>Bulk Product Upload</h1>
    
    <form action="{{ route('admin.upload.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file-upload">Select a file to upload (JSON, CSV):</label>
        <input type="file" id="file-upload" name="file_upload" accept=".json, .csv, .txt" required>
        <button type="submit">Upload</button>
    </form>

    <div id="instructions">
        <h3>Sample Format:</h3>
        <pre>
            [
                {
                  "id": 1,
                  "name": "Whey Protein Isolate",
                  "price": 39.99,
                  "description": "High-quality whey protein isolate for muscle recovery and growth.",
                  "imageurl": "https://m.media-amazon.com/images/I/71KtI9UDIgL._AC_UF1000,1000_QL80_.jpg",
                  "category": "Protein",
                  "stock_quantity": 50,
                  "weight": "1.5 kg",
                  "flavor": "Vanilla"
                },
            ]
                    </pre>
    </div>

</body>
</html>
