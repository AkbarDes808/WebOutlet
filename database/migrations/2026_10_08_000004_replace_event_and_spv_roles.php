<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Lepas constraint lama terlebih dahulu agar role baru bisa diisi.
        DB::statement("
            ALTER TABLE users
            DROP CONSTRAINT IF EXISTS users_role_check
        ");

        // Migrasikan role lama.
        DB::statement("
            UPDATE users
            SET role = 'outlet 8'
            WHERE LOWER(TRIM(role)) IN ('event 1', 'event1')
        ");

        DB::statement("
            UPDATE users
            SET role = 'outlet 9'
            WHERE LOWER(TRIM(role)) IN ('event 2', 'event2', 'spv')
        ");

        // Role resmi sekarang: Admin dan Outlet 1–9.
        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT users_role_check
            CHECK (
                role IN (
                    'admin',
                    'outlet 1',
                    'outlet 2',
                    'outlet 3',
                    'outlet 4',
                    'outlet 5',
                    'outlet 6',
                    'outlet 7',
                    'outlet 8',
                    'outlet 9'
                )
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            DROP CONSTRAINT IF EXISTS users_role_check
        ");

        DB::statement("
            UPDATE users
            SET role = 'event 1'
            WHERE role = 'outlet 8'
        ");

        DB::statement("
            UPDATE users
            SET role = 'SPV'
            WHERE role = 'outlet 9'
        ");

        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT users_role_check
            CHECK (role IN ('admin', 'SPV', 'outlet'))
        ");
    }
};
