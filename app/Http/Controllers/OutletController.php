<?php

namespace App\Http\Controllers;

use App\Models\Bahan; // Pastikan model Bahan di-import
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Menampilkan halaman menu utama yang berisi daftar semua outlet.
     */
    public function index()
    {
        // Ambil semua nama outlet yang unik dari tabel 'bahans'
        $outlets = Bahan::select('nama_outlet')->distinct()->pluck('nama_outlet');

        // Kirim data outlets ke view
        return view('outlets.index', compact('outlets'));
    }
}