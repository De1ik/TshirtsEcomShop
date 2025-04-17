<?php

namespace App\Http\Controllers;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Color;

use Illuminate\Http\Request;


class AdminUpdateProductController extends Controller
{
    public function update_product($id) {
        $product = Product::with([
            'variants.color',   // загрузка цвета для каждого варианта
            'variants',         // сами варианты
            'images',           // все изображения продукта
            'activeDiscount'
        ])->findOrFail($id);

        $collections = Collection::all();
        $colors = Color::all();

        // Категории статичные (если не хранятся в БД)
        $categories = [
            ['id' => 1, 'name' => 'tshirts'],
            ['id' => 2, 'name' => 'hoodie'],
        ];

        return view('admin_update_product', compact(
            'product',
            'categories',
            'collections',
            'colors',
        ));
    }
}
