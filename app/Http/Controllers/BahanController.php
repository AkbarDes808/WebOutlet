<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanController extends Controller
{
    /**
     * Semua kolom stok yang tersedia di tabel bahans.
     */
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

    /**
     * Label yang ditampilkan di History.
     */
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

    /**
     * Mapping field Bahan ke stock_items.
     *
     * Hanya item yang memang sudah menjadi stock item POS
     * yang disinkronkan ke stock_item_outlets.
     */
    private array $stockItemMapping = [
        'ayam' => 12,
        'tepung' => 10,
        'teh' => 5,
        'beras' => 7,
        'cup' => 6,
        'bubuk_cabe' => 11,
    ];

    /**
     * Nama stock item.
     */
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

    /**
     * Mapping role outlet ke nama outlet di tabel bahans.
     */
    private array $outletMapping = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
    ];

    /**
     * Daftar outlet.
     */
    private array $outlets = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
    ];

    /**
     * Halaman inventory / input bahan.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $role = strtolower(trim((string) ($user->role ?? '')));

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        /*
         * Admin dan SPV boleh memilih outlet.
         * User outlet hanya boleh melihat outlet miliknya.
         */
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
        } else {
            if (!str_contains($role, 'outlet')) {
                abort(403);
            }

            $selectedOutlet = $role;

            if (!isset($this->outletMapping[$selectedOutlet])) {
                abort(403);
            }
        }

        $namaOutlet = $this->outletMapping[$selectedOutlet];

        /*
         * Ambil snapshot Bahan terakhir.
         */
        $bahan = Bahan::whereRaw(
            'LOWER(TRIM(nama_outlet)) = ?',
            [strtolower($namaOutlet)]
        )
            ->latest('id')
            ->first();

        /*
         * Kalau belum ada snapshot, buat object kosong
         * agar Blade tetap aman.
         */
        if (!$bahan) {
            $bahan = new Bahan();

            $bahan->nama_outlet = $namaOutlet;

            foreach ($this->rows as $field) {
                $bahan->{$field} = 0;
            }
        }

        /*
         * Ambil stok POS aktual dari stock_item_outlets.
         */
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

        /*
         * Total stok POS per stock item.
         */
        $totalStok = [];

        foreach ($stockItems as $stockItem) {
            $totalStok[$stockItem->id] = isset(
                $stockOutletRows[$stockItem->id]
            )
                ? (float) $stockOutletRows[$stockItem->id]->stok
                : 0;
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
            'stockOutletRows' => $stockOutletRows,
            'totalStok' => $totalStok,
        ]);
    }

    /**
     * Simpan penambahan stok.
     *
     * Prinsip:
     *
     * INPUT FORM = PENAMBAHAN
     *
     * Jadi:
     *
     * stok lama + input baru
     *
     * Tidak pernah mengosongkan / mereset field
     * yang tidak diisi.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $role = strtolower(trim((string) ($user->role ?? '')));

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        if (!$isAdminOrSpv && !str_contains($role, 'outlet')) {
            abort(403);
        }

        /*
         * Tentukan outlet.
         */
        $requestedOutlet = strtolower(
            trim(
                (string) $request->input(
                    'nama_outlet',
                    $role
                )
            )
        );

        /*
         * User outlet tidak boleh menginput ke outlet lain.
         */
        if (!$isAdminOrSpv) {
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

        $namaOutlet = $this->outletMapping[$requestedOutlet];

        /*
         * Ambil semua input.
         */
        $data = [];

        $adaInput = false;

        foreach ($this->rows as $field) {
            $value = $request->input($field);

            /*
             * Field kosong = tidak ada penambahan.
             */
            if (
                $value === null ||
                trim((string) $value) === ''
            ) {
                $data[$field] = 0;
                continue;
            }

            /*
             * Normalisasi angka.
             */
            $value = str_replace(',', '.', trim((string) $value));

            if (!is_numeric($value)) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Nilai ' .
                        $this->historyLabels[$field] .
                        ' harus berupa angka.'
                    );
            }

            $value = (float) $value;

            /*
             * Penambahan tidak boleh negatif.
             */
            if ($value < 0) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Nilai ' .
                        $this->historyLabels[$field] .
                        ' tidak boleh negatif.'
                    );
            }

            if ($value > 0) {
                $adaInput = true;
            }

            $data[$field] = $value;
        }

        if (!$adaInput) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Masukkan minimal satu jumlah stok.'
                );
        }

        try {
            DB::transaction(function () use (
                $data,
                $namaOutlet,
                $requestedOutlet
            ) {
                /*
                 * Ambil snapshot terakhir.
                 */
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

                    /*
                     * INPUT = TAMBAHAN.
                     */
                    $newValues[$field] =
                        $oldValue + $inputValue;
                }

                $newValues['nama_outlet'] = $namaOutlet;

                /*
                 * Simpan snapshot baru.
                 *
                 * Model Bahan sudah memiliki semua field
                 * di $fillable.
                 */
                Bahan::create($newValues);

                /*
                 * Sinkronkan hanya field yang memang
                 * mempunyai stock item POS.
                 */
                $this->syncStockItems(
                    $requestedOutlet,
                    $data
                );
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
                    'Gagal menyimpan stok: ' .
                    $e->getMessage()
                );
        }
    }

    /**
     * Sinkronisasi Bahan ke stock_item_outlets.
     *
     * Hanya:
     *
     * ayam
     * tepung
     * teh
     * beras
     * cup
     * bubuk_cabe
     *
     * yang disinkronkan ke stok POS.
     */
    private function syncStockItems(
        string $requestedOutlet,
        array $data
    ): void {
        foreach ($this->stockItemMapping as $field => $stockItemId) {
            $jumlah = (float) ($data[$field] ?? 0);

            /*
             * Tidak ada input = jangan lakukan apa-apa.
             */
            if ($jumlah <= 0) {
                continue;
            }

            /*
             * Pastikan stock item aktif.
             */
            $stockItem = DB::table('stock_items')
                ->where('id', $stockItemId)
                ->where('aktif', true)
                ->first();

            if (!$stockItem) {
                throw new \RuntimeException(
                    'Stock item "' .
                    ($this->stockItemNames[$stockItemId]
                        ?? ('ID ' . $stockItemId)) .
                    '" tidak ditemukan atau tidak aktif.'
                );
            }

            /*
             * Cari stok outlet.
             */
            $stockOutlet = DB::table('stock_item_outlets')
                ->where(
                    'stock_item_id',
                    $stockItemId
                )
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$requestedOutlet]
                )
                ->lockForUpdate()
                ->first();

            /*
             * Kalau mapping belum ada, buat.
             */
            if (!$stockOutlet) {
                DB::table('stock_item_outlets')
                    ->insert([
                        'stock_item_id' => $stockItemId,
                        'outlet' => $requestedOutlet,
                        'stok' => $jumlah,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                continue;
            }

            /*
             * Kalau sudah ada, TAMBAHKAN.
             */
            $stokLama = (float) $stockOutlet->stok;

            $stokBaru = $stokLama + $jumlah;

            DB::table('stock_item_outlets')
                ->where('id', $stockOutlet->id)
                ->update([
                    'stok' => $stokBaru,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * History stok.
     *
     * Sumber:
     *
     * 1. bahans
     *    -> penambahan stok
     *
     * 2. stock_deductions
     *    -> pemakaian akibat transaksi
     *
     * Pengurangan dari transaksi TIDAK dianggap
     * sebagai penambahan.
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $role = strtolower(trim((string) ($user->role ?? '')));

        $isAdmin = $role === 'admin';
        $isSpv = $role === 'spv';
        $isAdminOrSpv = $isAdmin || $isSpv;

        if (!$isAdminOrSpv && !str_contains($role, 'outlet')) {
            abort(403);
        }

        /*
         * Tentukan outlet.
         */
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
        } else {
            $selectedOutlet = $role;

            if (!isset($this->outletMapping[$selectedOutlet])) {
                abort(403);
            }
        }

        $namaOutlet = $this->outletMapping[$selectedOutlet];

        /*
         * ==========================================================
         * PENAMBAHAN DARI BAHANS
         * ==========================================================
         *
         * Ambil semua snapshot Bahan secara ASC agar
         * perubahan bisa dibandingkan dengan snapshot sebelumnya.
         */
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

                /*
                 * Snapshot pertama.
                 *
                 * Kalau tidak ada snapshot sebelumnya,
                 * nilai positif dianggap stok awal/input.
                 */
                if ($previous === null) {
                    $change = $currentValue;
                } else {
                    $previousValue = (float) (
                        $previous->{$field} ?? 0
                    );

                    $change =
                        $currentValue -
                        $previousValue;
                }

                /*
                 * PENTING:
                 *
                 * Hanya perubahan POSITIF yang dianggap
                 * Penambahan.
                 *
                 * Kalau negatif, kemungkinan berasal dari
                 * pengurangan / perubahan snapshot dan tidak
                 * ditampilkan sebagai Penambahan.
                 */
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

            /*
             * Hanya buat history jika memang ada
             * penambahan.
             */
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

        /*
         * ==========================================================
         * PEMAKAIAN DARI TRANSAKSI
         * ==========================================================
         *
         * Ambil stock_deductions.
         *
         * Jumlah dibuat negatif ketika ditampilkan.
         */
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

        /*
         * Kelompokkan pemakaian berdasarkan transaksi.
         */
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

        /*
         * Gabungkan Penambahan + Penggunaan.
         */
        $history = array_merge(
            $penambahanHistory,
            array_values($penggunaanHistory)
        );

        /*
         * Urutkan terbaru di atas.
         */
        usort(
            $history,
            function ($a, $b) {
                $timeA = strtotime(
                    (string) $a['created_at']
                );

                $timeB = strtotime(
                    (string) $b['created_at']
                );

                return $timeB <=> $timeA;
            }
        );

        /*
         * Ambil stok POS aktual untuk informasi tambahan.
         */
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

    /**
     * Format angka untuk kebutuhan internal.
     */
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
