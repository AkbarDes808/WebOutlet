<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDeduction extends Model
{
    protected $table = 'stock_deductions';

    protected $fillable = [
        'transaction_id',
        'stock_item_id',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}