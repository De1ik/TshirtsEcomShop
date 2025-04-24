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
        // PostgreSQL specific: drop the check constraint
        DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_method_check");

        // Re-add the constraint with updated enum values
        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_payment_method_check
            CHECK (payment_method IN ('card', 'paypal', 'cash', 'google_pay', 'apple_pay'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original constraint if needed
        DB::statement("ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_method_check");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_payment_method_check
            CHECK (payment_method IN ('card', 'paypal', 'cash'))
        ");
    }
};
