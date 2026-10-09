<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only these eight stock items are allowed in menu recipes:
        // Ayam, Teh Kotak, Saos Cabe, Kantong Sambal,
        // Kertas Ayam, Dus, Plastik Kecil, Plastik Sedang.
        $allowedStockItemIds = [5, 11, 12, 17, 18, 19, 20, 21];

        DB::table('menu_stock_items')
            ->whereNotIn('stock_item_id', $allowedStockItemIds)
            ->delete();

        // Keep historical references intact, but disable flour so it is no longer
        // available as an active stock item.
        DB::table('stock_items')
            ->whereRaw('LOWER(TRIM(nama)) = ?', ['tepung'])
            ->update([
                'aktif' => false,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Recipe rows deleted by this migration cannot be safely reconstructed.
        // Re-enable flour only; restore recipe rows manually if rolling back.
        DB::table('stock_items')
            ->whereRaw('LOWER(TRIM(nama)) = ?', ['tepung'])
            ->update([
                'aktif' => true,
                'updated_at' => now(),
            ]);
    }
};
