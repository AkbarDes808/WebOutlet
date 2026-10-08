<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $source = DB::table('stock_items')
            ->where('nama', 'Saus Sambal Sachet')
            ->first();

        $target = DB::table('stock_items')
            ->whereIn('nama', ['Saus Cabe', 'Saos Cabe'])
            ->orderBy('id')
            ->first();

        if ($source && $target && $source->id !== $target->id) {
            $sourceRows = DB::table('stock_item_outlets')
                ->where('stock_item_id', $source->id)
                ->get();

            foreach ($sourceRows as $sourceRow) {
                $targetRow = DB::table('stock_item_outlets')
                    ->where('stock_item_id', $target->id)
                    ->whereRaw('LOWER(TRIM(outlet)) = ?', [strtolower(trim($sourceRow->outlet))])
                    ->first();

                if ($targetRow) {
                    DB::table('stock_item_outlets')
                        ->where('id', $targetRow->id)
                        ->update([
                            'stok' => (float) $targetRow->stok + (float) $sourceRow->stok,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('stock_item_outlets')->insert([
                        'stock_item_id' => $target->id,
                        'outlet' => $sourceRow->outlet,
                        'stok' => $sourceRow->stok,
                        'created_at' => $sourceRow->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('stock_item_outlets')
                ->where('stock_item_id', $source->id)
                ->delete();

            $sourceRecipes = DB::table('menu_stock_items')
                ->where('stock_item_id', $source->id)
                ->get();

            foreach ($sourceRecipes as $recipe) {
                $targetRecipe = DB::table('menu_stock_items')
                    ->where('menu_id', $recipe->menu_id)
                    ->where('stock_item_id', $target->id)
                    ->first();

                if ($targetRecipe) {
                    DB::table('menu_stock_items')
                        ->where('id', $targetRecipe->id)
                        ->update([
                            'jumlah' => (float) $targetRecipe->jumlah + (float) $recipe->jumlah,
                            'updated_at' => now(),
                        ]);

                    DB::table('menu_stock_items')
                        ->where('id', $recipe->id)
                        ->delete();
                } else {
                    DB::table('menu_stock_items')
                        ->where('id', $recipe->id)
                        ->update([
                            'stock_item_id' => $target->id,
                            'updated_at' => now(),
                        ]);
                }
            }

            DB::table('stock_deductions')
                ->where('stock_item_id', $source->id)
                ->update(['stock_item_id' => $target->id]);

            if (DB::getSchemaBuilder()->hasTable('stock_adjustments')) {
                DB::table('stock_adjustments')
                    ->where('stock_item_id', $source->id)
                    ->update(['stock_item_id' => $target->id]);
            }

            DB::table('stock_items')
                ->where('id', $source->id)
                ->delete();

            $targetId = $target->id;
        } else {
            $targetId = $target?->id;
        }

        if ($targetId) {
            DB::table('stock_items')
                ->where('id', $targetId)
                ->update(['nama' => 'Saos Cabe']);
        }

        DB::table('stock_items')
            ->where('nama', 'Cabe')
            ->update(['nama' => 'Kantong Sambal']);

        DB::table('menus')
            ->where('name', 'Saus Cabe')
            ->update(['name' => 'Saos Cabe']);
    }

    public function down(): void
    {
        // Intentionally left non-destructive.
    }
};
