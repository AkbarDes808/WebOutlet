<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Role baru tetap menggunakan constraint users_role_check yang
        // sudah ada. Karena user aplikasi bukan owner tabel, migration
        // tidak melakukan ALTER TABLE.
        //
        // Ubah SPV/Event menjadi role Outlet 8/9 melalui update satu per
        // satu. Nilai baru harus sudah diizinkan oleh constraint yang ada.

        DB::table('users')
            ->whereIn(DB::raw('LOWER(TRIM(role))'), ['event 1', 'event1'])
            ->update(['role' => 'outlet 8']);

        DB::table('users')
            ->whereIn(DB::raw('LOWER(TRIM(role))'), ['event 2', 'event2', 'spv'])
            ->update(['role' => 'outlet 9']);
    }

    public function down(): void
    {
        // Tidak melakukan rollback role secara otomatis karena beberapa
        // user baru mungkin sudah menggunakan Outlet 8/9 setelah migration.
    }
};
