<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Hide retired menu items without deleting transaction history.
     */
    public function up(): void
    {
        DB::table('menus')
            ->whereIn(DB::raw('LOWER(TRIM(name))'), [
                'ayam krispi',
                'paket ayam krispi',
                'paket ayam krispi + es teh',
            ])
            ->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Intentionally do not reactivate these menus automatically.
    }
};
