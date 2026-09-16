<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_deductions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->cascadeOnDelete();

            $table->foreignId('stock_item_id')
                ->constrained('stock_items')
                ->cascadeOnDelete();

            $table->decimal('jumlah', 12, 2);

            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->unique(
                ['transaction_id', 'stock_item_id'],
                'stock_deductions_transaction_stock_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_deductions');
    }
};