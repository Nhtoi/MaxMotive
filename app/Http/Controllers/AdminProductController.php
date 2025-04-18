<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $products = DB::select("
                SELECT * FROM products
                WHERE name ILIKE ? OR category ILIKE ?
            ", ["%$search%", "%$search%"]);
        } else {
            $products = DB::select("SELECT * FROM products");
        }

        return view('admin-products', compact('products'));
    }

    public function create()
    {
        // Pass empty array for new product
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
            'weight' => 'nullable|numeric',  // Add weight validation
            'flavor' => 'nullable|string',   // Add flavor validation
        ]);

        DB::insert("
            INSERT INTO products (name, price, description, imageurl, category, stock_quantity, weight, flavor, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), now())
        ", [
            $validated['name'],
            $validated['price'],
            $validated['description'] ?? null,
            $validated['imageurl'] ?? null,
            $validated['category'] ?? null,
            $validated['stock_quantity'],
            $validated['weight'] ?? null,   // Insert weight if provided
            $validated['flavor'] ?? null,   // Insert flavor if provided
        ]);

        return redirect()->route('admin.products')->with('success', 'Product added.');
    }

    public function edit($id)
    {
        $product = DB::selectOne("SELECT * FROM products WHERE id = ?", [$id]);

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
            'weight' => 'nullable|numeric',  // Add weight validation
            'flavor' => 'nullable|string',   // Add flavor validation
        ]);

        DB::update("
            UPDATE products
            SET name = ?, price = ?, description = ?, imageurl = ?, category = ?, stock_quantity = ?, weight = ?, flavor = ?, updated_at = now()
            WHERE id = ?
        ", [
            $validated['name'],
            $validated['price'],
            $validated['description'] ?? null,
            $validated['imageurl'] ?? null,
            $validated['category'] ?? null,
            $validated['stock_quantity'],
            $validated['weight'] ?? null,   // Update weight if provided
            $validated['flavor'] ?? null,   // Update flavor if provided
            $id,
        ]);

        return redirect()->route('admin.products')->with('success', 'Product updated.');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM products WHERE id = ?", [$id]);

        return redirect()->route('admin.products')->with('success', 'Product deleted.');
    }

    public function archive($id)
    {
        DB::update("UPDATE products SET category = 'archived', updated_at = now() WHERE id = ?", [$id]);

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
    
        // Try decoding JSON
        $products = json_decode($content, true);
    
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($products)) {
            return back()->with('error', 'Invalid JSON file format.');
        }
    
        $inserted = 0;
        foreach ($products as $product) {
            // Basic validation per product
            if (
                isset($product['id'], $product['name'], $product['price'], $product['description'], 
                      $product['imageurl'], $product['category'], $product['stock_quantity'])
            ) {
                DB::insert("
                    INSERT INTO products (id, name, price, description, imageurl, category, stock_quantity, weight, flavor, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                    ON CONFLICT (id) 
                    DO UPDATE SET
                        name = EXCLUDED.name,
                        price = EXCLUDED.price,
                        description = EXCLUDED.description,
                        imageurl = EXCLUDED.imageurl,
                        category = EXCLUDED.category,
                        stock_quantity = EXCLUDED.stock_quantity,
                        weight = EXCLUDED.weight,
                        flavor = EXCLUDED.flavor,
                        updated_at = NOW();
                ", [
                    $product['id'],
                    $product['name'],
                    $product['price'],
                    $product['description'] ?? null, 
                    $product['imageurl'] ?? null, // Allow imageurl to be null
                    $product['category'] ?? null, 
                    $product['stock_quantity'],
                    $product['weight'] ?? null, 
                    $product['flavor'] ?? null, 
                ]);
    
                $inserted++;
            }
        }
    
        return back()->with('success', "$inserted product(s) uploaded successfully.");
    }
}
