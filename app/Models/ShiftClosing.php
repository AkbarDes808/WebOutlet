<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftClosing extends Model
{
    protected $fillable = [

        'user_id',

        'outlet',
        'kasir',

        'tanggal',

        'waktu_mulai',
        'waktu_selesai',

        'total_transaksi',

        'total_penjualan',

        'cash_total',
        'cash_orders',

        'qris_total',
        'qris_orders',

        'expected_cash',

        'actual_cash',

        'selisih',

        'catatan',
    ];
}