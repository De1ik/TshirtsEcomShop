<?php


// User
use App\Models\User;
$user = User::create([
    'first_name' => 'Artem',
    'last_name' => 'Delik',
    'email' => 'artem@example.com',
    'password_hash' => bcrypt('secret123'),
    'gender' => 'male',
    'role' => 'admin',
]);


// Collection
use App\Models\Collection;
$collection = Collection::create([
    'name' => 'CrocadiloBombardiro',
    'release_date' => '2025-03-01',
]);


// Product
use App\Models\Product;
use App\Models\Collection;
$collection = Collection::where('name', 'ClassyWen')->first();

$product = Product::create([
    'collection_id' => $collection->id,
    'name' => 'Crocadilo Bombardiro Shirt',
    'description' => 'Limited edition t-shirt of Crocadilo Bombardiro.',
    'price' => 99.99,
    'is_discount' => false,
    'category' => 'tshirt',
    'gender' => 'unisex',
    'available_amnt' => 2,
]);


// ProductVariant
use App\Models\ProductVariant;
$variant = ProductVariant::create([
    'product_id' => $product->id,
    'size' => 'M',
    'color' => 'Black',
    'sku' => 'SKTV-M-BLK',
    'amount' => 10,
]);


// ProductImage
use App\Models\ProductImage;

$product = Product::where('id', 3)->first();

$image = ProductImage::create([
    'product_id' => $product->id,
    'image_url' => 'tshirt-logo-2.png',
    'is_main' => true,
]);


// DiscountProduct
use App\Models\DiscountProduct;
$discount = DiscountProduct::create([
    'product_id' => $product->id,
    'new_price' => 24.99,
    'date_start' => now()->subDays(2),
    'date_end' => now()->addDays(5),
]);


// Review
use App\Models\Review;
$review = Review::create([
    'user_id' => $user->id,
    'product_id' => $product->id,
    'description' => 'Really nice quality!',
    'rating' => 5,
]);


// Order
use App\Models\Order;
$order = Order::create([
    'user_id' => $user->id,
    'order_date' => now(),
    'delivery_fee' => 4.99,
    'total_amount' => 54.97,
    'status' => 'processing',
]);


// OrderItem
use App\Models\ProductVariant;
use App\Models\OrderItem;
$variant = ProductVariant::first();
$orderItem = OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'variant_id' => $variant->id,
    'quantity' => 2,
    'price_by_one' => 24.99,
]);









