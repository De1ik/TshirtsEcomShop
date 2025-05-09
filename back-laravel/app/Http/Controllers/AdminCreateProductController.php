<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\DiscountProduct;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;



class AdminCreateProductController extends Controller
{
    public function create_product()
    {
        $collections = Collection::all();
        $categories = [
            ['id' => 1, 'name' => 'tshirt'],
            ['id' => 2, 'name' => 'hoodie'],
        ];
        $products = Product::latest()->get();
        $colors = Color::all();
        return view('admin_create_product', compact(
            'categories',
            'collections',
            'products',
            'colors'
        ));
    }

    public function save_new_product(Request $request)
    {
        if ($request->input('product_type') === 'new') {
            return $this->save_product($request);
        } else {
            return $this->save_variant($request);
        }
    }

    public function save_product(Request $request)
    {
        Log::info('➡️ Starting the save process for new product');
        Log::info('📥 Request data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'collection' => 'required|string',
            'amount' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'gender' => 'required|in:male,female,unisex',
            'productPhoto.*' => 'image|max:5120',
            'color-id' => 'required',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'in:XS,S,M,L,XL,XXL',
        ]);

        if ($validated['collection'] === 'new') {
            $request->validate([
                'new_collection_name' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        $exists = Collection::whereRaw('LOWER(name) = ?', [strtolower($value)])->exists();
                        if ($exists) {
                            $fail('A collection with this name already exists.');
                        }
                    }
                ]
            ]);

            $collection = Collection::create([
                'name' => $request->input('new_collection_name'),
            ]);

            $collection_id = $collection->id;
        } else {
            $collection = Collection::find($validated['collection']);
            if (!$collection) {
                return back()->withErrors(['collection' => 'Selected collection not found.'])->withInput();
            }

            $collection_id = $collection->id;
        }



        if ($request->has('enableDiscount')) {
            $discountData = $request->validate([
                'discount-price' => ['required', 'numeric', 'lt:price'],
                'discount-start-date' => ['required', 'date'],
                'discount-end-date' => ['required', 'date', 'after_or_equal:discount-start-date'],
            ]);
        }

        if ($validated['color-id'] === 'new') {
            $request->validate([
                'new_color_name' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (Color::whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                            $fail('A color with this name already exists.');
                        }
                    }
                ],
                'new_color_hex' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
            ]);

            $color_el = Color::create([
                'name' => $request->input('new_color_name'),
                'hex_code' => $request->input('new_color_hex'),
            ]);
        } else {
            $color_el = Color::find($validated['color-id']);
            if (!$color_el) {
                return back()->withErrors(['color-id' => 'Selected color not found.'])->withInput();
            }
        }

        $color_id = $color_el->id;

        try {
            $isDiscountActive = $request->has('enableDiscount') && isset($discountData['discount-price']);
            $finalPrice = $isDiscountActive ? $discountData['discount-price'] : $validated['price'];

            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'final_price' => $finalPrice,
                'category' => $validated['category'],
                'collection_id' => $collection_id,
                'description' => $validated['description'] ?? null,
                'gender' => $validated['gender'],
                'is_discount' => $request->has('enableDiscount'),
            ]);

            if ($request->has('enableDiscount')) {
                DiscountProduct::create([
                    'product_id' => $product->id,
                    'new_price' => $discountData['discount-price'],
                    'date_start' => $discountData['discount-start-date'],
                    'date_end' => $discountData['discount-end-date'],
                ]);
            }

            if ($request->hasFile('productPhoto')) {
                foreach ($request->file('productPhoto') as $index => $imageFile) {
                    $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $imageFile->storeAs('product-photos', $filename, 'public');

                    $product->images()->create([
                        'image_url' => $filename,
                        'is_main' => $index === 0,
                        'color_id' => $color_id,
                    ]);
                }
            }

            foreach ($validated['sizes'] as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color_id' => $color_id,
                    'amount' => $validated['amount'],
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('❌ DB Error: ' . $e->getMessage());
            return back()->with('error', 'Unexpected error occurred.')->withInput();
        }

        return redirect()
            ->route('update_product_index', ['id' => $product->id])
            ->with('success', 'Product was saved');
    }


    public function save_variant(Request $request)
    {
        Log::info('➡️ NEW VARIANT: Starting the save process for new Variant');
        Log::info('📥 Request data:', $request->all());

        $validated = $request->validate([
            'parent_product_id' => 'required|integer|exists:products,id',
            'amount' => 'required|integer|min:0',
            'productPhoto.*' => 'image|max:5120',
            'color-id' => 'required',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'in:XS,S,M,L,XL,XXL',
        ]);

        if ($validated['color-id'] === 'new') {
            $request->validate([
                'new_color_name' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (Color::whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                            $fail('A color with this name already exists.');
                        }
                    }
                ],
                'new_color_hex' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
            ]);

            $color_el = Color::create([
                'name' => $request->input('new_color_name'),
                'hex_code' => $request->input('new_color_hex'),
            ]);
        } else {
            $color_el = Color::find($validated['color-id']);
            if (!$color_el) {
                return back()->withErrors(['color-id' => 'Selected color not found.'])->withInput();
            }
        }

        $color_id = $color_el->id;

        try {
            if ($request->hasFile('productPhoto')) {
                foreach ($request->file('productPhoto') as $index => $imageFile) {
                    $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $imageFile->storeAs('product-photos', $filename, 'public');

                    ProductImage::create([
                        'product_id' => $validated['parent_product_id'],
                        'image_url' => $filename,
                        'is_main' => $index === 0,
                        'color_id' => $color_id,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error during product photo saving: ' . $e->getMessage());
            return back()->withErrors(['productPhoto' => 'Error during product photo saving.'])->withInput();
        }

        try {
            $skippedVariants = [];

            foreach ($validated['sizes'] as $size) {
                $exists = ProductVariant::where('product_id', $validated['parent_product_id'])
                    ->where('color_id', $color_id)
                    ->where('size', $size)
                    ->exists();

                if (!$exists) {
                    ProductVariant::create([
                        'product_id' => $validated['parent_product_id'],
                        'size' => $size,
                        'color_id' => $color_id,
                        'amount' => $validated['amount'],
                    ]);

                    Log::info("🖼 Product variant for size {$size} successfully saved");
                } else {
                    $skippedVariants[] = $size;
                    Log::warning("⚠️ Variant for size {$size} already exists and was skipped");
                }
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error during product variant saving: ' . $e->getMessage());
            return back()->withErrors(['sizes' => 'Error during product variant saving.'])->withInput();
        }

        $message = 'Process was finished successfully!';
        if (!empty($skippedVariants)) {
            $message .= ' The following sizes already existed and were skipped: ' . implode(', ', $skippedVariants) . '.';
        }

        return back()->with('success', $message);
    }

}
