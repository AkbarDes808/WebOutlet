<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marinasi', function (Blueprint $table) {
            $table->id();
            $table->integer('daging_ayam')->default(0);
            $table->integer('saus_teriyaki')->default(0);
            $table->integer('bawang_putih')->default(0);
            $table->integer('lada')->default(0);
            $table->integer('garam')->default(0);
            $table->integer('ketumbar')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marinasi');
    }
};
