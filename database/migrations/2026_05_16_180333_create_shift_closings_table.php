<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_closings', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('outlet');
            $table->string('kasir');

            $table->date('tanggal');

            $table->time('waktu_mulai');
            $table->time('waktu_selesai');

            $table->integer('total_transaksi')->default(0);

            $table->bigInteger('total_penjualan')->default(0);

            $table->bigInteger('cash_total')->default(0);
            $table->integer('cash_orders')->default(0);

            $table->bigInteger('qris_total')->default(0);
            $table->integer('qris_orders')->default(0);

            $table->bigInteger('expected_cash')->default(0);

            $table->bigInteger('actual_cash')->default(0);

            $table->bigInteger('selisih')->default(0);

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_closings');
    }
};