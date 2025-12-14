<?php

namespace App\Http\Controllers;

use App\Models\Marinasi;
use Illuminate\Http\Request;

class MarinasiController extends Controller
{
    public function index()
    {
        // Ambil data marinasi terakhir
        $marinasi = Marinasi::latest()->first();

        return view('marinasi.index', compact('marinasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'daging_ayam'    => 'required|numeric',
            'saus_teriyaki'  => 'required|numeric',
            'bawang_putih'   => 'required|numeric',
            'lada'           => 'required|numeric',
            'garam'          => 'required|numeric',
            'ketumbar'       => 'required|numeric',
        ]);

        Marinasi::create([
            'daging_ayam'    => $request->daging_ayam,
            'saus_teriyaki'  => $request->saus_teriyaki,
            'bawang_putih'   => $request->bawang_putih,
            'lada'           => $request->lada,
            'garam'          => $request->garam,
            'ketumbar'       => $request->ketumbar,
        ]);

        return redirect()->route('marinasi.index')->with('success', 'Data marinasi berhasil disimpan.');
    }
}
