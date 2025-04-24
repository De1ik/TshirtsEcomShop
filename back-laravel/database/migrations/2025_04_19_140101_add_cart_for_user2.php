<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cartId = DB::table('carts')->insertGetId([
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cart_items')->insert([
            'cart_id' => $cartId,
            'product_variant_id' => 1,
            'quantity' => 2,
            'unit_price' => 29.99,
            'total_price' => 59.98,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('cart_items')->where('product_variant_id', 1)->delete();
        DB::table('carts')->where('user_id', 10)->delete();
    }
};
