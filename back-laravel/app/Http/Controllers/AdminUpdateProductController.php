<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Color;
use App\Models\DiscountProduct;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminUpdateProductController extends Controller
{
    public function index($id) {
        $product = Product::with([
            'variants' => function ($query) {
                $query
                    ->orderBy('color_id')
                    ->orderByRaw("
                      CASE size
                        WHEN 'XS' THEN 1
                        WHEN 'S' THEN 2
                        WHEN 'M' THEN 3
                        WHEN 'L' THEN 4
                        WHEN 'XL' THEN 5
                        WHEN 'XXL' THEN 6
                        ELSE 999
                      END
                    ");
            },
            'images',
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
//     public function update(Request $request, $id)
//     {
//         $product = Product::findOrFail($id);
//
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'price' => 'required|numeric|min:0',
//             'description' => 'required|string',
//             // добавь другие поля по необходимости
//         ]);
//
//         $product->update($validated);
//
//         return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
//     }
    public function update_product(Request $request, $id)
    {
//         dd($request->all());


        try {
            // ✅ Валидация данных
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'category' => 'required|string',
                'collection' => 'required|exists:collections,id',
                'description' => 'nullable|string',
                'gender' => 'required|in:male,female,unisex',
                'discount-price' => 'nullable|numeric|min:0',
                'discount-start-date' => 'nullable|date',
                'discount-end-date' => 'nullable|date|after_or_equal:discount-start-date',
                'productPhoto.*' => 'image|max:5120',
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Error during product validation: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

        $product = Product::findOrFail($id);

        // ✅ Обновление базовых полей
        $product->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'collection_id' => $validated['collection'],
            'gender' => $validated['gender'],
            'description' => $validated['description'],
        ]);

        // ✅ Обработка скидки
        if ($request->has('enableDiscount')) {
            $product->update([
                'is_discount' => true,
                'final_price' => $validated['discount-price'],
            ]);

            DiscountProduct::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'new_price' => $validated['discount-price'],
                    'date_start' => $validated['discount-start-date'],
                    'date_end' => $validated['discount-end-date'],
                ]
            );
        } else {
            $product->update([
                'is_discount' => false,
                'final_price' => $validated['price'],
            ]);
            $product->discount()->delete();
        }

        // ✅ Обработка изображений
        if ($request->filled('photo_colors')) {
            $photoColors = json_decode($request->input('photo_colors'), true);

            // Получаем относительные пути уже существующих изображений (без base64)
            // Оставляем только относительные пути из src
            $existingUrls = collect($photoColors)
                ->filter(fn($item) => !Str::startsWith($item['src'], 'data:image'))
                ->map(function ($item) {
                    // Получаем путь из полного URL
                    $fullPath = parse_url($item['src'], PHP_URL_PATH); // /storage/product-photos/filename.jpg
                    Log::info('📝 Full Path ' . $fullPath);
                    return Str::replaceFirst('/storage/', '', $fullPath); // product-photos/filename.jpg
                })
                ->toArray();

            Log::info('📝 Existing relative URLs:', $existingUrls);




            // Удаляем изображения, которых больше нет в списке
            foreach ($product->images as $image) {
                if (!in_array($image->image_url, $existingUrls)) {
                    Storage::disk('public')->delete($image->image_url);
                    $image->delete();
                }
            }

            $hasMainImage = $product->images()->where('is_main', true)->exists();
            $addedFirstBase64 = false;

            // Обрабатываем и сохраняем новые фото (base64)
            foreach ($photoColors as $photo) {
                if (Str::startsWith($photo['src'], 'data:image')) {
                    preg_match('/data:image\/(\w+);base64,/', $photo['src'], $matches);
                    $extension = $matches[1] ?? 'jpg';
                    $base64Str = substr($photo['src'], strpos($photo['src'], ',') + 1);
                    $binaryData = base64_decode($base64Str);

                    $filename = Str::uuid() . '.' . $extension;
                    $path = "product-photos/{$filename}";
                    Storage::disk('public')->put($path, $binaryData);

                    // Найти или создать цвет
                    $color = Color::find($photo['color']);
                    if (!$color) {
                        Log::warning("⚠️ Color not found for ID: {$photo['color']}");
                        continue;
                    }

                    $isMain = !$hasMainImage && !$addedFirstBase64;
                    if ($isMain) {
                        $addedFirstBase64 = true;
                    }

                    // Сохраняем изображение
                    $product->images()->create([
                        'image_url' => $path,
                        'color_id' => $color->id,
                        'is_main' => $isMain,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Product updated successfully!');
    }


    public function update_variant(Request $request, $id)
    {
//         dd($request->all());
        try {
            // ✅ Валидация данных
            $validated = $request->validate([
                'stock' => 'required|integer|min:0',
                'size' => 'in:XS,S,M,L,XL,XXL',
                'color-id' => 'required',
                'sku' => 'nullable',
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Error during product validation: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

        $color_id = $validated['color-id'];

        try{
            if ($validated['color-id'] == 'new'){
                $colorValidate = $request->validate([
                    'new_color_name'   => ['required', 'unique:colors,name'],
                    'new_color_hex'  => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
                ]);

                Log::info('✅ Validation of product color info was success', $validated);

                $color_el = Color::create([
                    'name' => $colorValidate['new_color_name'],
                    'hex_code' => $colorValidate['new_color_hex'],
                ]);

                $color_id = $color_el->id;

                Log::info('✅ New color was created', $validated);
            }
            else {
                $color_el = Color::find($validated['color-id']);
            }
            if (!$color_el) {
                return back()->with('error', 'Selected color not found.');
            }

            $color_id = $color_el->id;
        } catch (\Throwable $e) {
            Log::error('❌ Error during color validation and saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

        if (!empty($validated['sku'])) {
            $variant = ProductVariant::findOrFail($validated['sku']);
            $variant->amount = $validated['stock'];
            $variant->save();
            return back()->with('success', 'Variant ' . $validated['sku'] . ' was successfully updated with amount ' . $validated['stock']);
        }

         // 2. Найти продукт
         $product = Product::findOrFail($id);

         // 3. Проверить, существует ли такой вариант (по размеру и цвету)
         $existingVariant = $product->variants()
             ->where('size', $validated['size'])
             ->where('color_id', $color_id)
             ->first();

         if ($existingVariant) {
             return back()->with('error', 'Product exists.');
         } else {
             $product->variants()->create([
                 'size' => $validated['size'],
                 'color_id' => $color_id,
                 'amount' => $validated['stock'], // 'sku' — это количество
             ]);

             return back()->with('success', 'New product variant created.');
         }
    }


    public function delete_variant($id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            $variant->delete();

            return redirect()->back()->with('success', 'Variant ' . $id . ' deleted successfully.');
        } catch (\Exception $e) {
            Log::error('❌ Error deleting variant: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete variant.');
        }
    }

    public function delete_product($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return redirect()->route('admin_default_catalogue')->with('success', 'Product ' . $id . ' deleted successfully.');
        } catch (\Exception $e) {
            Log::error('❌ Error deleting product: ' . $e->getMessage());
            return redirect()->route('admin_default_catalogue')->with('error', 'Failed to delete variant.');
        }
    }
}
