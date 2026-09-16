<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            if (!Schema::hasColumn('transactions', 'kasir_id')) {
                $table->unsignedBigInteger('kasir_id')->nullable()->after('user_id');

                $table->foreign('kasir_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }

        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {

            if (Schema::hasColumn('transactions', 'kasir_id')) {
                $table->dropForeign(['kasir_id']);
                $table->dropColumn('kasir_id');
            }

        });
    }
};