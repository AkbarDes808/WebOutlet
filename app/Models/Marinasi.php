<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marinasi extends Model
{
    use HasFactory;

    protected $table = 'marinasi'; // sesuai dengan migration

    protected $fillable = [
        'daging_ayam',
        'saus_teriyaki',
        'bawang_putih',
        'lada',
        'garam',
        'ketumbar',
    ];
}
