<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function show(Request $request, $id)
    {
        $product = Product::with(['variants.color', 'mainImage', 'images', 'activeDiscount', 'reviews'])
            ->findOrFail($id);

        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(2)
            ->get();

        $firstVariant = $product->variants->first();

        $selectedColor = $request->query('color') ?? strtolower(optional($firstVariant->color)->name);
        $selectedSize = $request->query('size') ?? $firstVariant->size ?? null;

        $variantsOfColor = $product->variants->filter(function ($variant) use ($selectedColor) {
            return strtolower($variant->color->name) === $selectedColor;
        });

        $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $availableSizes = $variantsOfColor
            ->filter(fn($v) => $v->amount > 0)
            ->pluck('size')
            ->unique()
            ->sortBy(fn($size) => array_search($size, $sizeOrder) !== false ? array_search($size, $sizeOrder) : 999);

        $selectedVariant = $variantsOfColor->first(function ($v) use ($selectedSize) {
            return $v->size === $selectedSize;
        });

        return view('product_details', compact(
            'product',
            'similarProducts',
            'selectedColor',
            'selectedSize',
            'availableSizes',
            'selectedVariant',
            'variantsOfColor'
        ));
    }

}
