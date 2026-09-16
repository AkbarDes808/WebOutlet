<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahans';

    protected $fillable = [
        'nama_outlet',
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

    protected $casts = [
        'tepung_roti' => 'float',
        'tepung_bumbu' => 'float',
        'garam' => 'float',
        'bubuk_cabe' => 'float',
        'telur' => 'float',
        'gula' => 'float',
        'ayam' => 'float',
        'tepung' => 'float',
        'teh' => 'float',
        'beras' => 'float',
        'cup' => 'float',
        'kertas_chicken_kecil' => 'float',
        'kertas_chicken_sedang' => 'float',
        'kertas_chicken_besar' => 'float',
        'dus_chicken' => 'float',
        'dus_chicken_jumbo' => 'float',
        'plastik_cup_isi_1' => 'float',
        'plastik_cup_isi_2' => 'float',
        'plastik_ayam_kecil' => 'float',
        'plastik_sedang' => 'float',
        'plastik_tanggung' => 'float',
        'plastik_besar' => 'float',
        'plastik_jumbo' => 'float',
    ];
}
