<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItemOutlet extends Model
{
    protected $table = 'stock_item_outlets';

    protected $fillable = [
        'stock_item_id',
        'outlet',
        'stok',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}