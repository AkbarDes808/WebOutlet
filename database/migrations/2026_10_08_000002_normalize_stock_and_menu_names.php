<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('stock_items')
            ->where('nama', 'Saos Cabe')
            ->update(['nama' => 'Saus Cabe']);

        DB::table('menus')
            ->where('name', 'Saos Cabe')
            ->update(['name' => 'Saus Cabe']);
    }

    public function down(): void
    {
        DB::table('stock_items')
            ->where('nama', 'Saus Cabe')
            ->update(['nama' => 'Saos Cabe']);

        DB::table('menus')
            ->where('name', 'Saus Cabe')
            ->update(['name' => 'Saos Cabe']);
    }
};