<?php

namespace App\Services;

use App\Models\Bahan;
use App\Models\Menu;
use App\Models\StockDeduction;
use Exception;
use Illuminate\Support\Facades\DB;

class StockService
{
    private array $stockFields = [
        'tepung_roti',
        'tepung_bumbu',
        'garam',
        'bubuk_cabe',
        'telur',
        'gula',
        'ayam',
        'tepung',
        'teh',
        'beras',
        'cup',
        'kertas_chicken_kecil',
        'kertas_chicken_sedang',
        'kertas_chicken_besar',
        'dus_chicken',
        'dus_chicken_jumbo',
        'plastik_cup_isi_1',
        'plastik_cup_isi_2',
        'plastik_ayam_kecil',
        'plastik_sedang',
        'plastik_tanggung',
        'plastik_besar',
        'plastik_jumbo',
    ];

    private array $stockMapping = [
        'Ayam - Sayap' => 'ayam',
        'Ayam - Paha' => 'ayam',
        'Ayam - Dada' => 'ayam',
        'Ayam - Paha Atas' => 'ayam',
        'Teh Kotak' => 'teh',
        'Kotak' => 'dus_chicken',
        'Nasi' => 'beras',
        'Tepung' => 'tepung',
        'Cabe' => 'bubuk_cabe',
    ];

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

        $stockItems = DB::table('stock_items')
            ->whereIn('id', array_keys($kebutuhan))
            ->get()
            ->keyBy('id');

        foreach (array_keys($kebutuhan) as $stockItemId) {
            if (!isset($stockItems[$stockItemId])) {
                throw new Exception(
                    'Stock item ID ' . $stockItemId . ' tidak ditemukan.'
                );
            }
        }

        $stockItemIds = array_keys($kebutuhan);

        $outletStockRows = DB::table('stock_item_outlets')
            ->whereIn('stock_item_id', $stockItemIds)
            ->where('outlet', $outletRole)
            ->lockForUpdate()
            ->get()
            ->keyBy('stock_item_id');

        $outletManagedIds = DB::table('stock_item_outlets')
            ->whereIn('stock_item_id', $stockItemIds)
            ->distinct()
            ->pluck('stock_item_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $outletRequirements = [];
        $bahansRequirements = [];

        foreach ($kebutuhan as $stockItemId => $jumlah) {
            $stockItem = $stockItems[$stockItemId];
            $nama = trim((string) $stockItem->nama);

            if (in_array($stockItemId, $outletManagedIds, true)) {
                if (!isset($outletStockRows[$stockItemId])) {
                    throw new Exception(
                        'Stok "' .
                        $nama .
                        '" belum memiliki mapping ke inventory ' .
                        $namaOutlet .
                        '. Transaksi tidak diproses agar stok tidak salah.'
                    );
                }

                $outletRequirements[$stockItemId] = $jumlah;
                continue;
            }

            if (!isset($this->stockMapping[$nama])) {
                throw new Exception(
                    'Stok "' .
                    $nama .
                    '" belum memiliki mapping ke inventory outlet. ' .
                    'Transaksi tidak diproses agar stok tidak salah.'
                );
            }

            $field = $this->stockMapping[$nama];

            if (!in_array($field, $this->stockFields, true)) {
                throw new Exception(
                    'Kolom stok "' .
                    $field .
                    '" tidak tersedia di inventory.'
                );
            }

            $bahansRequirements[$field] =
                ($bahansRequirements[$field] ?? 0) + $jumlah;
        }

        $stokSekarang = null;

        if (!empty($bahansRequirements)) {
            $stokSekarang = Bahan::where(
                'nama_outlet',
                $namaOutlet
            )
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (!$stokSekarang) {
                throw new Exception(
                    'Stok untuk ' . $namaOutlet . ' belum dibuat.'
                );
            }
        }

        foreach ($outletRequirements as $stockItemId => $jumlah) {
            $row = $outletStockRows[$stockItemId];
            $stok = (float) $row->stok;

            if ($stok < $jumlah) {
                throw new Exception(
                    'Stok ' .
                    $stockItems[$stockItemId]->nama .
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

        if ($stokSekarang) {
            foreach ($bahansRequirements as $field => $jumlah) {
                $stok = (float) ($stokSekarang->{$field} ?? 0);

                if ($stok < $jumlah) {
                    $namaBahan = collect($stockItems)
                        ->filter(function ($item) use ($field) {
                            $nama = trim((string) $item->nama);

                            return isset($this->stockMapping[$nama])
                                && $this->stockMapping[$nama] === $field;
                        })
                        ->pluck('nama')
                        ->implode(', ');

                    throw new Exception(
                        'Stok ' .
                        ($namaBahan ?: $field) .
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
        }

        foreach ($outletRequirements as $stockItemId => $jumlah) {
            $row = $outletStockRows[$stockItemId];

            DB::table('stock_item_outlets')
                ->where('id', $row->id)
                ->update([
                    'stok' => (float) $row->stok - $jumlah,
                ]);
        }

        if ($stokSekarang && !empty($bahansRequirements)) {
            $dataBaru = [
                'nama_outlet' => $namaOutlet,
            ];

            foreach ($this->stockFields as $field) {
                $dataBaru[$field] =
                    (float) ($stokSekarang->{$field} ?? 0);
            }

            foreach ($bahansRequirements as $field => $jumlah) {
                $dataBaru[$field] -= $jumlah;
            }

            $dataBaru['created_at'] = now();
            $dataBaru['updated_at'] = now();

            DB::table('bahans')->insert($dataBaru);
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