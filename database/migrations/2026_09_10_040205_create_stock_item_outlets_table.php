<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_item_outlets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_item_id')
                ->constrained('stock_items')
                ->cascadeOnDelete();

            $table->string('outlet', 100);

            $table->decimal('stok', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(
                ['stock_item_id', 'outlet'],
                'stock_item_outlet_unique'
            );

            $table->index('outlet');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_item_outlets');
    }
};