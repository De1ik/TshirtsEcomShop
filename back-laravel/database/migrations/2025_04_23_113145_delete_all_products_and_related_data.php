<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('product_images')->delete();
        DB::table('product_variants')->delete();
        DB::table('discount_products')->delete();
        DB::table('reviews')->delete();
        DB::table('products')->delete();
        DB::table('carts')->delete();
        DB::table('cart_items')->delete();
        DB::table('collections')->delete();
        DB::table('colors')->delete();
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('payments')->delete();
    }

    public function down(): void
    {
        logger('Products and related data cannot be restored after this migration.');
    }
};
