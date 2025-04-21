<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $product_id) {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to leave a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($product_id);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->input('rating'),
            'description' => $request->input('description'),
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
