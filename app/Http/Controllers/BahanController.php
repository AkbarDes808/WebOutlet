<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanController extends Controller
{
    private array $rows = [
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

    private array $historyLabels = [
        'tepung_roti' => 'Tepung Roti',
        'tepung_bumbu' => 'Tepung Bumbu',
        'garam' => 'Garam',
        'bubuk_cabe' => 'Cabe',
        'telur' => 'Telur',
        'gula' => 'Gula',
        'ayam' => 'Ayam',
        'tepung' => 'Tepung',
        'teh' => 'Teh Kotak',
        'beras' => 'Nasi',
        'cup' => 'Kotak',
        'kertas_chicken_kecil' => 'Kertas Chicken Kecil',
        'kertas_chicken_sedang' => 'Kertas Chicken Sedang',
        'kertas_chicken_besar' => 'Kertas Chicken Besar',
        'dus_chicken' => 'Dus Chicken',
        'dus_chicken_jumbo' => 'Dus Chicken Jumbo',
        'plastik_cup_isi_1' => 'Plastik Cup Isi 1',
        'plastik_cup_isi_2' => 'Plastik Cup Isi 2',
        'plastik_ayam_kecil' => 'Plastik Ayam Kecil',
        'plastik_sedang' => 'Plastik Sedang',
        'plastik_tanggung' => 'Plastik Tanggung',
        'plastik_besar' => 'Plastik Besar',
        'plastik_jumbo' => 'Plastik Jumbo',
    ];

    private array $stockItemMapping = [
        'ayam' => 12,
        'tepung' => 10,
        'teh' => 5,
        'beras' => 7,
        'cup' => 6,
        'bubuk_cabe' => 11,
    ];

    private array $stockItemNames = [
        5 => 'Teh Kotak',
        6 => 'Kotak',
        7 => 'Nasi',
        8 => 'Saus Sambal Sachet',
        9 => 'Saus Tomat Sachet',
        10 => 'Tepung',
        11 => 'Cabe',
        12 => 'Ayam',
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

    private array $outlets = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
    ];

    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $this->getUserRole();

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        $selectedOutlet = $this->resolveOutlet($request, $role, $isAdminOrSpv);

        $namaOutlet = $this->outletMapping[$selectedOutlet];

        $stockItems = DB::table('stock_items')
            ->where('aktif', true)
            ->orderBy('id')
            ->get();

        $stockOutletRows = DB::table('stock_item_outlets')
            ->whereRaw(
                'LOWER(TRIM(outlet)) = ?',
                [$selectedOutlet]
            )
            ->get()
            ->keyBy('stock_item_id');

        $totalStok = [];

        foreach ($stockItems as $stockItem) {
            $totalStok[$stockItem->id] = isset($stockOutletRows[$stockItem->id])
                ? (float) $stockOutletRows[$stockItem->id]->stok
                : 0;
        }

        $allOutletStocks = DB::table('stock_item_outlets')
            ->get()
            ->groupBy('stock_item_id');

        $totalSemuaOutlet = [];

        foreach ($stockItems as $stockItem) {
            $totalSemuaOutlet[$stockItem->id] = 0;

            if (isset($allOutletStocks[$stockItem->id])) {
                foreach ($allOutletStocks[$stockItem->id] as $row) {
                    $totalSemuaOutlet[$stockItem->id] += (float) $row->stok;
                }
            }
        }

        $bahan = new Bahan();
        $bahan->nama_outlet = $namaOutlet;

        return view('bahans.index', [
            'bahan' => $bahan,
            'selectedOutlet' => $selectedOutlet,
            'namaOutlet' => $namaOutlet,
            'outlets' => $this->outlets,
            'isAdmin' => $isAdmin,
            'isSpv' => $isSpv,
            'isAdminOrSpv' => $isAdminOrSpv,
            'stockItems' => $stockItems,
            'stockOutletRows' => $stockOutletRows,
            'totalStok' => $totalStok,
            'totalSemuaOutlet' => $totalSemuaOutlet,
        ]);
    }

    public function store(Request $request)
    {
        $role = $this->getUserRole();

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        if (!$isAdminOrSpv && !str_contains($role, 'outlet')) {
            abort(403);
        }

        $requestedOutlet = strtolower(
            trim(
                (string) $request->input('nama_outlet', $role)
            )
        );

        if (!$isAdminOrSpv) {
            $requestedOutlet = $role;
        }

        if (!isset($this->outletMapping[$requestedOutlet])) {
            return back()
                ->withInput()
                ->with('error', 'Outlet tidak valid.');
        }

        $namaOutlet = $this->outletMapping[$requestedOutlet];

        try {
            DB::transaction(function () use (
                $request,
                $requestedOutlet,
                $namaOutlet
            ) {
                $legacyData = $this->prepareLegacyData($request);

                $stockItemData = $this->prepareStockItemData(
                    $request
                );

                if (!$legacyData['has_input'] && !$stockItemData['has_input']) {
                    throw new \RuntimeException(
                        'Masukkan minimal satu jumlah stok.'
                    );
                }

                if ($legacyData['has_input']) {
                    $this->storeLegacyBahan(
                        $legacyData['data'],
                        $namaOutlet
                    );
                }

                if ($stockItemData['has_input']) {
                    $this->syncDirectStockItems(
                        $stockItemData['data'],
                        $requestedOutlet
                    );
                }

                if ($legacyData['has_input']) {
                    $this->syncStockItems(
                        $requestedOutlet,
                        $legacyData['data']
                    );
                }
            });

            return redirect()
                ->route(
                    'bahans.index',
                    ['outlet' => $requestedOutlet]
                )
                ->with(
                    'success',
                    'Stok bahan berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal menyimpan stok: ' . $e->getMessage()
                );
        }
    }

    private function prepareLegacyData(Request $request): array
    {
        $data = [];
        $hasInput = false;

        foreach ($this->rows as $field) {
            $value = $request->input($field);

            if ($value === null || trim((string) $value) === '') {
                $data[$field] = 0;
                continue;
            }

            $value = str_replace(
                ',',
                '.',
                trim((string) $value)
            );

            if (!is_numeric($value)) {
                throw new \RuntimeException(
                    'Nilai ' .
                    ($this->historyLabels[$field] ?? $field) .
                    ' harus berupa angka.'
                );
            }

            $value = (float) $value;

            if ($value < 0) {
                throw new \RuntimeException(
                    'Nilai ' .
                    ($this->historyLabels[$field] ?? $field) .
                    ' tidak boleh negatif.'
                );
            }

            if ($value > 0) {
                $hasInput = true;
            }

            $data[$field] = $value;
        }

        return [
            'data' => $data,
            'has_input' => $hasInput,
        ];
    }

    private function prepareStockItemData(Request $request): array
    {
        $data = [];
        $hasInput = false;

        $stockItems = DB::table('stock_items')
            ->where('aktif', true)
            ->orderBy('id')
            ->get();

        foreach ($stockItems as $stockItem) {
            $field = 'stock_item_' . $stockItem->id;

            if (!$request->has($field)) {
                continue;
            }

            $value = $request->input($field);

            if ($value === null || trim((string) $value) === '') {
                continue;
            }

            $value = str_replace(
                ',',
                '.',
                trim((string) $value)
            );

            if (!is_numeric($value)) {
                throw new \RuntimeException(
                    'Nilai ' .
                    $stockItem->nama .
                    ' harus berupa angka.'
                );
            }

            $value = (float) $value;

            if ($value < 0) {
                throw new \RuntimeException(
                    'Nilai ' .
                    $stockItem->nama .
                    ' tidak boleh negatif.'
                );
            }

            if ($value > 0) {
                $hasInput = true;
            }

            $data[$stockItem->id] = $value;
        }

        return [
            'data' => $data,
            'has_input' => $hasInput,
        ];
    }

    private function storeLegacyBahan(
        array $data,
        string $namaOutlet
    ): void {
        $latest = Bahan::whereRaw(
            'LOWER(TRIM(nama_outlet)) = ?',
            [strtolower($namaOutlet)]
        )
            ->latest('id')
            ->lockForUpdate()
            ->first();

        $newValues = [];

        foreach ($this->rows as $field) {
            $oldValue = $latest
                ? (float) ($latest->{$field} ?? 0)
                : 0;

            $inputValue = (float) ($data[$field] ?? 0);

            $newValues[$field] = $oldValue + $inputValue;
        }

        $newValues['nama_outlet'] = $namaOutlet;

        Bahan::create($newValues);
    }

    private function syncDirectStockItems(
        array $data,
        string $requestedOutlet
    ): void {
        foreach ($data as $stockItemId => $jumlah) {
            if ($jumlah <= 0) {
                continue;
            }

            $stockItem = DB::table('stock_items')
                ->where('id', $stockItemId)
                ->where('aktif', true)
                ->lockForUpdate()
                ->first();

            if (!$stockItem) {
                throw new \RuntimeException(
                    'Stock item ID ' .
                    $stockItemId .
                    ' tidak ditemukan atau tidak aktif.'
                );
            }

            $stockOutlet = DB::table('stock_item_outlets')
                ->where('stock_item_id', $stockItemId)
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$requestedOutlet]
                )
                ->lockForUpdate()
                ->first();

            if (!$stockOutlet) {
                DB::table('stock_item_outlets')->insert([
                    'stock_item_id' => $stockItemId,
                    'outlet' => $requestedOutlet,
                    'stok' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                continue;
            }

            DB::table('stock_item_outlets')
                ->where('id', $stockOutlet->id)
                ->update([
                    'stok' => (float) $stockOutlet->stok + $jumlah,
                    'updated_at' => now(),
                ]);
        }
    }

    private function syncStockItems(
        string $requestedOutlet,
        array $data
    ): void {
        foreach ($this->stockItemMapping as $field => $stockItemId) {
            $jumlah = (float) ($data[$field] ?? 0);

            if ($jumlah <= 0) {
                continue;
            }

            $stockItem = DB::table('stock_items')
                ->where('id', $stockItemId)
                ->where('aktif', true)
                ->lockForUpdate()
                ->first();

            if (!$stockItem) {
                throw new \RuntimeException(
                    'Stock item "' .
                    ($this->stockItemNames[$stockItemId]
                        ?? ('ID ' . $stockItemId)) .
                    '" tidak ditemukan atau tidak aktif.'
                );
            }

            $stockOutlet = DB::table('stock_item_outlets')
                ->where('stock_item_id', $stockItemId)
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$requestedOutlet]
                )
                ->lockForUpdate()
                ->first();

            if (!$stockOutlet) {
                DB::table('stock_item_outlets')->insert([
                    'stock_item_id' => $stockItemId,
                    'outlet' => $requestedOutlet,
                    'stok' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                continue;
            }

            DB::table('stock_item_outlets')
                ->where('id', $stockOutlet->id)
                ->update([
                    'stok' => (float) $stockOutlet->stok + $jumlah,
                    'updated_at' => now(),
                ]);
        }
    }

    public function history(Request $request)
    {
        $role = $this->getUserRole();

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        if (!$isAdminOrSpv && !str_contains($role, 'outlet')) {
            abort(403);
        }

        $selectedOutlet = $this->resolveOutlet(
            $request,
            $role,
            $isAdminOrSpv
        );

        $namaOutlet = $this->outletMapping[$selectedOutlet];

        $bahanHistory = Bahan::whereRaw(
            'LOWER(TRIM(nama_outlet)) = ?',
            [strtolower($namaOutlet)]
        )
            ->orderBy('id', 'asc')
            ->get();

        $penambahanHistory = [];
        $previous = null;

        foreach ($bahanHistory as $current) {
            $items = [];

            foreach ($this->historyLabels as $field => $label) {
                $currentValue = (float) (
                    $current->{$field} ?? 0
                );

                if ($previous === null) {
                    $change = $currentValue;
                } else {
                    $previousValue = (float) (
                        $previous->{$field} ?? 0
                    );

                    $change = $currentValue - $previousValue;
                }

                if ($change <= 0) {
                    continue;
                }

                $items[] = [
                    'nama' => $label,
                    'item' => $label,
                    'field' => $field,
                    'change' => $change,
                    'total' => $currentValue,
                ];
            }

            if (!empty($items)) {
                $penambahanHistory[] = [
                    'id' => $current->id,
                    'type' => 'input',
                    'type_label' => 'Penambahan',
                    'nama_outlet' => $namaOutlet,
                    'created_at' => $current->created_at,
                    'updated_at' => $current->updated_at,
                    'items' => $items,
                ];
            }

            $previous = $current;
        }

        $deductions = DB::table('stock_deductions as sd')
            ->join(
                'stock_items as si',
                'si.id',
                '=',
                'sd.stock_item_id'
            )
            ->leftJoin(
                'transactions as t',
                't.id',
                '=',
                'sd.transaction_id'
            )
            ->whereRaw(
                'LOWER(TRIM(t.nama_outlet)) = ?',
                [strtolower($namaOutlet)]
            )
            ->select([
                'sd.id',
                'sd.transaction_id',
                'sd.stock_item_id',
                'si.nama as stock_item',
                'sd.jumlah',
                'sd.created_at',
                't.order_number',
                't.nama_outlet',
            ])
            ->orderBy('sd.created_at', 'desc')
            ->get();

        $penggunaanHistory = [];

        foreach ($deductions as $deduction) {
            $transactionId = (int) $deduction->transaction_id;

            if (!isset($penggunaanHistory[$transactionId])) {
                $penggunaanHistory[$transactionId] = [
                    'id' => 'transaction-' . $transactionId,
                    'transaction_id' => $transactionId,
                    'type' => 'usage',
                    'type_label' => 'Penggunaan',
                    'nama_outlet' => $namaOutlet,
                    'order_number' => $deduction->order_number,
                    'created_at' => $deduction->created_at,
                    'items' => [],
                ];
            }

            $penggunaanHistory[$transactionId]['items'][] = [
                'nama' => $deduction->stock_item,
                'item' => $deduction->stock_item,
                'field' => null,
                'change' => -((float) $deduction->jumlah),
                'total' => null,
            ];
        }

        $history = array_merge(
            $penambahanHistory,
            array_values($penggunaanHistory)
        );

        usort($history, function ($a, $b) {
            $timeA = strtotime(
                (string) $a['created_at']
            );

            $timeB = strtotime(
                (string) $b['created_at']
            );

            return $timeB <=> $timeA;
        });

        $stockItems = DB::table('stock_items')
            ->where('aktif', true)
            ->orderBy('id')
            ->get();

        $stockOutletRows = DB::table('stock_item_outlets')
            ->whereRaw(
                'LOWER(TRIM(outlet)) = ?',
                [$selectedOutlet]
            )
            ->get()
            ->keyBy('stock_item_id');

        $totalStok = [];

        foreach ($stockItems as $stockItem) {
            $totalStok[$stockItem->id] = isset(
                $stockOutletRows[$stockItem->id]
            )
                ? (float) $stockOutletRows[$stockItem->id]->stok
                : 0;
        }

        return view('bahans.history', [
            'history' => $history,
            'selectedOutlet' => $selectedOutlet,
            'namaOutlet' => $namaOutlet,
            'outlets' => $this->outlets,
            'isAdmin' => $isAdmin,
            'isSpv' => $isSpv,
            'isAdminOrSpv' => $isAdminOrSpv,
            'stockItems' => $stockItems,
            'stockOutletRows' => $stockOutletRows,
            'totalStok' => $totalStok,
        ]);
    }

    private function getUserRole(): string
    {
        return strtolower(
            trim(
                (string) (auth()->user()->role ?? '')
            )
        );
    }

    private function resolveOutlet(
        Request $request,
        string $role,
        bool $isAdminOrSpv
    ): string {
        if ($isAdminOrSpv) {
            $selectedOutlet = strtolower(
                trim(
                    (string) $request->input(
                        'outlet',
                        'outlet 1'
                    )
                )
            );

            if (!isset($this->outletMapping[$selectedOutlet])) {
                $selectedOutlet = 'outlet 1';
            }

            return $selectedOutlet;
        }

        if (!str_contains($role, 'outlet')) {
            abort(403);
        }

        if (!isset($this->outletMapping[$role])) {
            abort(403);
        }

        return $role;
    }

    private function formatNumber(float $number): string
    {
        if (floor($number) == $number) {
            return number_format(
                $number,
                0,
                ',',
                '.'
            );
        }

        return number_format(
            $number,
            2,
            ',',
            '.'
        );
    }
}