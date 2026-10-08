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
        'bubuk_cabe' => 'Kantong Sambal',
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

    /*
    |--------------------------------------------------------------------------
    | Legacy Bahan -> Stock Item
    |--------------------------------------------------------------------------
    */

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
        8 => 'Saos Cabe',
        9 => 'Saus Tomat Sachet',
        10 => 'Tepung',
        11 => 'Kantong Sambal',
        12 => 'Ayam',
    ];

    /*
    |--------------------------------------------------------------------------
    | Outlet
    |--------------------------------------------------------------------------
    */

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
        'outlet 10' => 'Outlet 10',
    ];

    private array $outletDisplayNames = [
    'Outlet 1' => 'Pusat',
    'Outlet 2' => 'Indomaret',
    'Outlet 3' => 'Bunderan',
    'Outlet 4' => 'Mersi',
    'Outlet 5' => 'Arca',
    'Outlet 6' => 'Larangan',
    'Outlet 7' => 'Unsoed',
        'Outlet 8' => 'Event 1',
        'Outlet 9' => 'Event 2',
        'Outlet 10' => 'Event',
    ];

    private array $outlets = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
        'outlet 8' => 'Outlet 8',
        'outlet 9' => 'Outlet 9',
        'outlet 10' => 'Outlet 10',
    ];

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $role = $this->getUserRole();

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        $selectedOutlet = $this->resolveOutlet(
            $request,
            $role,
            $isAdminOrSpv
        );

        $namaOutlet = $selectedOutlet === 'all' ? 'Semua Outlet' : $this->outletMapping[$selectedOutlet];

        $stockItems = DB::table('stock_items')
            ->where('aktif', true)
            ->whereNotIn('nama', [
                'Tepung',
                'Dus Chicken',
            ])
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil stok outlet
        |--------------------------------------------------------------------------
        */

        $stockOutletRows = DB::table('stock_item_outlets')
            ->when($selectedOutlet !== 'all', function ($query) use ($selectedOutlet) {
                $query->whereRaw('LOWER(TRIM(outlet)) = ?', [$selectedOutlet]);
            })
            ->orderBy('id')
            ->get()
            ->groupBy('stock_item_id');

        /*
        |--------------------------------------------------------------------------
        | Jika ada duplicate row, jumlahkan hanya untuk tampilan.
        |--------------------------------------------------------------------------
        */

        $totalStok = [];

        foreach ($stockItems as $stockItem) {
            $totalStok[$stockItem->id] = 0;

            if (isset($stockOutletRows[$stockItem->id])) {
                foreach ($stockOutletRows[$stockItem->id] as $row) {
                    $totalStok[$stockItem->id] +=
                        (float) $row->stok;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Total semua outlet
        |--------------------------------------------------------------------------
        */

        $allOutletStocks = DB::table('stock_item_outlets')
            ->get()
            ->groupBy('stock_item_id');

        $totalSemuaOutlet = [];

        foreach ($stockItems as $stockItem) {
            $totalSemuaOutlet[$stockItem->id] = 0;

            if (isset($allOutletStocks[$stockItem->id])) {
                foreach (
                    $allOutletStocks[$stockItem->id]
                    as $row
                ) {
                    $totalSemuaOutlet[$stockItem->id] +=
                        (float) $row->stok;
                }
            }
        }

        if ($selectedOutlet === 'all') {
            $legacyRows = Bahan::query()->get();
            $legacyTotal = [];
            foreach ($this->rows as $field) {
                $legacyTotal[$field] = (float) $legacyRows->sum(fn ($row) => (float) ($row->{$field} ?? 0));
            }
            $totalStok = (object) $legacyTotal;
        }

        /*
        |--------------------------------------------------------------------------
        | Legacy Bahan
        |--------------------------------------------------------------------------
        */

        $bahan = Bahan::whereRaw(
            'LOWER(TRIM(nama_outlet)) = ?',
            [strtolower($namaOutlet)]
        )
            ->orderByDesc('id')
            ->first();

        if (!$bahan) {
            $bahan = new Bahan();
            $bahan->nama_outlet = $namaOutlet;
        }

        /*
        |--------------------------------------------------------------------------
        | Untuk compatibility dengan Blade lama,
        | stockOutletRows dikembalikan sebagai row pertama.
        |--------------------------------------------------------------------------
        */

        $stockOutletRowsForView = collect();

        foreach ($stockOutletRows as $stockItemId => $rows) {
            $stockOutletRowsForView[$stockItemId] =
                $rows->first();
        }

        return view('bahans.index', [
            'bahan' => $bahan,
            'selectedOutlet' => $selectedOutlet,
            'namaOutlet' => $namaOutlet,
            'outlets' => $this->outlets,
            'isAdmin' => $isAdmin,
            'isSpv' => $isSpv,
            'isAdminOrSpv' => $isAdminOrSpv,
            'stockItems' => $stockItems,
            'stockOutletRows' => $stockOutletRowsForView,
            'totalStok' => $totalStok,
            'totalSemuaOutlet' => $totalSemuaOutlet,
        ]);
    }

    public function create()
    {
        return redirect()->route('bahans.index');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $role = $this->getUserRole();

        // Event dapat tersimpan sebagai nama tampilan pada akun lama.
        // Normalisasi kembali ke role canonical agar history tidak menjadi
        // "Semua Outlet".
        $roleAliases = [
            'event 1' => 'outlet 8',
            'event 2' => 'outlet 9',
            'event' => 'outlet 10',
        ];

        $role = $roleAliases[$role] ?? $role;

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        if (
            !$isAdminOrSpv &&
            !str_starts_with($role, 'outlet ')
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Tentukan outlet
        |--------------------------------------------------------------------------
        */

        if ($isAdminOrSpv) {
            $requestedOutlet = strtolower(
                trim(
                    (string) $request->input(
                        'nama_outlet',
                        $request->input('outlet', '')
                    )
                )
            );
        } else {
            $requestedOutlet = $role;
        }

        if (!isset($this->outletMapping[$requestedOutlet])) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Outlet tidak valid.'
                );
        }

        $namaOutlet =
            $this->outletMapping[$requestedOutlet];

        try {
            DB::transaction(function () use (
                $request,
                $requestedOutlet,
                $namaOutlet
            ) {
                /*
                |--------------------------------------------------------------------------
                | Legacy data
                |--------------------------------------------------------------------------
                */

                $legacyData =
                    $this->prepareLegacyData($request);

                /*
                |--------------------------------------------------------------------------
                | Stock Item langsung
                |--------------------------------------------------------------------------
                */

                $stockItemData =
                    $this->prepareStockItemData($request);

                if (
                    !$legacyData['has_input'] &&
                    !$stockItemData['has_input']
                ) {
                    throw new \RuntimeException(
                        'Masukkan minimal satu jumlah stok.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PENTING:
                |
                | stock_item_xxx yang mempunyai mapping ke
                | legacy Bahan digabung terlebih dahulu.
                |
                | Contoh:
                |
                | stock_item_12 = 1
                |
                | -> ayam +1
                |
                | Kemudian syncStockItems hanya berjalan SEKALI.
                |
                | Ini mencegah double increment.
                |--------------------------------------------------------------------------
                */

                $this->mergeStockItemIntoLegacyData(
                    $legacyData,
                    $stockItemData
                );

                /*
                |--------------------------------------------------------------------------
                | Simpan legacy Bahan
                |--------------------------------------------------------------------------
                */

                if ($legacyData['has_input']) {
                    $this->storeLegacyBahan(
                        $legacyData['data'],
                        $namaOutlet
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Sync mapped stock item
                |--------------------------------------------------------------------------
                */

                if ($legacyData['has_input']) {
                    $this->syncStockItems(
                        $requestedOutlet,
                        $legacyData['data']
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Sync stock item yang TIDAK mempunyai
                | mapping legacy.
                |--------------------------------------------------------------------------
                */

                $this->syncUnmappedDirectStockItems(
                    $stockItemData['data'],
                    $requestedOutlet
                );
            });

            return redirect()
                ->route(
                    'bahans.index',
                    [
                        'outlet' =>
                            $requestedOutlet
                    ]
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
                    'Gagal menyimpan stok: ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE LEGACY
    |--------------------------------------------------------------------------
    */

    private function prepareLegacyData(
        Request $request
    ): array {
        $data = [];
        $hasInput = false;

        foreach ($this->rows as $field) {
            $value = $request->input($field);

            if (
                $value === null ||
                trim((string) $value) === ''
            ) {
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
                    (
                        $this->historyLabels[$field]
                        ?? $field
                    ) .
                    ' harus berupa angka.'
                );
            }

            $value = (float) $value;

            if ($value < 0) {
                throw new \RuntimeException(
                    'Nilai ' .
                    (
                        $this->historyLabels[$field]
                        ?? $field
                    ) .
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

    /*
    |--------------------------------------------------------------------------
    | PREPARE STOCK ITEM
    |--------------------------------------------------------------------------
    */

    private function prepareStockItemData(
        Request $request
    ): array {
        $data = [];
        $hasInput = false;

        $stockItems = DB::table('stock_items')
            ->where('aktif', true)
            ->orderBy('id')
            ->get();

        foreach ($stockItems as $stockItem) {

            /*
            |--------------------------------------------------------------------------
            | BUG FIX UTAMA
            |
            | SALAH:
            | stock_item\_12
            |
            | BENAR:
            | stock_item_12
            |--------------------------------------------------------------------------
            */

            $field =
                'stock_item_' .
                $stockItem->id;

            if (!$request->has($field)) {
                continue;
            }

            $value = $request->input($field);

            if (
                $value === null ||
                trim((string) $value) === ''
            ) {
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

    /*
    |--------------------------------------------------------------------------
    | MERGE STOCK ITEM KE LEGACY
    |--------------------------------------------------------------------------
    */

    private function mergeStockItemIntoLegacyData(
        array &$legacyData,
        array $stockItemData
    ): void {
        foreach (
            $this->stockItemMapping
            as $field => $stockItemId
        ) {
            $jumlah =
                (float) (
                    $stockItemData['data'][$stockItemId]
                    ?? 0
                );

            if ($jumlah <= 0) {
                continue;
            }

            $legacyData['data'][$field] =
                (float) (
                    $legacyData['data'][$field]
                    ?? 0
                ) + $jumlah;

            $legacyData['has_input'] = true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE LEGACY BAHAN
    |--------------------------------------------------------------------------
    */

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
                ? (float) (
                    $latest->{$field}
                    ?? 0
                )
                : 0;

            $inputValue =
                (float) (
                    $data[$field] ?? 0
                );

            $newValues[$field] =
                $oldValue + $inputValue;
        }

        $newValues['nama_outlet'] =
            $namaOutlet;

        Bahan::create($newValues);
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC MAPPED STOCK ITEMS
    |--------------------------------------------------------------------------
    */

    private function syncStockItems(
        string $requestedOutlet,
        array $data
    ): void {
        foreach (
            $this->stockItemMapping
            as $field => $stockItemId
        ) {
            $jumlah =
                (float) (
                    $data[$field] ?? 0
                );

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
                    (
                        $this->stockItemNames[
                            $stockItemId
                        ]
                        ?? (
                            'ID ' .
                            $stockItemId
                        )
                    ) .
                    '" tidak ditemukan atau tidak aktif.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Ambil SEMUA row outlet.
            |
            | Ini penting untuk mencegah duplicate row
            | membuat stok salah.
            |--------------------------------------------------------------------------
            */

            $rows = DB::table(
                'stock_item_outlets'
            )
                ->where(
                    'stock_item_id',
                    $stockItemId
                )
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$requestedOutlet]
                )
                ->lockForUpdate()
                ->get();

            $namaOutlet =
                $this->outletMapping[
                    $requestedOutlet
                ];

            if ($rows->isEmpty()) {
                DB::table(
                    'stock_item_outlets'
                )->insert([
                    'stock_item_id' => $stockItemId,
                    'outlet' => $namaOutlet,
                    'stok' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('stock_adjustments')->insert([
                    'stock_item_id' => $stockItemId,
                    'outlet' => $namaOutlet,
                    'jumlah' => $jumlah,
                    'jenis' => 'Penambahan',
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Gunakan row pertama sebagai canonical.
            | Duplicate row digabung ke row pertama.
            |--------------------------------------------------------------------------
            */

            $canonical = $rows->first();

            $currentStock = 0;

            foreach ($rows as $row) {
                $currentStock +=
                    (float) $row->stok;
            }

            $newStock =
                $currentStock + $jumlah;

            DB::table(
                'stock_item_outlets'
            )
                ->where(
                    'id',
                    $canonical->id
                )
                ->update([
                    'outlet' =>
                        $namaOutlet,
                    'stok' =>
                        $newStock,
                    'updated_at' =>
                        now(),
                ]);

            DB::table('stock_adjustments')->insert([
                'stock_item_id' => $stockItemId,
                'outlet' => $namaOutlet,
                'jumlah' => $jumlah,
                'jenis' => 'Penambahan',
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Hapus duplicate rows setelah digabung.
            |--------------------------------------------------------------------------
            */

            if ($rows->count() > 1) {
                $duplicateIds =
                    $rows
                        ->skip(1)
                        ->pluck('id')
                        ->values()
                        ->all();

                DB::table(
                    'stock_item_outlets'
                )
                    ->whereIn(
                        'id',
                        $duplicateIds
                    )
                    ->delete();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC DIRECT STOCK ITEM
    |
    | Hanya untuk stock item yang tidak mempunyai
    | mapping legacy.
    |--------------------------------------------------------------------------
    */

    private function syncUnmappedDirectStockItems(
        array $data,
        string $requestedOutlet
    ): void {
        foreach ($data as $stockItemId => $jumlah) {

            $stockItemId =
                (int) $stockItemId;

            $jumlah =
                (float) $jumlah;

            if ($jumlah <= 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Jika sudah mempunyai mapping legacy,
            | jangan diproses lagi.
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $stockItemId,
                    array_values(
                        $this->stockItemMapping
                    ),
                    true
                )
            ) {
                continue;
            }

            $stockItem = DB::table('stock_items')
                ->where(
                    'id',
                    $stockItemId
                )
                ->where(
                    'aktif',
                    true
                )
                ->lockForUpdate()
                ->first();

            if (!$stockItem) {
                throw new \RuntimeException(
                    'Stock item ID ' .
                    $stockItemId .
                    ' tidak ditemukan atau tidak aktif.'
                );
            }

            $rows = DB::table(
                'stock_item_outlets'
            )
                ->where(
                    'stock_item_id',
                    $stockItemId
                )
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$requestedOutlet]
                )
                ->lockForUpdate()
                ->get();

            $namaOutlet =
                $this->outletMapping[
                    $requestedOutlet
                ];

            if ($rows->isEmpty()) {
                DB::table(
                    'stock_item_outlets'
                )->insert([
                    'stock_item_id' =>
                        $stockItemId,
                    'outlet' =>
                        $namaOutlet,
                    'stok' =>
                        $jumlah,
                    'created_at' =>
                        now(),
                    'updated_at' =>
                        now(),
                ]);

                continue;
            }

            $canonical = $rows->first();

            $currentStock = 0;

            foreach ($rows as $row) {
                $currentStock +=
                    (float) $row->stok;
            }

            DB::table(
                'stock_item_outlets'
            )
                ->where(
                    'id',
                    $canonical->id
                )
                ->update([
                    'outlet' =>
                        $namaOutlet,
                    'stok' =>
                        $currentStock + $jumlah,
                    'updated_at' =>
                        now(),
                ]);

            if ($rows->count() > 1) {
                $duplicateIds =
                    $rows->skip(1)->pluck('id')->values()->all();

                DB::table('stock_item_outlets')
                    ->whereIn('id', $duplicateIds)
                    ->delete();
            }

            DB::table('stock_adjustments')->insert([
                'stock_item_id' => $stockItemId,
                'outlet' => $namaOutlet,
                'jumlah' => $jumlah,
                'jenis' => 'Penambahan',
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(Request $request)
    {
        $role = $this->getUserRole();

        // Normalisasi nama Event lama ke role outlet canonical.
        $roleAliases = [
            'event 1' => 'outlet 8',
            'event 2' => 'outlet 9',
            'event' => 'outlet 10',
        ];

        $role = $roleAliases[$role] ?? $role;

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        if (
            !$isAdminOrSpv &&
            !str_starts_with($role, 'outlet ')
        ) {
            abort(403);
        }

        // User outlet WAJIB dikunci ke outlet miliknya.
        // Request ?outlet=... tidak boleh mengubah filter outlet.
        if (!$isAdminOrSpv) {
            $selectedOutlet = $role;
        } else {
            $selectedOutlet = $this->resolveOutlet(
                $request,
                $role,
                $isAdminOrSpv
            );
        }

        $namaOutlet = $selectedOutlet === 'all' ? null : $this->outletMapping[$selectedOutlet];
        $namaOutletDisplay = $selectedOutlet === 'all'
            ? 'Semua Outlet'
            : ($this->outletDisplayNames[$namaOutlet] ?? $namaOutlet);

        /*
        |--------------------------------------------------------------------------
        | HISTORY PENAMBAHAN LEGACY
        |
        | Tetap tampilkan history dari tabel Bahan untuk data lama.
        | Jika sudah ada stock_adjustments pada timestamp + outlet yang sama,
        | legacy record tidak ditampilkan agar tidak terjadi duplikasi.
        |--------------------------------------------------------------------------
        */

        $bahanHistory = Bahan::query()
            ->when(
                $namaOutlet,
                fn ($q) => $q->whereRaw(
                    'LOWER(TRIM(nama_outlet)) = ?',
                    [strtolower($namaOutlet)]
                )
            )
            ->orderBy('id', 'asc')
            ->get();

        $penambahanHistory = [];
        $previousByOutlet = [];

        foreach ($bahanHistory as $current) {
            $outletKey = strtolower(trim((string) $current->nama_outlet));
            $items = [];

            foreach ($this->historyLabels as $field => $label) {
                $currentValue = (float) ($current->{$field} ?? 0);

                if (!isset($previousByOutlet[$outletKey])) {
                    $change = $currentValue;
                } else {
                    $previousValue = (float) (
                        $previousByOutlet[$outletKey]->{$field} ?? 0
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
                    'id' => 'legacy-' . $current->id,
                    'type' => 'input',
                    'type_label' => 'Penambahan',
                    'nama_outlet' => $this->displayOutletName($current->nama_outlet),
                    'outlet_key' => $outletKey,
                    'created_at' => $current->created_at,
                    'updated_at' => $current->updated_at,
                    'items' => $items,
                ];
            }

            $previousByOutlet[$outletKey] = $current;
        }

        /*
        |--------------------------------------------------------------------------
        | HISTORY PENGGUNAAN / KASIR
        |--------------------------------------------------------------------------
        */

        $deductions = DB::table(
            'stock_deductions as sd'
        )
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
            ->when($namaOutlet, fn ($q) => $q->whereRaw('LOWER(TRIM(t.nama_outlet)) = ?', [strtolower($namaOutlet)]))
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
            ->orderBy(
                'sd.created_at',
                'desc'
            )
            ->get();

        $penggunaanHistory = [];

        foreach ($deductions as $deduction) {
            $transactionId =
                (int) $deduction->transaction_id;

            if (
                !isset(
                    $penggunaanHistory[
                        $transactionId
                    ]
                )
            ) {
                $penggunaanHistory[
                    $transactionId
                ] = [
                    'id' =>
                        'transaction-' .
                        $transactionId,
                    'transaction_id' =>
                        $transactionId,
                    'type' =>
                        'usage',
                    'type_label' =>
                        'Penggunaan POS',
                    'nama_outlet' =>
                        $this->displayOutletName($deduction->nama_outlet),
                    'order_number' =>
                        $deduction->order_number,
                    'created_at' =>
                        $deduction->created_at,
                    'items' =>
                        [],
                ];
            }

            $penggunaanHistory[
                $transactionId
            ]['items'][] = [
                'nama' =>
                    $deduction->stock_item,
                'item' =>
                    $deduction->stock_item,
                'field' =>
                    null,
                'change' =>
                    -(
                        (float)
                        $deduction->jumlah
                    ),
                'total' =>
                    null,
            ];
        }

        $adjustmentRows = DB::table('stock_adjustments as sa')
            ->join('stock_items as si', 'si.id', '=', 'sa.stock_item_id')
            ->leftJoin('users as u', 'u.id', '=', 'sa.user_id')
            ->when($namaOutlet, fn ($q) => $q->whereRaw('LOWER(TRIM(sa.outlet)) = ?', [strtolower($namaOutlet)]))
            ->select('sa.id', 'sa.outlet', 'sa.jumlah', 'sa.jenis', 'sa.user_id', 'sa.created_at', 'si.nama as stock_item', 'u.name as nama_user')
            ->orderByDesc('sa.created_at')
            ->get();

        // Satu kali input bahan menghasilkan satu card history.
        // NOW() PostgreSQL menggunakan timestamp transaksi yang sama untuk
        // seluruh item yang disimpan dalam satu DB::transaction().
        $adjustments = $adjustmentRows
            ->groupBy(function ($row) {
                return strtolower(trim((string) $row->outlet))
                    . '|' . (string) $row->user_id
                    . '|' . (string) $row->jenis
                    . '|' . (string) $row->created_at;
            })
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'id' => 'adjustment-' . $first->id,
                    'type' => 'input',
                    'type_label' => $first->jenis,
                    'nama_outlet' => $this->displayOutletName($first->outlet),
                    'created_at' => $first->created_at,
                    'updated_at' => $first->created_at,
                    'items' => $rows->map(fn ($row) => [
                        'nama' => $row->stock_item,
                        'item' => $row->stock_item,
                        'field' => null,
                        'change' => (float) $row->jumlah,
                        'total' => null,
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Hapus legacy card yang sudah mempunyai stock_adjustments.
        | Sumber baru menjadi stock_adjustments, sedangkan legacy hanya
        | dipakai untuk history lama yang belum tercatat di sana.
        |--------------------------------------------------------------------------
        */

        $adjustmentKeys = [];

        foreach ($adjustments as $adjustment) {
            $adjustmentKeys[
                strtolower(trim((string) $adjustment['nama_outlet']))
                . '|' . (string) $adjustment['created_at']
            ] = true;
        }

        $penambahanHistory = array_values(array_filter(
            $penambahanHistory,
            function ($record) use ($adjustmentKeys) {
                $key =
                    strtolower(trim((string) $record['nama_outlet']))
                    . '|' . (string) $record['created_at'];

                return !isset($adjustmentKeys[$key]);
            }
        ));

        foreach ($penambahanHistory as &$record) {
            unset($record['outlet_key']);
        }
        unset($record);

        $history = array_merge(
            $penambahanHistory,
            array_values($penggunaanHistory),
            $adjustments
        );

        usort(
            $history,
            function ($a, $b) {
                $timeA = strtotime(
                    (string)
                    $a['created_at']
                );

                $timeB = strtotime(
                    (string)
                    $b['created_at']
                );

                return $timeB <=> $timeA;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | STOCK ITEMS
        |--------------------------------------------------------------------------
        */

        $stockItems = DB::table(
            'stock_items'
        )
            ->where(
                'aktif',
                true
            )
            ->orderBy('id')
            ->get();

        $stockOutletRows = DB::table('stock_item_outlets')
            ->when($selectedOutlet !== 'all', fn ($q) => $q->whereRaw('LOWER(TRIM(outlet)) = ?', [$selectedOutlet]))
            ->orderBy('id')
            ->get()
            ->groupBy('stock_item_id');

        $totalStok = [];

        foreach ($stockItems as $stockItem) {
            $totalStok[
                $stockItem->id
            ] = 0;

            if (
                isset(
                    $stockOutletRows[
                        $stockItem->id
                    ]
                )
            ) {
                foreach (
                    $stockOutletRows[
                        $stockItem->id
                    ] as $row
                ) {
                    $totalStok[
                        $stockItem->id
                    ] +=
                        (float)
                        $row->stok;
                }
            }
        }

        $stockOutletRowsForView =
            collect();

        foreach (
            $stockOutletRows
            as $stockItemId => $rows
        ) {
            $stockOutletRowsForView[
                $stockItemId
            ] = $rows->first();
        }

        /*
        |--------------------------------------------------------------------------
        | SISA STOK REAL-TIME
        |
        | Nilai Sisa Stok pada history harus selalu mengambil
        | kondisi terakhir dari stock_item_outlets, bukan
        | snapshot stok saat history dibuat.
        |--------------------------------------------------------------------------
        */

        $currentStockByName = [];

        foreach ($stockItems as $stockItem) {
            $currentStockByName[
                strtolower(trim($stockItem->nama))
            ] = (float) (
                $totalStok[$stockItem->id] ?? 0
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Alias nama lama -> nama stok aktif
        |--------------------------------------------------------------------------
        */

        $stockNameAliases = [
            'saus sambal sachet' => 'saos cabe',
            'saus cabe' => 'saos cabe',
            'cabe' => 'kantong sambal',
        ];

        foreach ($history as &$record) {
            foreach ($record['items'] as &$item) {
                $historyName = strtolower(
                    trim((string) ($item['nama'] ?? ''))
                );

                $lookupName =
                    $stockNameAliases[$historyName]
                    ?? $historyName;

                if (array_key_exists($lookupName, $currentStockByName)) {
                    $item['total'] =
                        $currentStockByName[$lookupName];
                }
            }
        }

        unset($record, $item);

        return view(
            'bahans.history',
            [
                'history' =>
                    $history,
                'selectedOutlet' =>
                    $selectedOutlet,
                'namaOutlet' =>
                    $namaOutlet,
                'outlets' =>
                    $this->outlets,
                'isAdmin' =>
                    $isAdmin,
                'isSpv' =>
                    $isSpv,
                'isAdminOrSpv' =>
                    $isAdminOrSpv,
                'stockItems' =>
                    $stockItems,
                'stockOutletRows' =>
                    $stockOutletRowsForView,
                'totalStok' =>
                    $totalStok,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER ROLE
    |--------------------------------------------------------------------------
    */

    private function getUserRole(): string
    {
        return strtolower(
            trim(
                (string) (
                    auth()->user()->role
                    ?? ''
                )
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE OUTLET
    |--------------------------------------------------------------------------
    */

    private function resolveOutlet(
        Request $request,
        string $role,
        bool $isAdminOrSpv
    ): string {
        // Admin/SPV dapat melihat semua atau memilih outlet.
        if ($isAdminOrSpv) {
            $selectedOutlet =
                strtolower(
                    trim(
                        (string)
                        $request->input('outlet', '')
                    )
                );

            if ($selectedOutlet === '') {
                return 'all';
            }

            if (
                !isset(
                    $this->outletMapping[
                        $selectedOutlet
                    ]
                )
            ) {
                $selectedOutlet =
                    'outlet 1';
            }

            return $selectedOutlet;
        }

        // Kasir/outlet selalu menggunakan role canonical:
        // outlet 1 ... outlet 10.
        // Nama Event/Event 1/Event 2 hanya untuk tampilan.
        if (!str_starts_with($role, 'outlet ')) {
            abort(403);
        }

        if (!isset($this->outletMapping[$role])) {
            abort(403);
        }

        return $role;
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT NUMBER
    |--------------------------------------------------------------------------
    */

    private function displayOutletName(?string $outlet): string
    {
        if (!$outlet) return '-';
        return $this->outletDisplayNames[$outlet] ?? $outlet;
    }

    private function formatNumber(
        float $number
    ): string {
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