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
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_method_check");

            DB::statement("
                ALTER TABLE payments
                ADD CONSTRAINT payments_payment_method_check
                CHECK (payment_method IN ('card', 'paypal', 'cash', 'google_pay', 'apple_pay'))
            ");
        } elseif ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE payments
                MODIFY payment_method ENUM('card', 'paypal', 'cash', 'google_pay', 'apple_pay') NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_method_check");

            DB::statement("
                ALTER TABLE payments
                ADD CONSTRAINT payments_payment_method_check
                CHECK (payment_method IN ('card', 'paypal', 'cash'))
            ");
        } elseif ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE payments
                MODIFY payment_method ENUM('card', 'paypal', 'cash') NOT NULL
            ");
        }
    }
};
