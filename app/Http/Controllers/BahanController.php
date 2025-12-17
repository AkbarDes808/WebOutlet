<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BahanController extends Controller
{
    private array $fields = [
        'tepung_roti','tepung_bumbu','garam',
        'bubuk_cabe','telur','gula','ayam'
    ];

    // ======================
    // INDEX
    // ======================
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'outlet') {
            $selectedOutlet = trim($user->name);
            $outlets = collect([$selectedOutlet]);
        } else {
            $selectedOutlet = $request->input('outlet');
            $outlets = Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');
        }

        $viewData = compact('outlets', 'selectedOutlet');

        // ===============================
        // JIKA OUTLET DIPILIH
        // ===============================
        if ($selectedOutlet) {
            $bahan = Bahan::where('id', function ($q) use ($selectedOutlet) {
                $q->select(DB::raw('MAX(id)'))
                  ->from('bahans')
                  ->where('nama_outlet', $selectedOutlet);
            })->first();

            $viewData['bahan'] = $bahan;
        }

        // ===============================
        // JIKA TOTAL SEMUA OUTLET
        // ===============================
        else {
            if ($outlets->isNotEmpty()) {
                $latestIds = Bahan::select(DB::raw('MAX(id) as id'))
                    ->groupBy('nama_outlet')
                    ->pluck('id');

                $latestStocks = Bahan::whereIn('id', $latestIds)->get();

                $total = [];
                foreach ($this->fields as $f) {
                    $total[$f] = $latestStocks->sum($f);
                }

                $viewData['totalStok'] = (object) $total;
            }
        }

        return view('bahans.index', $viewData);
    }

    // ======================
    // STORE (ANTI BUG)
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'nama_outlet' => 'required|string|max:255'
        ]);

        $outlet = trim($request->nama_outlet);

        // ambil stok terakhir VALID
        $lastStock = Bahan::where('id', function ($q) use ($outlet) {
            $q->select(DB::raw('MAX(id)'))
              ->from('bahans')
              ->where('nama_outlet', $outlet);
        })->first();

        $data = ['nama_outlet' => $outlet];

        foreach ($this->fields as $field) {

            // bersihkan koma → angka murni
            $input = $request->input($field);
            $delta = 0;

            if ($input !== null && $input !== '') {
                $delta = (int) str_replace(',', '', $input);

                // outlet otomatis minus
                if (Auth::user()->role === 'outlet') {
                    $delta = -abs($delta);
                }
            }

            $last = $lastStock ? (int) $lastStock->$field : 0;
            $data[$field] = $last + $delta;
        }

        Bahan::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return redirect()->back()->with('success', 'Stok berhasil diperbarui');
    }

    // ======================
    // HISTORY (TETAP AMAN)
    // ======================
    public function history(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'outlet') {
            $selectedOutlet = trim($user->name);
            $outlets = collect([$selectedOutlet]);
        } else {
            $selectedOutlet = $request->input('outlet');
            $outlets = Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');
        }

        $historyQuery = Bahan::orderBy('id', 'asc');

        if ($selectedOutlet) {
            $historyQuery->where('nama_outlet', $selectedOutlet);
        }

        $history = $historyQuery->get();
        $lastState = [];
        $processed = collect();

        foreach ($history as $row) {
            $outlet = $row->nama_outlet;
            $prev = $lastState[$outlet] ?? null;

            $changes = [];
            foreach ($this->fields as $f) {
                $curr = (int) $row->$f;
                $prevVal = $prev ? (int) $prev->$f : 0;

                $changes[$f] = (object) [
                    'total' => $curr,
                    'change' => $curr - $prevVal
                ];
            }

            $processed->push((object) [
                'created_at' => $row->created_at,
                'nama_outlet' => $outlet,
                'data' => $changes
            ]);

            $lastState[$outlet] = $row;
        }

        return view('bahans.history', [
            'outlets' => $outlets,
            'selectedOutlet' => $selectedOutlet,
            'history' => $processed->reverse()
        ]);
    }
}
