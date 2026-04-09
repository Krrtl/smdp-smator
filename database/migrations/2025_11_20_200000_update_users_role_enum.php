<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use raw SQL to alter enum safely without requiring doctrine/dbal
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','user','tatatertib') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // Revert back to original enum (may fail if rows with tatatertib exist)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};