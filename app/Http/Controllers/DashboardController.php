<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * ============================================================
     * SEMUA FIELD STOK
     * HARUS SESUAI DENGAN KOLOM DATABASE
     * ============================================================
     */
    private array $fields = [
        // bahan lama
        'tepung_roti',
        'tepung_bumbu',
        'garam',
        'bubuk_cabe',
        'telur',
        'gula',
        'ayam',

        // bahan & kemasan baru
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
     * ============================================================
     * MAPPING ROLE USER -> NAMA OUTLET DI DATABASE
     * ============================================================
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

    public function index(Request $request)
    {
        // ========================================================
        // 1. DATA AWAL
        // ========================================================

        $activeTab = $request->input('tab', 'inventory');

        $user = Auth::user();

        $startDate = $request->input('start_date');

        $endDate = $request->input('end_date');

        $role = strtolower(trim($user->role ?? ''));


        // ========================================================
        // 2. TENTUKAN APAKAH USER ADALAH OUTLET
        // ========================================================

        $isOutletUser = str_starts_with($role, 'outlet ');


        // ========================================================
        // 3. FILTER OUTLET
        // ========================================================

        if ($isOutletUser) {

            /**
             * User outlet tidak boleh memilih outlet lain.
             *
             * Contoh:
             * role = outlet 1
             * maka otomatis:
             * nama_outlet = Pusat
             */

            $filterOutlet = $this->outletMapping[$role] ?? null;

            /**
             * Dropdown untuk user outlet hanya berisi
             * outlet miliknya sendiri.
             */
            $outlets = $filterOutlet
                ? collect([$filterOutlet])
                : collect();

        } else {

            /**
             * Admin / SPV dapat memilih outlet dari filter.
             */
            $filterOutlet = $request->input('outlet');

            /**
             * Ambil daftar outlet langsung dari database.
             */
            $outlets = Bahan::query()
                ->whereNotNull('nama_outlet')
                ->where('nama_outlet', '!=', '')
                ->select('nama_outlet')
                ->distinct()
                ->orderBy('nama_outlet')
                ->pluck('nama_outlet');
        }


        // ========================================================
        // 4. DATA UNTUK VIEW
        // ========================================================

        $viewData = [
            'outlets' => $outlets,

            'activeTab' => $activeTab,

            'user' => $user,

            'totalStok' => null,

            'history' => collect(),

            'selectedOutlet' => $filterOutlet,

            'isOutletUser' => $isOutletUser,
        ];


        // ========================================================
        // 5. TAB INVENTORY
        // ========================================================

        if ($activeTab === 'inventory') {

            /**
             * Query dasar inventory.
             */
            $query = Bahan::query();


            // ====================================================
            // FILTER OUTLET
            // ====================================================

            if ($filterOutlet) {

                $query->where(
                    'nama_outlet',
                    $filterOutlet
                );
            }


            // ====================================================
            // AMBIL ID DATA TERAKHIR SETIAP OUTLET
            // ====================================================

            $latestIdsQuery = $query
                ->select(
                    'nama_outlet',
                    DB::raw('MAX(id) as latest_id')
                )
                ->groupBy('nama_outlet');


            $latestIds = $latestIdsQuery
                ->pluck('latest_id');


            // ====================================================
            // AMBIL DATA STOK TERAKHIR
            // ====================================================

            $latestStocks = collect();

            if ($latestIds->isNotEmpty()) {

                $latestStocks = Bahan::query()
                    ->whereIn('id', $latestIds)
                    ->get();
            }


            // ====================================================
            // HITUNG TOTAL STOK
            // ====================================================

            $total = [];

            foreach ($this->fields as $field) {

                $total[$field] = $latestStocks->sum(function ($row) use ($field) {

                    return (float) ($row->{$field} ?? 0);

                });
            }


            // ====================================================
            // KIRIM KE VIEW
            // ====================================================

            $viewData['totalStok'] = (object) $total;
        }


        // ========================================================
        // 6. TAB HISTORY
        // ========================================================

        else {

            $historyQuery = Bahan::query()
                ->orderBy('created_at', 'asc');


            // ====================================================
            // FILTER OUTLET
            // ====================================================

            if ($filterOutlet) {

                $historyQuery->where(
                    'nama_outlet',
                    $filterOutlet
                );
            }


            // ====================================================
            // FILTER TANGGAL
            // ====================================================

            if ($startDate && $endDate) {

                $historyQuery->whereBetween(
                    'created_at',
                    [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]
                );
            }


            // ====================================================
            // AMBIL HISTORY
            // ====================================================

            $rawHistory = $historyQuery->get();

            $processedHistory = collect();

            $lastStateByOutlet = [];


            // ====================================================
            // PROSES PERUBAHAN STOK
            // ====================================================

            foreach ($rawHistory as $row) {

                $outlet = $row->nama_outlet;

                $prev = $lastStateByOutlet[$outlet] ?? null;

                $changes = [];


                foreach ($this->fields as $field) {

                    $curr = (float) ($row->{$field} ?? 0);

                    $prevVal = $prev
                        ? (float) ($prev->{$field} ?? 0)
                        : 0.0;


                    $changes[$field] = (object) [

                        'total' => $curr,

                        'change' => $curr - $prevVal,

                    ];
                }


                $processedHistory->push(
                    (object) [

                        'created_at' => $row->created_at,

                        'nama_outlet' => $outlet,

                        'data' => $changes,

                    ]
                );


                $lastStateByOutlet[$outlet] = $row;
            }


            // ====================================================
            // HISTORY TERBARU DI ATAS
            // ====================================================

            $viewData['history'] =
                $processedHistory->reverse()->values();
        }


        // ========================================================
        // 7. RETURN VIEW
        // ========================================================

        return view(
            'dashboard',
            $viewData
        );
    }
}
