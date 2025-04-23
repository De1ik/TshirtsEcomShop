<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, $id) {
        $selectedColor = $request->query('color');

        $product = Product::with(['variants.color', 'mainImage', 'images', 'activeDiscount', 'reviews'])
            ->findOrFail($id);

        // Fix: use 'category' instead of 'category_id'
        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();


        return view('product_details', compact('product', 'similarProducts', 'selectedColor'));
    }
}
