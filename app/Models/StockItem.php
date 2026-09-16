<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $table = 'stock_items';

    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'stok',
        'aktif',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
        'aktif' => 'boolean',
    ];

    public function menuStockItems()
    {
        return $this->hasMany(MenuStockItem::class);
    }

    public function deductions()
    {
        return $this->hasMany(StockDeduction::class);
    }

    public function outletStocks()
    {
        return $this->hasMany(StockItemOutlet::class);
    }
}