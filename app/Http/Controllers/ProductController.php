<?php

namespace App\Http\Controllers;

use App\Models\userProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = $search 
            ? userProduct::search($search)
            : userProduct::getAll();

        return view('products', compact('products'));
    }

    public function show($id)
    {
        $product = userProduct::find($id);

        if (!$product) {
            abort(404);
        }

        return view('product-show', compact('product'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search-bar');

        $products = userProduct::searchByName($searchTerm);

        return view('products', compact('products'));
    }
}
