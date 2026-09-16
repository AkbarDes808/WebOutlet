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

            $table->unsignedBigInteger('user_id')->index();

            $table->string('outlet');
            $table->string('kasir');

            $table->date('tanggal');

            $table->time('waktu_mulai');
            $table->time('waktu_selesai');

            $table->integer('total_transaksi')->default(0);

            // 🔥 UANG (PAKAI DECIMAL, BUKAN BIGINT)
            $table->decimal('total_penjualan', 15, 2)->default(0);

            $table->decimal('cash_total', 15, 2)->default(0);
            $table->integer('cash_orders')->default(0);

            $table->decimal('qris_total', 15, 2)->default(0);
            $table->integer('qris_orders')->default(0);

            $table->decimal('uang_modal', 15, 2)->default(0);

            $table->decimal('expected_cash', 15, 2)->default(0);

            $table->decimal('actual_cash', 15, 2)->default(0);

            $table->decimal('selisih', 15, 2)->default(0);

            // 🔥 ini penting (pengeluaran manual)
            $table->decimal('pengeluaran_lainnya', 15, 2)->default(0);

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_closings');
    }
};