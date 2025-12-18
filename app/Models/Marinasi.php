<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marinasi extends Model
{
    use HasFactory;

    protected $table = 'marinasi';

    protected $fillable = [
        'kode_batch',
        'total_ayam',
        'jumlah_batch',
        'lada',
        'bawang_putih',
        'saus_teriyaki',
        'garam',
        'ketumbar',
    ];
}
