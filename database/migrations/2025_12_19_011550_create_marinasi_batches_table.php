<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marinasi_batches', function (Blueprint $table) {
            $table->id();
            $table->string('kode_batch')->unique();
            $table->integer('total_ayam'); // total ayam hasil marinasi
            $table->integer('jumlah_batch')->default(1); // jumlah karung
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marinasi_batches');
    }
};
