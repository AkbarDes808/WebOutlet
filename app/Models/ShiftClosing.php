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

        'actual_cash',
        'selisih',

        'uang_modal',
        'pengeluaran_lainnya',

        'status_shift',

        'catatan',
    ];


    protected $casts = [

        'tanggal' => 'date',

        'total_transaksi' => 'integer',
        'total_penjualan' => 'integer',

        'cash_total' => 'integer',
        'cash_orders' => 'integer',

        'qris_total' => 'integer',
        'qris_orders' => 'integer',

        'actual_cash' => 'integer',
        'selisih' => 'integer',

        'uang_modal' => 'integer',

        'pengeluaran_lainnya' => 'integer',
    ];
}