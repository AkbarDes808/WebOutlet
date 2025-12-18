<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marinasi', function (Blueprint $table) {
            $table->id();

            $table->integer('total_ayam');
            $table->integer('jumlah_batch');

            $table->decimal('saus_teriyaki', 10, 2)->default(0);
            $table->decimal('bawang_putih', 10, 2)->default(0);
            $table->decimal('lada', 10, 2)->default(0);
            $table->decimal('garam', 10, 2)->default(0);
            $table->decimal('ketumbar', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marinasi');
    }
};
