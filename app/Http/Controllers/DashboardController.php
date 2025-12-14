<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data user, tab aktif, dan semua filter di awal
        $activeTab = $request->input('tab', 'inventory');
        $user = Auth::user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // 2. Tentukan filter outlet berdasarkan peran (role)
        if ($user->role === 'outlet') {
            $filterOutlet = trim($user->name); 
            $outlets = collect([$user->name]);
        } else {
            $filterOutlet = $request->input('outlet');
            $outlets = Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');
        }
        
        // 3. Siapkan data awal untuk dikirim ke view
        $viewData = [
            'outlets' => $outlets,
            'activeTab' => $activeTab,
            'user' => $user,
            'totalStok' => null, // Variabel yang benar untuk inventory
            'history' => collect() // Default ke koleksi kosong
        ];

        // 4. Logika terpisah untuk setiap tab
        if ($activeTab == 'inventory') {
            // HANYA jalankan ini jika tab inventory aktif
            $query = Bahan::query();
            if ($filterOutlet) {
                $query->where('nama_outlet', $filterOutlet);
            }
            
            $allLatestStock = collect();
            if ($query->clone()->exists()){
                $latestStockIds = $query->select(DB::raw('MAX(id) as id'))->groupBy('nama_outlet')->pluck('id');
                $allLatestStock = Bahan::whereIn('id', $latestStockIds)->get();
            }

            // Gunakan variabel $totalStok yang akurat
            $viewData['totalStok'] = (object) [
                'tepung_roti'  => $allLatestStock->sum('tepung_roti'),
                'tepung_bumbu' => $allLatestStock->sum('tepung_bumbu'),
                'garam'        => $allLatestStock->sum('garam'),
                'bubuk_cabe'   => $allLatestStock->sum('bubuk_cabe'),
                'telur'        => $allLatestStock->sum('telur'),
                'gula'         => $allLatestStock->sum('gula'),
                'ayam'         => $allLatestStock->sum('ayam'),
            ];

        } else { // $activeTab == 'history'
            // HANYA jalankan ini jika tab history aktif
            $historyQuery = Bahan::orderBy('created_at', 'asc');
            if ($filterOutlet) {
                $historyQuery->where('nama_outlet', $filterOutlet);
            }
            if ($startDate && $endDate) {
                $historyQuery->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
            }
            $rawHistory = $historyQuery->get();

            $processedHistory = collect();
            $lastStateByOutlet = [];
            $fields = ['tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe', 'telur', 'gula', 'ayam'];

            foreach ($rawHistory as $currentRecord) {
                $outletName = $currentRecord->nama_outlet;
                $previousRecord = $lastStateByOutlet[$outletName] ?? null;
                $changes = [];

                foreach ($fields as $field) {
                    $currentValue = $currentRecord->$field;
                    $previousValue = $previousRecord ? $previousRecord->$field : 0;
                    $difference = $currentValue - $previousValue;
                    $changes[$field] = (object) ['total' => $currentValue, 'change' => $difference];
                }

                $processedHistory->push((object) [
                    'created_at' => $currentRecord->created_at,
                    'nama_outlet' => $outletName,
                    'data' => $changes,
                ]);
                $lastStateByOutlet[$outletName] = $currentRecord;
            }
            
            $viewData['history'] = $processedHistory->reverse();
        }

        // 5. Kirim semua data yang sudah disiapkan ke view
        return view('dashboard', $viewData);
    }
}