<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipping_infos', function (Blueprint $table) {
            $table->string('country', 100)->nullable()->change();
            $table->string('city', 100)->nullable()->change();
            $table->string('address', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_infos', function (Blueprint $table) {
            $table->string('country', 100)->nullable(false)->change();
            $table->string('city', 100)->nullable(false)->change();
            $table->string('address', 255)->nullable(false)->change();
        });
    }
};
