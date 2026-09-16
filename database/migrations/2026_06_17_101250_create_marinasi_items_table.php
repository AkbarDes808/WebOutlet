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
        Schema::create('marinasi_items', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('marinasi_id');

            $table->string('bahan');
            $table->string('jenis');
            $table->string('penggunaan');

            $table->decimal('banyak', 15, 2)->default(0);
            $table->string('satuan');

            $table->decimal('total', 15, 2)->default(0);

            $table->timestamps();

            $table->index('marinasi_id');
            $table->index('bahan');
            $table->index('jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marinasi_items');
    }
};