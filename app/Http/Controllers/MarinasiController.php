<?php

namespace App\Http\Controllers;

use App\Models\Marinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarinasiController extends Controller
{
    /**
     * TAMPIL HALAMAN MARINASI
     */
    public function index()
    {
        // Ambil semua batch marinasi (terbaru di atas)
        $batches = Marinasi::orderBy('created_at', 'desc')->get();

        return view('marinasi.index', compact('batches'));
    }

    /**
     * SIMPAN BATCH MARINASI BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'total_ayam'   => 'required|integer|min:1',
            'jumlah_batch' => 'required|integer|min:1',
            'lada'         => 'required|numeric|min:0',
            'bawang_putih' => 'required|numeric|min:0',
            'saus_teriyaki'=> 'required|numeric|min:0',
            'garam'        => 'required|numeric|min:0',
            'ketumbar'     => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            Marinasi::create([
                'kode_batch'    => 'BATCH-' . now()->format('Ymd-His'),
                'total_ayam'    => $request->total_ayam,
                'jumlah_batch'  => $request->jumlah_batch,
                'lada'          => $request->lada,
                'bawang_putih'  => $request->bawang_putih,
                'saus_teriyaki' => $request->saus_teriyaki,
                'garam'         => $request->garam,
                'ketumbar'      => $request->ketumbar,
            ]);

        });

        return redirect()
            ->route('marinasi.index')
            ->with('success', 'Batch marinasi berhasil disimpan');
    }
}
