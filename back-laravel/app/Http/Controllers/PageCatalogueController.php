<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Collection;

class PageCatalogueController extends Controller
{
    public function default(Request $request) {
        $query = Product::with('mainImage', 'activeDiscount')
                        ->withAvg('reviews', 'rating')
                        ->withCount('reviews');


        // Min/Max price for slider
        $minPrice = (clone $query)->min('final_price');
        $maxPrice = (clone $query)->max('final_price');


        // Apply filters if present
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('collection')) {
            $query->where('collection_id', $request->input('collection'));
        }

        if ($request->filled('min-price') && $request->filled('max-price')) {
            $min = floatval($request->input('min-price'));
            $max = floatval($request->input('max-price'));
            $query->whereBetween('final_price', [$min, $max]);
        }

        if ($request->filled('discount') && $request->input('discount') == 1) {
            $query->where('is_discount', true);
        }

        // Sorting
        $sort = $request->input('sort');
        if ($sort === 'price_asc') {
            $query->orderBy('final_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('final_price', 'desc');
        }


        // Paginate with query params
        $products = $query->paginate(9)->withQueryString();

        // Filter data
        $categories = Product::select('category')->distinct()->pluck('category');
        $collections = Collection::whereIn('id', Product::select('collection_id'))->pluck('name', 'id');
        $genders = Product::select('gender')->distinct()->pluck('gender');

        return view('products_catalogue', compact(
            'products',
            'minPrice',
            'maxPrice',
            'categories',
            'collections',
            'genders',
            'sort',
        ));
    }
}

