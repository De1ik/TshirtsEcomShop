<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, $id)
    {
        $product = Product::with(['variants.color', 'mainImage', 'images', 'activeDiscount', 'reviews'])
            ->findOrFail($id);

        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $knownColors = ['red', 'green', 'blue', 'white', 'black', 'yellow', 'purple', 'pink', 'gray', 'brown', 'orange'];

        $imageColors = [];
        foreach ($product->images as $img) {
            $filename = pathinfo($img->image_url, PATHINFO_FILENAME);
            foreach ($knownColors as $color) {
                if (stripos($filename, $color) !== false) {
                    $imageColors[] = strtolower($color);
                    break;
                }
            }
        }

        $imageColors = array_unique($imageColors);
        $selectedColor = $request->query('color') ?? ($imageColors[0] ?? null);
        $selectedSize = $request->query('size');

        $availableVariants = $product->variants->filter(function ($variant) use ($selectedColor) {
            return strtolower($variant->color->name) === $selectedColor;
        });

        $availableSizes = $availableVariants->pluck('size')->unique();

        $selectedVariant = null;
        if ($selectedColor && $selectedSize) {
            $selectedVariant = $product->variants->first(function ($variant) use ($selectedColor, $selectedSize) {
                return strtolower($variant->color->name) === $selectedColor && $variant->size === $selectedSize;
            });
        }

        return view('product_details', compact('product', 'similarProducts', 'selectedColor', 'availableSizes', 'selectedVariant'));
    }


}
