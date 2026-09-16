<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuStockItem;
use App\Models\StockItem;
use Illuminate\Database\Seeder;

class MenuStockItemSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Helper
        |--------------------------------------------------------------------------
        */

        $stockId = function (string $nama): int {
            $stock = StockItem::where('nama', $nama)->first();

            if (!$stock) {
                throw new \RuntimeException(
                    "Stock item '{$nama}' tidak ditemukan."
                );
            }

            return $stock->id;
        };

        $menuId = function (string $nama): int {
            $menu = Menu::where('name', $nama)->first();

            if (!$menu) {
                throw new \RuntimeException(
                    "Menu '{$nama}' tidak ditemukan."
                );
            }

            return $menu->id;
        };

        /*
        |--------------------------------------------------------------------------
        | Dummy Pemakaian Stok
        |--------------------------------------------------------------------------
        |
        | Angka ini masih DUMMY.
        | Nanti bisa diubah setelah hasil diskusi.
        |
        */

        $recipes = [

            'Nasi' => [
                'Nasi' => 1,
            ],

            'Sambel' => [
                'Saus Sambal Sachet' => 1,
                'Cabe' => 5,
            ],

            'Es Teh' => [
                'Teh Kotak' => 1,
            ],

            'Ayam Original' => [
                'Ayam - Paha' => 1,
                'Tepung' => 50,
                'Kotak' => 1,
            ],

            'Paket Geprek + Es' => [
                'Ayam - Paha' => 1,
                'Nasi' => 1,
                'Teh Kotak' => 1,
                'Saus Sambal Sachet' => 1,
                'Tepung' => 50,
                'Cabe' => 10,
                'Kotak' => 1,
            ],

            'Paket Ori + Es' => [
                'Ayam - Paha' => 1,
                'Nasi' => 1,
                'Teh Kotak' => 1,
                'Saus Tomat Sachet' => 1,
                'Tepung' => 50,
                'Kotak' => 1,
            ],

            'Ayam Geprek' => [
                'Ayam - Paha' => 1,
                'Saus Sambal Sachet' => 1,
                'Tepung' => 50,
                'Cabe' => 10,
                'Kotak' => 1,
            ],

            'Ayam Nasi' => [
                'Ayam - Paha' => 1,
                'Nasi' => 1,
                'Tepung' => 50,
                'Kotak' => 1,
            ],

            'Ayam Geprek + Nasi' => [
                'Ayam - Paha' => 1,
                'Nasi' => 1,
                'Saus Sambal Sachet' => 1,
                'Tepung' => 50,
                'Cabe' => 10,
                'Kotak' => 1,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */

        foreach ($recipes as $menuName => $items) {

            $menuIdValue = $menuId($menuName);

            foreach ($items as $stockName => $jumlah) {

                MenuStockItem::updateOrCreate(
                    [
                        'menu_id' => $menuIdValue,
                        'stock_item_id' => $stockId($stockName),
                    ],
                    [
                        'jumlah' => $jumlah,
                    ]
                );
            }
        }
    }
}