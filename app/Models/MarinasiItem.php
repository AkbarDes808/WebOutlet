<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarinasiItem extends Model
{
    protected $table = 'marinasi_items';

    protected $fillable = [
        'marinasi_id',
        'bahan',
        'total',
        'per_batch',
    ];

    public function marinasi()
    {
        return $this->belongsTo(Marinasi::class);
    }
}
