<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');

            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL'])->nullable();
            $table->string('color', 50)->nullable();
            $table->string('sku', 100)->unique();
            $table->integer('amount')->default(0);

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
