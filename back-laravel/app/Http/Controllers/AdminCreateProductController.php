<?php

namespace App\Http\Controllers;


use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DiscountProduct;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;;

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
        Log::info('➡️ Starting the save process for new product');
        Log::info('📥 Request data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'collection' => 'required|exists:collections,id',
            'amount' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'gender' => 'required|in:male,female,unisex',
            'productPhoto.*' => 'image|max:5120', // до 5MB
        ]);

        if ($request->has('enableDiscount')) {
            $discountData = $request->validate([
                'discount-price'   => ['required', 'numeric', 'lt:price'],
                'discount-start-date'  => ['required', 'date'],
                'discount-end-date'    => ['required', 'date', 'after_or_equal:discount-start-date'],
            ]);
        }

        Log::info('✅ Validation of basic product info was success', $validated);

        try{
            $isDiscountActive = $request->has('enableDiscount') && $discountData['discount-price'];
            $finalPrice = $isDiscountActive ? $discountData['discount-price'] : $validated['price'];

            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'final_price' => $finalPrice,
                'category' => $validated['category'],
                'collection_id' => $validated['collection'],
                'available_amnt' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'gender' => $validated['gender'],
                'is_discount' => $request->has('enableDiscount'),
            ]);

            if ($request->has('enableDiscount')){

                Log::info('✅ Validation of discount info was success', $validated);

                $discount_product = DiscountProduct::create([
                    'product_id' => $product->id,
                    'new_price' => $discountData['discount-price'],
                    'date_start' => $discountData['discount-start-date'],
                    'date_end' => $discountData['discount-end-date'],
                ]);
            }

            Log::info('🆗 Product was successfully created', ['id' => $product->id]);

            if ($request->hasFile('productPhoto')) {
                foreach ($request->file('productPhoto') as $index => $imageFile) {
                    $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $path = $imageFile->storeAs('products', $filename, 'public');

                    $product->images()->create([
                        'image_url' => $path,
                        'is_main' => $index === 0,
                    ]);
                }

                Log::info('🖼 Images successfully saved');
            }


            return back()->with('success', 'Product was saved');
        } catch (\Throwable $e) {
            Log::error('❌ Error during product saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }
    }

}
