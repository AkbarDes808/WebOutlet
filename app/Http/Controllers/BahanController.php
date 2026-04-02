<?php

namespace App\Http\Controllers;

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

    /* =====================================
     * INDEX
     * ===================================== */
    public function index(Request $request)
    {
        $outlets = DB::table('bahans')
            ->select('nama_outlet')
            ->distinct()
            ->orderBy('nama_outlet')
            ->pluck('nama_outlet');

        $selectedOutlet = $request->outlet;

        // 🔥 Ambil ID terakhir per outlet
        $latestIds = DB::table('bahans')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('nama_outlet')
            ->pluck('id');

        $latestStocks = DB::table('bahans')
            ->whereIn('id', $latestIds)
            ->get();

        $totalStok = [];
        foreach ($this->rows as $field) {
            $totalStok[$field] = $latestStocks->sum($field);
        }

        $totalStok = (object) $totalStok;

        $bahan = null;

        if ($selectedOutlet) {
            $bahan = DB::table('bahans')
                ->where('nama_outlet', $selectedOutlet)
                ->orderByDesc('id')
                ->first();
        }

        return view('bahans.index', compact(
            'outlets',
            'selectedOutlet',
            'bahan',
            'totalStok'
        ));
    }

    /* =====================================
     * STORE (BUAT RECORD BARU)
     * ===================================== */
    public function store(Request $request)
    {
        $request->validate([
            'nama_outlet' => 'required|string'
        ]);

        $outlet = $request->nama_outlet;

        $last = DB::table('bahans')
            ->where('nama_outlet', $outlet)
            ->orderByDesc('id')
            ->first();

        $data = [
            'nama_outlet' => $outlet,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        foreach ($this->rows as $field) {

            $input = $request->input($field);

            if ($input !== null && $input !== '') {
                $input = str_replace(',', '.', $input);
                $delta = (float) $input;
            } else {
                $delta = 0;
            }

            $lastValue = $last ? ($last->$field ?? 0) : 0;

            $data[$field] = $lastValue + $delta;
        }

        DB::table('bahans')->insert($data);

        return redirect()->back()->with('success', 'Stok berhasil disimpan');
    }

    /* =====================================
     * HISTORY
     * ===================================== */
    public function history(Request $request)
    {
        $outlets = DB::table('bahans')
            ->select('nama_outlet')
            ->distinct()
            ->orderBy('nama_outlet')
            ->pluck('nama_outlet');

        $selectedOutlet = $request->outlet;

        $query = DB::table('bahans')
            ->orderBy('id', 'asc');

        if ($selectedOutlet) {
            $query->where('nama_outlet', $selectedOutlet);
        }

        $historyRaw = $query->get();

        $lastState = [];
        $processed = collect();

        foreach ($historyRaw as $row) {

            $outlet = $row->nama_outlet;
            $prev = $lastState[$outlet] ?? null;

            $changes = [];

            foreach ($this->rows as $field) {

                $curr = (float) ($row->$field ?? 0);
                $prevVal = $prev ? (float) ($prev->$field ?? 0) : 0;

                $changes[$field] = (object) [
                    'total'  => $curr,
                    'change' => $curr - $prevVal
                ];
            }

            $processed->push((object)[
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
