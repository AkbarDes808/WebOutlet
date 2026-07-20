<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller
{
    /**
     * SEMUA FIELD STOK
     * HARUS SAMA DENGAN KOLOM DATABASE
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

    public function index(Request $request)
    {
        // ======================
        // 1. DATA AWAL & FILTER
        // ======================
        $activeTab = $request->input('tab', 'inventory');
        $user = Auth::user();
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // ======================
        // 2. FILTER OUTLET
        // ======================
        if (preg_match('/^outlet\s\d+$/', $user->role)) {

            $filterOutlet = $user->role;

            $outlets = collect([$filterOutlet]);

        } else {

            $filterOutlet = $request->input('outlet');

            $outlets = Bahan::select('nama_outlet')
                ->distinct()
                ->pluck('nama_outlet');
        }

        // ======================
        // 3. DATA UNTUK VIEW
        // ======================
        $viewData = [
            'outlets'        => $outlets,
            'activeTab'      => $activeTab,
            'user'           => $user,
            'totalStok'      => null,
            'history'        => collect(),
            'selectedOutlet' => $filterOutlet,
        ];

        // ======================
        // 4. TAB INVENTORY
        // ======================
        if ($activeTab === 'inventory') {

            $query = Bahan::query();

            if ($filterOutlet) {
                $query->where('nama_outlet', $filterOutlet);
            }

            $latestStocks = collect();

            if ($query->clone()->exists()) {
                $latestIds = $query
                    ->select(DB::raw('MAX(id) as id'))
                    ->groupBy('nama_outlet')
                    ->pluck('id');

                $latestStocks = Bahan::whereIn('id', $latestIds)->get();
            }

            // HITUNG TOTAL STOK DINAMIS
            $total = [];
            foreach ($this->fields as $field) {
                $total[$field] = $latestStocks->sum($field);
            }

            $viewData['totalStok'] = (object) $total;
        }

        // ======================
        // 5. TAB HISTORY
        // ======================
        else {

            $historyQuery = Bahan::orderBy('created_at', 'asc');

            if ($filterOutlet) {
                $historyQuery->where('nama_outlet', $filterOutlet);
            }

            if ($startDate && $endDate) {
                $historyQuery->whereBetween(
                    'created_at',
                    [$startDate . ' 00:00:00', $endDate . ' 23:59:59']
                );
            }

            $rawHistory = $historyQuery->get();

            $processedHistory = collect();
            $lastStateByOutlet = [];

            foreach ($rawHistory as $row) {

                $outlet = $row->nama_outlet;
                $prev   = $lastStateByOutlet[$outlet] ?? null;
                $changes = [];

                foreach ($this->fields as $field) {
                    $curr = (float) $row->$field;
                    $prevVal = $prev ? (float) $prev->$field : 0.0;

                    $changes[$field] = (object) [
                        'total'  => $curr,
                        'change' => $curr - $prevVal,
                    ];
                }

                $processedHistory->push((object) [
                    'created_at'  => $row->created_at,
                    'nama_outlet' => $outlet,
                    'data'        => $changes,
                ]);

                $lastStateByOutlet[$outlet] = $row;
            }

            $viewData['history'] = $processedHistory->reverse();
        }

        // ======================
        // 6. RETURN VIEW
        // ======================
        return view('dashboard', $viewData);
    }
}
