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

        if ($selectedOutlet) {
            $bahan = Bahan::where('id', function ($q) use ($selectedOutlet) {
                $q->select(DB::raw('MAX(id)'))
                  ->from('bahans')
                  ->where('nama_outlet', $selectedOutlet);
            })->first();

            $viewData['bahan'] = $bahan;
        } else {
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
    // STORE (DESIMAL AMAN)
    // ======================
public function store(Request $request)
{
    $request->validate([
        'nama_outlet' => 'required|string|max:255'
    ]);

    $outlet = trim($request->nama_outlet);

    $lastStock = Bahan::where('id', function ($q) use ($outlet) {
        $q->select(DB::raw('MAX(id)'))
          ->from('bahans')
          ->where('nama_outlet', $outlet);
    })->first();

    $data = ['nama_outlet' => $outlet];

    foreach ($this->fields as $field) {

        $input = $request->input($field);
        $delta = 0.0;

        if ($input !== null && $input !== '' && $input !== '-') {

            /**
             * INPUT SUDAH DALAM FORMAT:
             * 12,5 → JS → 12.5
             * MAKA TINGGAL CAST FLOAT
             */
            if (!is_numeric($input)) {
                // fallback keamanan
                $input = str_replace(',', '.', $input);
            }

            $delta = (float) $input;

            // outlet selalu minus
            if (Auth::user()->role === 'outlet') {
                $delta = -abs($delta);
            }
        }

        $last = $lastStock ? (float) $lastStock->$field : 0.0;
        $data[$field] = $last + $delta;
    }

    Bahan::create($data);

    return redirect()->back()->with('success', 'Stok berhasil diperbarui');
}


    // ======================
    // HISTORY (DESIMAL AMAN)
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
                $curr = (float) $row->$f;
                $prevVal = $prev ? (float) $prev->$f : 0.0;

                $changes[$f] = (object) [
                    'total'  => $curr,
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
