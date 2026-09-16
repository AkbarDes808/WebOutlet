<?php

namespace Database\Seeders;

use App\Models\StockItem;
use App\Models\StockItemOutlet;
use Illuminate\Database\Seeder;

class StockItemOutletSeeder extends Seeder
{
    public function run(): void
    {
        $outlets = [
            'outlet 1',
            'outlet 2',
            'outlet 3',
            'outlet 4',
            'outlet 5',
            'outlet 6',
            'outlet 7',
        ];

        $stockItems = StockItem::where('aktif', true)->get();

        foreach ($stockItems as $stockItem) {

            foreach ($outlets as $outlet) {

                StockItemOutlet::firstOrCreate(
                    [
                        'stock_item_id' => $stockItem->id,
                        'outlet' => $outlet,
                    ],
                    [
                        'stok' => 0,
                    ]
                );

            }
        }
    }
}