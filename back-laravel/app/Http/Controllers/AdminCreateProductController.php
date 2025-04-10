<?php

namespace App\Http\Controllers;


use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCreateProductController extends Controller
{
    public function create_product() {
        $collections = Collection::all();
        $categories = [
                ['id' => 1, 'name' => 'tshirts'],
                ['id' => 2, 'name' => 'hoodie'],
        ];
        return view('admin_create_product', compact('categories', 'collections'));
    }


    public function save_new_product(Request $request)
    {
//         // Валидация
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'price' => 'required|numeric',
//             'category' => 'required|string',
//             'collection' => 'required|exists:collections,id',
//             'amount' => 'required|integer|min:0',
//             'description' => 'nullable|string',
//             'gender' => 'required|in:men,women,unisex',
//             'productPhoto.*' => 'image|max:5120', // до 5MB
//         ]);
//
//         // Создание продукта
//         $product = Product::create([
//             'name' => $validated['name'],
//             'price' => $validated['price'],
//             'category' => $validated['category'],
//             'collection_id' => $validated['collection'],
//             'available_amnt' => $validated['amount'],
//             'description' => $validated['description'] ?? null,
//             'gender' => $validated['gender'],
//             'is_discount' => $request->has('enableDiscount'),
//             'original_price' => $request->input('discountPrice') ?? null,
//             'discount_start' => $request->input('discountStartDate'),
//             'discount_end' => $request->input('discountEndDate'),
//         ]);
//
//         // Обработка загруженных изображений
//         if ($request->hasFile('productPhoto')) {
//             foreach ($request->file('productPhoto') as $index => $imageFile) {
//                 $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
//                 $path = $imageFile->storeAs('products', $filename, 'public');
//
//                 $product->images()->create([
//                     'image_url' => $path,
//                     'is_main' => $index === 0,
//                 ]);
//             }
//         }
//
//         return redirect()->back()->with('success', 'Product created successfully!');
        dd($request->all(), $request->file('productPhoto'));
    }

}
