<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('admin', 'user', 'agent') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::table('users')->where('type', 'agent')->update(['type' => 'user']);

        DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
