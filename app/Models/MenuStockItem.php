<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuStockItem extends Model
{
    protected $table = 'menu_stock_items';

    protected $fillable = [
        'menu_id',
        'stock_item_id',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}