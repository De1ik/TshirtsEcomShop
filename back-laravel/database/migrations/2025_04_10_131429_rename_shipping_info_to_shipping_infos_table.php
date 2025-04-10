<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('shipping_info', 'shipping_infos');
    }

    public function down(): void
    {
        Schema::rename('shipping_infos', 'shipping_info');
    }
};
