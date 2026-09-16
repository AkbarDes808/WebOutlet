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
    ];

    public function deductForTransaction(
        int $transactionId,
        array $cart,
        string $outlet
    ): void {
        $outletRole = strtolower(trim($outlet));

        if (!isset($this->outletMapping[$outletRole])) {
            throw new Exception(
                'Outlet transaksi tidak valid: ' . $outlet
            );
        }

        $namaOutlet = $this->outletMapping[$outletRole];

        if (
            StockDeduction::where(
                'transaction_id',
                $transactionId
            )->exists()
        ) {
            return;
        }

        if (empty($cart)) {
            throw new Exception('Cart transaksi kosong.');
        }

        $kebutuhan = [];

        foreach ($cart as $item) {
            $menuName = trim((string) ($item['name'] ?? ''));
            $qtyMenu = (float) ($item['qty'] ?? 0);

            if ($menuName === '') {
                throw new Exception(
                    'Nama menu tidak ditemukan dalam cart.'
                );
            }

            if ($qtyMenu <= 0) {
                throw new Exception(
                    'Jumlah menu tidak valid: ' . $menuName
                );
            }

            $menu = Menu::where('name', $menuName)
                ->where('is_active', true)
                ->first();

            if (!$menu) {
                throw new Exception(
                    'Menu tidak ditemukan atau tidak aktif: ' .
                    $menuName
                );
            }

            $resep = $menu->stockItems()
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

                $stockItemId = (int) $recipe->stock_item_id;
                $jumlah = (float) $recipe->jumlah * $qtyMenu;

                if ($jumlah <= 0) {
                    continue;
                }

                $kebutuhan[$stockItemId] =
                    ($kebutuhan[$stockItemId] ?? 0) + $jumlah;
            }
        }

        if (empty($kebutuhan)) {
            throw new Exception(
                'Tidak ada kebutuhan stok dari transaksi.'
            );
        }

        $stockItemIds = array_keys($kebutuhan);

        $stockItems = DB::table('stock_items')
            ->whereIn('id', $stockItemIds)
            ->get()
            ->keyBy('id');

        foreach ($stockItemIds as $stockItemId) {
            if (!isset($stockItems[$stockItemId])) {
                throw new Exception(
                    'Stock item ID ' .
                    $stockItemId .
                    ' tidak ditemukan.'
                );
            }

            if (!$stockItems[$stockItemId]->aktif) {
                throw new Exception(
                    'Stock item "' .
                    $stockItems[$stockItemId]->nama .
                    '" sedang tidak aktif.'
                );
            }
        }

        $outletStockRows = DB::table('stock_item_outlets')
            ->whereIn('stock_item_id', $stockItemIds)
            ->whereRaw(
                'LOWER(TRIM(outlet)) = ?',
                [$outletRole]
            )
            ->lockForUpdate()
            ->get()
            ->keyBy('stock_item_id');

        foreach ($kebutuhan as $stockItemId => $jumlah) {
            $stockItem = $stockItems[$stockItemId];

            if (!isset($outletStockRows[$stockItemId])) {
                throw new Exception(
                    'Stok "' .
                    $stockItem->nama .
                    '" belum memiliki mapping ke inventory ' .
                    $namaOutlet .
                    '. Transaksi tidak diproses agar stok tidak salah.'
                );
            }

            $row = $outletStockRows[$stockItemId];
            $stok = (float) $row->stok;

            if ($stok < $jumlah) {
                throw new Exception(
                    'Stok ' .
                    $stockItem->nama .
                    ' di ' .
                    $namaOutlet .
                    ' tidak mencukupi. Tersedia: ' .
                    $this->formatNumber($stok) .
                    ', dibutuhkan: ' .
                    $this->formatNumber($jumlah) .
                    '.'
                );
            }
        }

        foreach ($kebutuhan as $stockItemId => $jumlah) {
            $row = $outletStockRows[$stockItemId];

            $berhasil = DB::table('stock_item_outlets')
                ->where('id', $row->id)
                ->update([
                    'stok' => (float) $row->stok - $jumlah,
                    'updated_at' => now(),
                ]);

            if ($berhasil !== 1) {
                throw new Exception(
                    'Gagal mengurangi stok "' .
                    $stockItems[$stockItemId]->nama .
                    '" di ' .
                    $namaOutlet .
                    '.'
                );
            }
        }

        foreach ($kebutuhan as $stockItemId => $jumlah) {
            StockDeduction::create([
                'transaction_id' => $transactionId,
                'stock_item_id' => $stockItemId,
                'jumlah' => $jumlah,
                'keterangan' =>
                    'Transaksi ' .
                    $transactionId .
                    ' - ' .
                    $namaOutlet .
                    ' - ' .
                    $stockItems[$stockItemId]->nama,
            ]);
        }
    }

    private function formatNumber(float $number): string
    {
        return floor($number) == $number
            ? number_format($number, 0, ',', '.')
            : number_format($number, 2, ',', '.');
    }
}