<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarinasiItem extends Model
{
    use HasFactory;

    protected $table = 'marinasi_items';

    protected $fillable = [
        'marinasi_id',
        'nama_bahan',
        'total',
        'per_batch',
    ];

    public function marinasi()
    {
        return $this->belongsTo(Marinasi::class, 'marinasi_id');
    }
    public function index()
{
    // ambil semua batch marinasi
    $batches = Marinasi::orderBy('created_at', 'desc')->get();

    return view('marinasi.index', compact('batches'));
}
}
