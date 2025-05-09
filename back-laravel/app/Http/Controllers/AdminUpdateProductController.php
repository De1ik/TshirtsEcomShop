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

        $usedColorIds = $product->variants->pluck('color_id')->unique();
        $usedColors = Color::whereIn('id', $usedColorIds)->get();

        $categories = [
            ['id' => 1, 'name' => 'tshirts'],
            ['id' => 2, 'name' => 'hoodie'],
        ];

        return view('admin_update_product', compact(
            'product',
            'categories',
            'collections',
            'colors',
            'usedColors'
        ));
    }


    public function update_product(Request $request, $id)
    {
//         dd($request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'category' => 'required|string',
                'collection' => 'required|string',
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

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'collection_id' => $collection_id,
            'gender' => $validated['gender'],
            'description' => $validated['description'],
        ]);

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

        if ($request->filled('photo_colors')) {
            $photoColors = json_decode($request->input('photo_colors'), true);

            $existingUrls = collect($photoColors)
                ->filter(fn($item) => !Str::startsWith($item['src'], 'data:image'))
                ->map(function ($item) {
                    $fullPath = parse_url($item['src'], PHP_URL_PATH); // /storage/product-photos/filename.jpg
                    Log::info('📝 Full Path ' . $fullPath);
                    return Str::replaceFirst('/storage/', '', $fullPath); // product-photos/filename.jpg
                })
                ->toArray();

            Log::info('📝 Existing relative URLs:', $existingUrls);
        }

        return redirect()->back()->with('success', 'Product updated successfully!');
    }


    public function update_variant(Request $request, $id)
    {
//         dd($request->all());
        try {
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

         $product = Product::findOrFail($id);

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
                 'amount' => $validated['stock'],
             ]);

             return back()->with('success', 'New product variant created.');
         }
    }


    public function delete_variant($id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            $product = $variant->product;

            $variant->delete();

            if ($product->variants()->count() === 0) {
                $product->delete();
                return redirect()->route('admin_default_catalogue')
                    ->with('success', 'Last variant deleted. Product ' . $product->id . ' was also removed.');
            }

            return redirect()->back()
                ->with('success', 'Variant ' . $id . ' deleted successfully.');
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

    public function delete_image(ProductImage $image)
    {
        try {
            Storage::disk('public')->delete('product-photos/' . $image->image_url);

            $image->delete();

            return back()->with('success', 'New product variant created.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete image.');

        }
    }

    public function upload_image(Request $request, Product $product)
    {
        try {
            // Validate the request
            $request->validate([
                'productPhoto' => 'required|array',
                'productPhoto.*' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'color-id' => 'required|exists:colors,id',
            ]);

            // Check if the product already has a main image
            $hasMainImage = $product->images()->where('is_main', true)->exists();
            $addedFirstBase64 = false;

            // Get the color ID from the request
            $colorId = $request->input('color-id');

            // Handle multiple image uploads
            if ($request->hasFile('productPhoto')) {
                foreach ($request->file('productPhoto') as $index => $photo) {
                    $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
                    $photo->storeAs('product-photos', $filename, 'public');

                    $product->images()->create([
                        'image_url' => $filename,
                        'color_id' => $colorId,
                        'is_main' => !$hasMainImage && $index === 0,
                    ]);
                }
            }

            return back()->with('success', 'Product images uploaded successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to upload images: ' . $e->getMessage());
        }
    }


}
