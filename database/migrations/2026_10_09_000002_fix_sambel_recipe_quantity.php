<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu_stock_items')
            ->whereIn('menu_id', function ($query) {
                $query->select('id')
                    ->from('menus')
                    ->whereRaw('LOWER(TRIM(name)) = ?', ['sambel']);
            })
            ->whereIn('stock_item_id', function ($query) {
                $query->select('id')
                    ->from('stock_items')
                    ->whereRaw('LOWER(TRIM(nama)) = ?', ['kantong sambal']);
            })
            ->update([
                'jumlah' => 1,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Do not restore the previous quantity of 5 because it is not desired.
    }
};
