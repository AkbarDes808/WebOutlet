<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marinasi_batch_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marinasi_batch_id')
                  ->constrained('marinasi_batches')
                  ->cascadeOnDelete();

            $table->string('bahan');               // lada, gula, dll
            $table->decimal('total', 10, 2);       // total awal
            $table->decimal('sisa', 10, 2);        // sisa saat ini
            $table->string('satuan', 10)->default('kg');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marinasi_batch_items');
    }
};
