<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id')->nullable();

            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->boolean('is_discount')->default(false);

            $table->enum('category', ['tshirt', 'hoodie'])->nullable();
            $table->enum('gender', ['male', 'female', 'unisex'])->nullable();

            $table->integer('available_amnt')->default(0);
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
