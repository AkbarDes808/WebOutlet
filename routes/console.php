<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:reset-operational-data {--force : Skip confirmation}', function () {
    if (!$this->option('force') && !$this->confirm(
        'RESET SEMUA DATA OPERASIONAL? Transaksi, shift, riwayat stok akan dihapus dan semua stok menjadi 0. Backup database penuh dibuat terlebih dahulu.'
    )) {
        $this->warn('Reset dibatalkan. Tidak ada data yang diubah.');
        return self::SUCCESS;
    }

    $connectionName = config('database.default');
    $connection = config('database.connections.' . $connectionName, []);
    $driver = $connection['driver'] ?? $connectionName;
    $timestamp = now()->format('Ymd_His');
    $backupDir = storage_path('app/backups');

    File::ensureDirectoryExists($backupDir);

    try {
        if ($driver === 'pgsql') {
            $backupPath = $backupDir . '/weboutlet_before_reset_' . $timestamp . '.dump';
            $command = ['pg_dump', '--format=custom', '--no-owner', '--no-acl', '--file=' . $backupPath];

            if (!empty($connection['host'])) {
                $command[] = '--host=' . $connection['host'];
            }
            if (!empty($connection['port'])) {
                $command[] = '--port=' . $connection['port'];
            }
            if (!empty($connection['username'])) {
                $command[] = '--username=' . $connection['username'];
            }
            $command[] = '--dbname=' . $connection['database'];

            $process = new Process($command, base_path(), [
                'PGPASSWORD' => (string) ($connection['password'] ?? ''),
            ]);
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            $backupPath = $backupDir . '/weboutlet_before_reset_' . $timestamp . '.sql';
            $command = [
                'mysqldump',
                '--single-transaction',
                '--routines',
                '--triggers',
                '--result-file=' . $backupPath,
            ];

            if (!empty($connection['host'])) {
                $command[] = '--host=' . $connection['host'];
            }
            if (!empty($connection['port'])) {
                $command[] = '--port=' . $connection['port'];
            }
            if (!empty($connection['username'])) {
                $command[] = '--user=' . $connection['username'];
            }
            $command[] = $connection['database'];

            $process = new Process($command, base_path(), [
                'MYSQL_PWD' => (string) ($connection['password'] ?? ''),
            ]);
        } elseif ($driver === 'sqlite') {
            $databasePath = $connection['database'] ?? '';
            if ($databasePath === '' || $databasePath === ':memory:' || !is_file($databasePath)) {
                throw new \RuntimeException('File database SQLite tidak ditemukan atau menggunakan :memory:.');
            }

            $backupPath = $backupDir . '/weboutlet_before_reset_' . $timestamp . '.sqlite';
            if (!copy($databasePath, $backupPath)) {
                throw new \RuntimeException('Gagal menyalin file database SQLite.');
            }
            $process = null;
        } else {
            throw new \RuntimeException('Driver database tidak didukung untuk backup otomatis: ' . $driver);
        }

        if ($process !== null) {
            $process->setTimeout(600);
            $process->run();

            if (!$process->isSuccessful() || !is_file($backupPath) || filesize($backupPath) === 0) {
                @unlink($backupPath);
                throw new \RuntimeException(
                    'Backup gagal. Reset dibatalkan. Detail: ' . trim($process->getErrorOutput() . ' ' . $process->getOutput())
                );
            }
        }
    } catch (\Throwable $e) {
        $this->error('RESET DIBATALKAN: backup database gagal.');
        $this->line($e->getMessage());
        return self::FAILURE;
    }

    $this->info('Backup database berhasil: ' . $backupPath);
    $this->warn('Mulai menghapus data operasional...');

    try {
        $counts = DB::transaction(function () {
            $counts = [];

            foreach (['stock_adjustments', 'stock_deductions', 'transaction_items', 'transactions', 'shift_closings'] as $table) {
                if (Schema::hasTable($table)) {
                    $counts[$table] = DB::table($table)->count();
                    DB::table($table)->delete();
                }
            }

            if (Schema::hasTable('users') && Schema::hasColumn('users', 'shift_started_at')) {
                $counts['users_shift_reset'] = DB::table('users')
                    ->whereNotNull('shift_started_at')
                    ->update(['shift_started_at' => null]);
            }

            if (Schema::hasTable('stock_item_outlets') && Schema::hasColumn('stock_item_outlets', 'stok')) {
                $counts['stock_item_outlets_rows_zeroed'] = DB::table('stock_item_outlets')
                    ->where('stok', '!=', 0)
                    ->update(['stok' => 0, 'updated_at' => now()]);
            }

            if (Schema::hasTable('stock_items') && Schema::hasColumn('stock_items', 'stok')) {
                $counts['stock_items_rows_zeroed'] = DB::table('stock_items')
                    ->where('stok', '!=', 0)
                    ->update(['stok' => 0, 'updated_at' => now()]);
            }

            if (Schema::hasTable('bahans')) {
                $quantityColumns = [
                    'tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe',
                    'telur', 'gula', 'ayam', 'tepung', 'teh', 'beras', 'cup',
                    'kertas_chicken_kecil', 'kertas_chicken_sedang',
                    'kertas_chicken_besar', 'dus_chicken', 'dus_chicken_jumbo',
                    'plastik_cup_isi_1', 'plastik_cup_isi_2',
                    'plastik_ayam_kecil', 'plastik_sedang',
                    'plastik_tanggung', 'plastik_besar', 'plastik_jumbo',
                ];

                $values = [];
                foreach ($quantityColumns as $column) {
                    if (Schema::hasColumn('bahans', $column)) {
                        $values[$column] = 0;
                    }
                }

                if ($values !== []) {
                    $counts['bahans_rows_zeroed'] = DB::table('bahans')
                        ->where(function ($query) use ($values) {
                            foreach (array_keys($values) as $column) {
                                $query->orWhere($column, '!=', 0);
                            }
                        })
                        ->update($values);
                }
            }

            return $counts;
        });
    } catch (\Throwable $e) {
        $this->error('Reset gagal dan perubahan database dibatalkan. Backup tetap tersedia: ' . $backupPath);
        $this->line($e->getMessage());
        return self::FAILURE;
    }

    $this->newLine();
    $this->info('RESET DATA OPERASIONAL BERHASIL.');
    $this->line('Backup penuh: ' . $backupPath);
    $this->line('Ringkasan: ' . json_encode($counts, JSON_UNESCAPED_UNICODE));
    $this->comment('Akun login, menu, resep, dan konfigurasi tidak dihapus.');
})->purpose('Backup database lalu reset seluruh transaksi, shift, dan stok operasional');
