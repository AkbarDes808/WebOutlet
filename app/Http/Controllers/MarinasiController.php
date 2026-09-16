<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarinasiController extends Controller
{
    /**
     * =====================================================
     * HALAMAN INPUT STOK (INDEX)
     * =====================================================
     */
    public function index()
    {
        $hurufList = range('A', 'Z');
        $stok = [];

        foreach ($hurufList as $huruf) {
            $stok[$huruf] = DB::table('marinasi_items')
                ->where('bahan', 'LIKE', '% ' . $huruf)
                ->sum('total');
        }

        return view('marinasi.index', compact('stok'));
    }

    /**
     * =====================================================
     * HALAMAN PRODUKSI & HISTORY
     * =====================================================
     */
    public function produksi()
    {
        $history = DB::table('marinasi_items')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPerBahan = DB::table('marinasi_items')
            ->select('bahan', DB::raw('SUM(total) as total_sisa'))
            ->groupBy('bahan')
            ->pluck('total_sisa', 'bahan');

        return view('marinasi.produksi', compact('history', 'totalPerBahan'));
    }

    /**
     * =====================================================
     * SIMPAN STOK SAJA (BUTTON PRODUKSI BARU)
     * =====================================================
     */
    public function storeOnlyItems(Request $request)
    {
        $jenisForm = $request->input('jenis_form'); // marinasi / lapis

        // Ambil data sesuai form
        $items = $jenisForm === 'marinasi'
            ? $request->input('marinasi', [])
            : $request->input('lapis', []);

        if (empty($items)) {
            return back()->with('error', 'Tidak ada bahan yang dimasukkan');
        }

        // Tentukan JENIS otomatis
        $jenisBumbu = $jenisForm === 'marinasi'
            ? 'Bumbu Tepung Marinasi'
            : 'Bumbu Tepung Lapis';

        $marinasiId = random_int(100000, 999999);

        foreach ($items as $kode => $jumlah) {

            if (!$jumlah || $jumlah <= 0) continue;

            // Nama bahan (tetap konsisten dengan produksi)
            $namaBahan = in_array($kode, ['C','J','L','M','N'])
                ? 'Marinasi & Lapis ' . $kode
                : ucfirst($jenisForm) . ' ' . $kode;

            DB::table('marinasi_items')->insert([
                'marinasi_id' => $marinasiId,
                'bahan'  => $namaBahan,
                'jenis'       => $jenisBumbu, // 🔥 INI KUNCINYA
                'penggunaan'  => 'Tambah Stok',
                'banyak'      => $jumlah,
                'satuan'      => 'Gram',
                'total'       => $jumlah,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        return back()->with(
            'success',
            $jenisBumbu . ' berhasil disimpan'
        );
    }
    /**
     * =====================================================
     * PRODUKSI MARINASI
     * =====================================================
     */
    public function submitMarinasi(Request $request)
    {
        $request->validate([
            'jumlah_ayam' => 'required|numeric|min:1500'
        ]);

        $jumlahAyam = $request->jumlah_ayam;
        $batch = $jumlahAyam / 1500;

        $marinasiId = random_int(100000, 999999);

        $resep = [
            'A' => 1000,
            'B' => 2,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 12.5,
            'J' => 30,
            'K' => 600,
            'L' => 200,
            'M' => 100,
            'N' => 700,
            'O' => 100,
        ];

        foreach ($resep as $kode => $gram) {

            $namaBahan = in_array($kode, ['C','J','L','M','N'])
                ? 'Marinasi & Lapis ' . $kode
                : 'Marinasi ' . $kode;

            DB::table('marinasi_items')->insert([
                'marinasi_id' => $marinasiId,
                'bahan'  => $namaBahan,
                'jenis'       => 'Bumbu Tepung Marinasi',
                'penggunaan'  => 'Ayam',
                'banyak'      => $jumlahAyam,
                'satuan'      => 'Pieces',
                'total'       => -1 * ($gram * $batch),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Produksi Marinasi berhasil disimpan');
    }

    /**
     * =====================================================
     * PRODUKSI LAPIS
     * =====================================================
     */
    public function submitLapis(Request $request)
    {
        $request->validate([
            'jumlah_karung' => 'required|numeric|min:25'
        ]);

        $jumlahKg = $request->jumlah_karung;
        $batch = $jumlahKg / 25;

        $marinasiId = random_int(100000, 999999);

        $resep = [
            'A' => 7070,
            'C' => 70,
            'J' => 150,
            'L' => 5000,
            'M' => 2800,
            'N' => 3650,
            'P' => 500,
            'Q' => 800,
            'R' => 1600,
            'S' => 1000,
        ];

        foreach ($resep as $kode => $gram) {

            $namaBahan = in_array($kode, ['C','J','L','M','N'])
                ? 'Marinasi & Lapis ' . $kode
                : 'Lapis ' . $kode;

            DB::table('marinasi_items')->insert([
                'marinasi_id' => $marinasiId,
                'bahan'  => $namaBahan,
                'jenis'       => 'Bumbu Tepung Lapis',
                'penggunaan'  => 'Simpan Karung',
                'banyak'      => $jumlahKg,
                'satuan'      => 'Kilogram',
                'total'       => -1 * ($gram * $batch),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Produksi Lapis berhasil disimpan');
    }
}