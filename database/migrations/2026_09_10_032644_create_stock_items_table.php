<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori')->nullable();
            $table->string('satuan');
            $table->decimal('stok', 12, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};