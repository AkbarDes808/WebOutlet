<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita ubah kolom 'role' agar menerima 'admin', 'SPV', dan 'outlet'
            $table->enum('role', ['admin', 'SPV', 'outlet'])->default('outlet')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kode untuk mengembalikan jika diperlukan (opsional)
            $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};