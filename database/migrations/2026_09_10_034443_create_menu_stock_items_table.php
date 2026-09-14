<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_stock_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                ->constrained('menus')
                ->cascadeOnDelete();

            $table->foreignId('stock_item_id')
                ->constrained('stock_items')
                ->cascadeOnDelete();

            $table->decimal('jumlah', 12, 2);

            $table->timestamps();

            $table->unique(
                ['menu_id', 'stock_item_id'],
                'menu_stock_items_menu_stock_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_stock_items');
    }
};