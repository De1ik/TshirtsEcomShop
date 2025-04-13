<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE users ALTER COLUMN first_name DROP NOT NULL');
        DB::statement('ALTER TABLE users ALTER COLUMN last_name DROP NOT NULL');

        // Make gender nullable
        DB::statement('ALTER TABLE users ALTER COLUMN gender DROP NOT NULL');

        // Set default value for role to 'users'
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'users'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users ALTER COLUMN first_name SET NOT NULL');
        DB::statement('ALTER TABLE users ALTER COLUMN last_name SET NOT NULL');

        // Revert: make gender not nullable
        DB::statement('ALTER TABLE users ALTER COLUMN gender SET NOT NULL');

        // Remove default for role
        DB::statement("ALTER TABLE users ALTER COLUMN role DROP DEFAULT");
    }
};
