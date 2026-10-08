<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $recipes = [
            'Ayam Geprek' => [
                'Ayam' => 1,
                'Kantong Sambal' => 1,
            ],
            'Ayam Geprek + Nasi' => [
                'Ayam' => 1,
                'Kantong Sambal' => 1,
                'Nasi' => 1,
            ],
            'Paket Ayam Geprek' => [
                'Ayam' => 1,
                'Kantong Sambal' => 1,
                'Nasi' => 1,
            ],
            'Paket Ayam Geprek + Es Teh' => [
                'Ayam' => 1,
                'Kantong Sambal' => 1,
                'Nasi' => 1,
                'Teh Kotak' => 1,
            ],
            'Paket Geprek + Es' => [
                'Ayam' => 1,
                'Kantong Sambal' => 1,
                'Nasi' => 1,
                'Teh Kotak' => 1,
            ],
            'Ayam Krispi' => [
                'Ayam' => 1,
            ],
            'Paket Ayam Krispi' => [
                'Ayam' => 1,
                'Nasi' => 1,
            ],
            'Paket Ayam Krispi + Es Teh' => [
                'Ayam' => 1,
                'Nasi' => 1,
                'Teh Kotak' => 1,
            ],
        ];

        foreach ($recipes as $menuName => $recipe) {
            $menu = DB::table('menus')
                ->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($menuName)])
                ->first();

            if (!$menu) {
                continue;
            }

            DB::table('menu_stock_items')
                ->where('menu_id', $menu->id)
                ->delete();

            foreach ($recipe as $stockName => $jumlah) {
                $stockItem = DB::table('stock_items')
                    ->whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($stockName)])
                    ->where('aktif', true)
                    ->first();

                if (!$stockItem) {
                    throw new RuntimeException(
                        'Stock item "' . $stockName . '" tidak ditemukan.'
                    );
                }

                DB::table('menu_stock_items')->insert([
                    'menu_id' => $menu->id,
                    'stock_item_id' => $stockItem->id,
                    'jumlah' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Tidak mengembalikan recipe lama karena recipe lama tidak lagi valid.
    }
};
