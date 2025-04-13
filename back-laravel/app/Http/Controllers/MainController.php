<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Collection;

class MainController extends Controller
{

    public function index() {
        $products = Product::whereHas('mainImage')
                           ->with('mainImage', 'activeDiscount')
                           ->withAvg('reviews', 'rating')
                           ->withCount('reviews')
                           ->latest()
                           ->take(10)
                           ->get();

        $latestCollection = Collection::orderBY('release_date', 'desc')->first();
        $last_collection_products = Product::where('collection_id', $latestCollection->id)
                           ->with('mainImage', 'activeDiscount')
                           ->withAvg('reviews', 'rating')
                           ->withCount('reviews')
                           ->latest()
                           ->take(4)
                           ->get();

        $collection_id = $latestCollection->id;

        return view('index', compact(
            'products',
            'last_collection_products',
            'collection_id',
        ));
    }
}
