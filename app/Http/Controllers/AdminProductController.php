<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::all($search);

        return view('admin-products', compact('products'));
    }

    public function create()
    {
        return view('admin-products-create', ['product' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
            'imageurl' => 'nullable',
            'category' => 'nullable',
            'stock_quantity' => 'required|integer',
            'weight' => 'nullable|numeric',
            'flavor' => 'nullable|string',
        ]);

        Product::insert($validated);

        return redirect()->route('admin.products')->with('success', 'Product added.');
    }

    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        return view('admin-products-create', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
            'imageurl' => 'nullable',
            'category' => 'nullable',
            'stock_quantity' => 'required|integer',
            'weight' => 'nullable|numeric',
            'flavor' => 'nullable|string',
        ]);

        Product::updateById($id, $validated);

        return redirect()->route('admin.products')->with('success', 'Product updated.');
    }

    public function destroy($id)
    {
        Product::deleteById($id);

        return redirect()->route('admin.products')->with('success', 'Product deleted.');
    }

    public function archive($id)
    {
        Product::archiveById($id);

        return redirect()->route('admin.products')->with('success', 'Product archived.');
    }

    public function uploadForm()
    {
        return view('admin-upload');
    }

    public function uploadStore(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:json,txt,csv',
        ]);

        $file = $request->file('file_upload');
        $content = file_get_contents($file->getRealPath());

        $products = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($products)) {
            return back()->with('error', 'Invalid JSON file format.');
        }

        $inserted = Product::upsertMany($products);

        return back()->with('success', "$inserted product(s) uploaded successfully.");
    }
}
