<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BahanController extends Controller
{
    public function index(Request $request)
    {
                $user = Auth::user();

        // ====================================================
        // ++ LOGIKA BARU UNTUK MEMERIKSA PERAN PENGGUNA ++
        // ====================================================
        if ($user->role === 'outlet') {
            // Jika user adalah 'outlet', paksa filter ke outletnya sendiri
            $selectedOutlet = trim($user->name);
            // Daftar outlet hanya berisi outlet milik user tersebut
            $outlets = collect([$user->name]);
        } else {
            // Jika admin/SPV, ambil filter dari request URL
            $selectedOutlet = $request->input('outlet');
            // Daftar outlet berisi semua outlet yang ada
            $outlets = Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');
        }
        // ====================================================
        
        $bahan = null;
        $viewData = [
            'outlets' => $outlets,
            'selectedOutlet' => $selectedOutlet,
        ];

        if ($selectedOutlet) {
            // Kueri ini sekarang otomatis terfilter berdasarkan peran pengguna
            $bahan = Bahan::where('nama_outlet', $selectedOutlet)->latest()->first();
            $viewData['bahan'] = $bahan;
        } else {
            // Kueri ini hanya akan berjalan untuk Admin/SPV
            if ($outlets->isNotEmpty()) {
                $latestStockIds = Bahan::select(DB::raw('MAX(id) as id'))
                                        ->groupBy('nama_outlet')
                                        ->pluck('id');
                
                $allLatestStock = Bahan::whereIn('id', $latestStockIds)->get();
                
                $totalStok = (object) [
                    'tepung_roti'  => $allLatestStock->sum('tepung_roti'),
                    'tepung_bumbu' => $allLatestStock->sum('tepung_bumbu'),
                    'garam'        => $allLatestStock->sum('garam'),
                    'bubuk_cabe'   => $allLatestStock->sum('bubuk_cabe'),
                    'telur'        => $allLatestStock->sum('telur'),
                    'gula'         => $allLatestStock->sum('gula'),
                    'ayam'         => $allLatestStock->sum('ayam'),
                ];
                $viewData['totalStok'] = $totalStok;
            }
        }

        return view('bahans.index', $viewData);
    }
    
public function store(Request $request)
{
    $rules = [
        'nama_outlet' => 'required|string|max:255',
        'tepung_roti' => 'required|numeric',
        'tepung_bumbu' => 'required|numeric',
        'garam' => 'required|numeric',
        'bubuk_cabe' => 'required|numeric',
        'telur' => 'required|numeric',
        'gula' => 'required|numeric',
        'ayam' => 'required|numeric',
    ];

    if (Auth::user()->role === 'outlet') {
        foreach (['tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe', 'telur', 'gula', 'ayam'] as $field) {
            $rules[$field] .= '|lte:0'; // lte:0 berarti "less than or equal to 0"
        }
    }

    $request->validate($rules);

    // Sisa dari logika Anda tidak perlu diubah, karena 100 + (-10) = 90
    $outlet = $request->nama_outlet;
    $lastStock = Bahan::where('nama_outlet', $outlet)->latest()->first();
    $newStockData = $request->only(['nama_outlet', 'tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe', 'telur', 'gula', 'ayam']);

    if ($lastStock) {
        foreach ($newStockData as $key => $value) {
            if ($key !== 'nama_outlet' && is_numeric($value)) {
                $newStockData[$key] = $lastStock->$key + $value;
            }
        }
    }
    
    Bahan::create($newStockData);

    return redirect()->route('bahans.index', ['outlet' => $outlet])
                     ->with('success', 'Stok bahan berhasil diperbarui!');
}
     public function history(Request $request)
    {
        $user = Auth::user();

        // ====================================================
        // ++ LOGIKA FILTER BERDASARKAN PERAN PENGGUNA ++
        // ====================================================
        if ($user->role === 'outlet') {
            // Jika user adalah 'outlet', paksa filter ke outletnya sendiri
            $selectedOutlet = trim($user->name);
            $outlets = collect([$user->name]);
        } else {
            // Jika admin/SPV, ambil filter dari request URL
            $selectedOutlet = $request->input('outlet');
            // Ambil daftar semua outlet untuk ditampilkan di dropdown
            $outlets = Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');
        }
        // ====================================================
        
        $processedHistory = collect();

        // Query data riwayat, urutkan dari yang paling lama untuk perhitungan
        $historyQuery = Bahan::orderBy('created_at', 'asc');

        // Terapkan filter outlet (yang sudah ditentukan di atas) pada query
        if ($selectedOutlet) {
            $historyQuery->where('nama_outlet', $selectedOutlet);
        }
        
        $history = $historyQuery->get();

        // Proses data riwayat untuk menghitung selisih (penambahan/pengurangan)
        $lastStateByOutlet = [];
        $fields = ['tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe', 'telur', 'gula', 'ayam'];

        foreach ($history as $currentRecord) {
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
            
            // Perbarui catatan terakhir untuk outlet ini untuk iterasi berikutnya
            $lastStateByOutlet[$outletName] = $currentRecord;
        }

        // Kirim semua data yang dibutuhkan ke view 'bahans.history'
        return view('bahans.history', [
            'outlets' => $outlets,
            'selectedOutlet' => $selectedOutlet,
            'history' => $processedHistory->reverse(), // Balik urutan agar yang terbaru tampil di atas
        ]);
    }

    public function create()
{
        // Ambil daftar outlet yang unik dari database
    $outlets = \App\Models\Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');

    // Kirim variabel $outlets ke view
    return view('bahans.create', compact('outlets'));
}
}