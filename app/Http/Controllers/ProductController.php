<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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

        return view('products', compact('products'));
    }

    public function show($id)
    {
        $product = DB::selectOne("SELECT * FROM products WHERE id = ?", [$id]);

        if (!$product) {
            abort(404);
        }

        return view('product-show', compact('product'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search-bar');
    
        $products = DB::select(
            "SELECT * FROM products WHERE name ILIKE :searchTerm",
            ['searchTerm' => '%' . $searchTerm . '%']
        );
    
        return view('products', ['products' => $products]);
    }
}
