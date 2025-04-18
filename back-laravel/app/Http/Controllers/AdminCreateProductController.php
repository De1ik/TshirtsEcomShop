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

class AdminCreateProductController extends Controller
{
    public function create_product() {
        $collections = Collection::all();
        $categories = [
                ['id' => 1, 'name' => 'tshirts'],
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

    public function save_new_product(Request $request){
        if ($request->input('product_type') === 'new') {
            return $this->save_product($request);
        } else {
            return $this->save_variant($request);
        }
    }

    public function save_product(Request $request)
    {
//         dd($request->all());
        Log::info('➡️ Starting the save process for new product');
        Log::info('📥 Request data:', $request->all());

        try{
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'category' => 'required|string',
                'collection' => 'required|exists:collections,id',
                'amount' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'gender' => 'required|in:male,female,unisex',
                'productPhoto.*' => 'image|max:5120', // до 5MB
                'color-id' => 'required',
                'sizes' => 'required|array|min:1',
                'sizes.*' => 'in:XS,S,M,L,XL,XXL',
            ]);

            $sizes = $request->input('sizes', []);
            $color = $request->input('color');

            Log::info('✅ Validation of basic product info was success', $validated);


            if ($request->has('enableDiscount')) {
                $discountData = $request->validate([
                    'discount-price'   => ['required', 'numeric', 'lt:price'],
                    'discount-start-date'  => ['required', 'date'],
                    'discount-end-date'    => ['required', 'date', 'after_or_equal:discount-start-date'],
                ]);
                Log::info('✅ Validation of product discount info was success', $discountData);
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error during product basic info validation: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

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
        } catch (\Throwable $e) {
            Log::error('❌ Error during product saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

        try{
            if ($request->has('enableDiscount')){

                $discount_product = DiscountProduct::create([
                    'product_id' => $product->id,
                    'new_price' => $discountData['discount-price'],
                    'date_start' => $discountData['discount-start-date'],
                    'date_end' => $discountData['discount-end-date'],
                ]);

                Log::info('✅ Discount for product was successfully created', ['id' => $product->id]);
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error during discount for product saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

//             Log::info('🆗 Product was successfully created', ['id' => $product->id]);


        try{
            if ($request->hasFile('productPhoto')) {
                foreach ($request->file('productPhoto') as $index => $imageFile) {
                    $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $path = $imageFile->storeAs('products', $filename, 'public');

                    $product->images()->create([
                        'image_url' => $path,
                        'is_main' => $index === 0,
                        'color_id' => $color_id,
                    ]);
                }

                Log::info('🖼 Images successfully saved');
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error during product photo saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }


        try {
            foreach ($validated['sizes'] as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color_id' => $color_id,
                    'amount' => $validated['amount'],
                ]);

                Log::info('🖼 Product variant for $size successfully saved');
            }

        } catch (\Throwable $e) {
            Log::error('❌ Error during product variant saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

        return back()->with('success', 'Product was saved');
    }




    public function save_variant(Request $request)
    {
//         dd($request->all());
        Log::info('➡️ NEW VARIANT: Starting the save process for new Variant');
        Log::info('📥 Request data:', $request->all());

        try{
            $validated = $request->validate([
                'parent_product_id' => 'required|integer',
                'amount' => 'required|integer|min:0',
                'productPhoto.*' => 'image|max:5120', // до 5MB
                'color-id' => 'required',
                'sizes' => 'required|array|min:1',
                'sizes.*' => 'in:XS,S,M,L,XL,XXL',
            ]);

            $sizes = $request->input('sizes', []);
            $color = $request->input('color');

            Log::info('✅ Validation of basic variant info was success', $validated);


        } catch (\Throwable $e) {
            Log::error('❌ Error during product basic info validation: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
        }

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

        try{
            if ($request->hasFile('productPhoto')) {
                foreach ($request->file('productPhoto') as $index => $imageFile) {
                    $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
                    $path = $imageFile->storeAs('products', $filename, 'public');

                    $image = ProductImage::create([
                        'product_id' => $validated['parent_product_id'],
                        'image_url' => $path,
                        'is_main' => $index === 0,
                        'color_id' => $color_id,
                    ]);
                }

                Log::info('🖼 Images successfully saved');
            }
        } catch (\Throwable $e) {
            Log::error('❌ Error during product photo saving: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs');
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
            return back()->with('error', 'Something went wrong. Check logs');
        }

        $message = 'Process was finished successfully!';

        if (!empty($skippedVariants)) {
            $message .= ' The following sizes already existed and were skipped: ' . implode(', ', $skippedVariants) . '.';
        }
        return back()->with('success', $message);
    }
}
