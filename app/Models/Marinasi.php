<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marinasi extends Model
{
    protected $table = 'marinasi';

    protected $fillable = [
        'kode_batch',
    ];

    public function items()
    {
        return $this->hasMany(MarinasiItem::class);
    }
}
