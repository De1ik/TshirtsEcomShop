<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Collection;

class AdminProductsCatalogueController extends Controller
{
    public function default(Request $request) {
//         dd($request->query());
        $sizes = $request->query('sizes', []);
        $query = Product::with('mainImage', 'activeDiscount')
                        ->withAvg('reviews', 'rating')
                        ->withCount('reviews');


        if ($request->filled('search')) {
            $search = $request->input('search');

            if ($request->boolean('search_by_id') && is_numeric($search)) {
                $query->where('id', $search);
            } else {
                $query->where('name', 'ILIKE', '%' . $search . '%');
            }
        }


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

        if (!empty($sizes)) {
            $query->whereHas('variants', function ($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
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
        } elseif ($sort === 'release_asc') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'release_desc') {
            $query->orderBy('created_at', 'desc');
        }


        // Paginate with query params
        $products = $query->paginate(9)->withQueryString();

        // Filter data
        $categories = Product::select('category')->distinct()->pluck('category');
        $collections = Collection::whereIn('id', Product::select('collection_id'))->pluck('name', 'id');
        $genders = Product::select('gender')->distinct()->pluck('gender');

        return view('admin_products_catalogue', compact(
            'products',
            'minPrice',
            'maxPrice',
            'categories',
            'collections',
            'genders',
            'sort',
        ));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchById = $request->boolean('searchById');

        $products = Product::with('mainImage')
            ->when($searchById, fn($q) => $q->where('id', $query))
            ->when(!$searchById, fn($q) => $q->where('name', 'ILIKE', '%' . $query . '%'))
            ->take(10)
            ->get();

        return response()->json($products);
    }


}
