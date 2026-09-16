<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_outlet');

            $table->decimal('tepung_roti', 10, 2)->nullable();
            $table->decimal('tepung_bumbu', 10, 2)->nullable();
            $table->decimal('garam', 10, 2)->nullable();
            $table->decimal('bubuk_cabe', 10, 2)->nullable();
            $table->decimal('telur', 10, 2)->nullable();
            $table->decimal('gula', 10, 2)->nullable();
            $table->decimal('ayam', 10, 2)->nullable();
            $table->decimal('tepung', 10, 2)->nullable();
            $table->decimal('teh', 10, 2)->nullable();
            $table->decimal('beras', 10, 2)->nullable();
            $table->decimal('cup', 10, 2)->nullable();
            $table->decimal('kertas_chicken_kecil', 10, 2)->nullable();
            $table->decimal('kertas_chicken_sedang', 10, 2)->nullable();
            $table->decimal('kertas_chicken_besar', 10, 2)->nullable();
            $table->decimal('dus_chicken', 10, 2)->nullable();
            $table->decimal('dus_chicken_jumbo', 10, 2)->nullable();
            $table->decimal('plastik_cup_isi_1', 10, 2)->nullable();
            $table->decimal('plastik_cup_isi_2', 10, 2)->nullable();
            $table->decimal('plastik_ayam_kecil', 10, 2)->nullable();
            $table->decimal('plastik_sedang', 10, 2)->nullable();
            $table->decimal('plastik_tanggung', 10, 2)->nullable();
            $table->decimal('plastik_besar', 10, 2)->nullable();
            $table->decimal('plastik_jumbo', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};

