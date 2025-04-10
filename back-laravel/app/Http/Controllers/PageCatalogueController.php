<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PageCatalogueController extends Controller
{
    public function default() {
        $products = Product::with('mainImage')
                           ->withAvg('reviews', 'rating')
                           ->withCount('reviews')
                           ->paginate(9);

        return view('products_catalogue', compact('products'));
    }
}
