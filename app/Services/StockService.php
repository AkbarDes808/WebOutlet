<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\StockDeduction;
use Exception;
use Illuminate\Support\Facades\DB;

class StockService
{
    private array $outletMapping = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
        'outlet 8' => 'Outlet 8',
        'outlet 9' => 'Outlet 9',
    ];

    /*
    |--------------------------------------------------------------------------
    | Stock Unlimited
    |--------------------------------------------------------------------------
    */

    private array $unlimitedStockItems = [
        'nasi',
    ];

    /*
    |--------------------------------------------------------------------------
    | DEDUCT
    |--------------------------------------------------------------------------
    */

    public function deductForTransaction(
        int $transactionId,
        array $cart,
        string $outlet
    ): void {
        $outletRole =
            strtolower(
                trim($outlet)
            );

        if (
            !isset(
                $this->outletMapping[
                    $outletRole
                ]
            )
        ) {
            throw new Exception(
                'Outlet transaksi tidak valid: ' .
                $outlet
            );
        }

        $namaOutlet =
            $this->outletMapping[
                $outletRole
            ];

        /*
        |--------------------------------------------------------------------------
        | Jangan deduct dua kali
        |--------------------------------------------------------------------------
        */

        if (
            StockDeduction::where(
                'transaction_id',
                $transactionId
            )->exists()
        ) {
            return;
        }

        if (empty($cart)) {
            throw new Exception(
                'Cart transaksi kosong.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung kebutuhan stok
        |--------------------------------------------------------------------------
        */

        $kebutuhan = [];

        foreach ($cart as $item) {
            $menuName =
                trim(
                    (string) (
                        $item['name']
                        ?? ''
                    )
                );

            $qtyMenu =
                (float) (
                    $item['qty']
                    ?? 0
                );

            if ($menuName === '') {
                throw new Exception(
                    'Nama menu tidak ditemukan dalam cart.'
                );
            }

            if ($qtyMenu <= 0) {
                throw new Exception(
                    'Jumlah menu tidak valid: ' .
                    $menuName
                );
            }

            $menuPrice = (int) ($item['price'] ?? 0);

            $menu = Menu::where(
                'name',
                $menuName
            )
                ->where(
                    'price',
                    $menuPrice
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();

            if (!$menu) {
                throw new Exception(
                    'Menu tidak ditemukan atau tidak aktif: ' .
                    $menuName
                );
            }

            $resep = $menu
                ->stockItems()
                ->with('stockItem')
                ->get();

            if ($resep->isEmpty()) {
                throw new Exception(
                    'Resep stok belum dibuat untuk menu: ' .
                    $menuName
                );
            }

            foreach ($resep as $recipe) {
                if (!$recipe->stockItem) {
                    throw new Exception(
                        'Stock item pada resep menu "' .
                        $menuName .
                        '" tidak ditemukan.'
                    );
                }

                $stockItemId =
                    (int)
                    $recipe->stock_item_id;

                $jumlah =
                    (float)
                    $recipe->jumlah *
                    $qtyMenu;

                if ($jumlah <= 0) {
                    continue;
                }

                $kebutuhan[
                    $stockItemId
                ] =
                    (
                        $kebutuhan[
                            $stockItemId
                        ]
                        ?? 0
                    ) + $jumlah;
            }
        }

        if (empty($kebutuhan)) {
            throw new Exception(
                'Tidak ada kebutuhan stok dari transaksi.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil stock item
        |--------------------------------------------------------------------------
        */

        $stockItemIds =
            array_keys(
                $kebutuhan
            );

        $stockItems = DB::table(
            'stock_items'
        )
            ->whereIn(
                'id',
                $stockItemIds
            )
            ->get()
            ->keyBy('id');

        foreach ($stockItemIds as $stockItemId) {
            if (
                !isset(
                    $stockItems[
                        $stockItemId
                    ]
                )
            ) {
                throw new Exception(
                    'Stock item ID ' .
                    $stockItemId .
                    ' tidak ditemukan.'
                );
            }

            if (
                !$stockItems[
                    $stockItemId
                ]->aktif
            ) {
                throw new Exception(
                    'Stock item "' .
                    $stockItems[
                        $stockItemId
                    ]->nama .
                    '" sedang tidak aktif.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi & lock stok outlet
        |--------------------------------------------------------------------------
        */

        $outletStockRows =
            DB::table(
                'stock_item_outlets'
            )
                ->whereIn(
                    'stock_item_id',
                    $stockItemIds
                )
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$outletRole]
                )
                ->lockForUpdate()
                ->get()
                ->groupBy(
                    'stock_item_id'
                );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI STOK
        |--------------------------------------------------------------------------
        */

        foreach (
            $kebutuhan
            as $stockItemId => $jumlah
        ) {
            $stockItem =
                $stockItems[
                    $stockItemId
                ];

            $namaStock =
                strtolower(
                    trim(
                        $stockItem->nama
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Nasi unlimited
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $namaStock,
                    $this->unlimitedStockItems,
                    true
                )
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Tidak ada row outlet = stok 0
            |--------------------------------------------------------------------------
            */

            if (
                !isset(
                    $outletStockRows[
                        $stockItemId
                    ]
                )
            ) {
                throw new Exception(
                    'Stok "' .
                    $stockItem->nama .
                    '" di ' .
                    $namaOutlet .
                    ' = 0. Transaksi tidak dapat diproses.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Jumlahkan duplicate row
            |--------------------------------------------------------------------------
            */

            $stokTersedia = 0;

            foreach (
                $outletStockRows[
                    $stockItemId
                ] as $row
            ) {
                $stokTersedia +=
                    (float)
                    $row->stok;
            }

            /*
            |--------------------------------------------------------------------------
            | STOK 0 / KURANG = TRANSAKSI DITOLAK
            |--------------------------------------------------------------------------
            */

            if (
                $stokTersedia < $jumlah
            ) {
                throw new Exception(
                    'Stok ' .
                    $stockItem->nama .
                    ' di ' .
                    $namaOutlet .
                    ' tidak mencukupi. ' .
                    'Tersedia: ' .
                    $this->formatNumber(
                        $stokTersedia
                    ) .
                    ', dibutuhkan: ' .
                    $this->formatNumber(
                        $jumlah
                    ) .
                    '.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | POTONG STOK
        |--------------------------------------------------------------------------
        */

        foreach (
            $kebutuhan
            as $stockItemId => $jumlah
        ) {
            $stockItem =
                $stockItems[
                    $stockItemId
                ];

            $namaStock =
                strtolower(
                    trim(
                        $stockItem->nama
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Nasi unlimited:
            | tidak mengurangi physical stock.
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $namaStock,
                    $this->unlimitedStockItems,
                    true
                )
            ) {
                continue;
            }

            $rows =
                $outletStockRows[
                    $stockItemId
                ];

            /*
            |--------------------------------------------------------------------------
            | Karena duplicate data mungkin masih ada,
            | kurangi secara aman dari row-row tersebut.
            |--------------------------------------------------------------------------
            */

            $sisa =
                $jumlah;

            foreach ($rows as $row) {
                if ($sisa <= 0) {
                    break;
                }

                $stokRow =
                    (float) $row->stok;

                if ($stokRow <= 0) {
                    continue;
                }

                $potong =
                    min(
                        $stokRow,
                        $sisa
                    );

                $berhasil =
                    DB::table(
                        'stock_item_outlets'
                    )
                        ->where(
                            'id',
                            $row->id
                        )
                        ->update([
                            'stok' =>
                                $stokRow -
                                $potong,
                            'updated_at' =>
                                now(),
                        ]);

                if ($berhasil !== 1) {
                    throw new Exception(
                        'Gagal mengurangi stok "' .
                        $stockItem->nama .
                        '" di ' .
                        $namaOutlet .
                        '.'
                    );
                }

                $sisa -=
                    $potong;
            }

            if ($sisa > 0) {
                throw new Exception(
                    'Stok ' .
                    $stockItem->nama .
                    ' tidak mencukupi.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN HISTORY DEDUCTION
        |--------------------------------------------------------------------------
        */

        foreach (
            $kebutuhan
            as $stockItemId => $jumlah
        ) {
            $stockItem =
                $stockItems[
                    $stockItemId
                ];

            StockDeduction::create([
                'transaction_id' =>
                    $transactionId,

                'stock_item_id' =>
                    $stockItemId,

                'jumlah' =>
                    $jumlah,

                'keterangan' =>
                    'Transaksi ' .
                    $transactionId .
                    ' - ' .
                    $namaOutlet .
                    ' - ' .
                    $stockItem->nama,
            ]);
        }
    }

    private function formatNumber(
        float $number
    ): string {
        return floor($number) == $number
            ? number_format(
                $number,
                0,
                ',',
                '.'
            )
            : number_format(
                $number,
                2,
                ',',
                '.'
            );
    }
}