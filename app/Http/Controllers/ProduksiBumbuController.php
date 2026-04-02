<?php

namespace App\Http\Controllers;

use App\Models\MarinasiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiBumbuController extends Controller
{
    public function useMarinasi(Request $request)
    {

        DB::transaction(function () use ($request) {

            // MARINASI A–O
            if ($request->has('marinasi')) {
                foreach ($request->marinasi as $kode => $jumlah) {
                    if ($jumlah !== null && $jumlah > 0) {
                        MarinasiItem::create([
                            'marinasi_id' => null,
                            'bahan'  => 'Marinasi ' . $kode,
                            'total'       => $jumlah,
                            'per_batch'   => 0,
                        ]);
                    }
                }
            }

            // LAPIS P–S
            if ($request->has('lapis')) {
                foreach ($request->lapis as $kode => $jumlah) {
                    if ($jumlah !== null && $jumlah > 0) {
                        MarinasiItem::create([
                            'marinasi_id' => null,
                            'bahan'  => 'Lapis ' . $kode,
                            'total'       => $jumlah,
                            'per_batch'   => 0,
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'DATA MASUK DATABASE');
    }
}
