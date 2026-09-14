<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{
    /**
     * Daftar tabel
     */
    public function index()
    {
        $tables = DB::select("
            SELECT
                table_name
            FROM information_schema.tables
            WHERE table_schema = 'public'
            AND table_type = 'BASE TABLE'
            ORDER BY table_name
        ");

        return view('database.index', compact('tables'));
    }

    /**
     * Isi sebuah tabel
     */
    public function table($table)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);

        $rows = DB::table($table)
            ->limit(200)
            ->get();

        $count = DB::table($table)->count();

        return view('database.table', compact(
            'table',
            'columns',
            'rows',
            'count'
        ));
    }

    /**
     * Form tambah data
     */
    public function create($table)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);

        return view('database.create', compact(
            'table',
            'columns'
        ));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request, $table)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);

        $data = [];

        foreach ($columns as $column) {
            if ($request->has($column)) {
                $value = $request->input($column);

                if ($value !== null && $value !== '') {
                    $data[$column] = $value;
                }
            }
        }

        if (!empty($data)) {
            DB::table($table)->insert($data);
        }

        return redirect()
            ->route('database.table', $table)
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit($table, $id)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);

        $primaryKey = $this->getPrimaryKey($table);

        if (!$primaryKey) {
            abort(400, 'Tabel ini tidak memiliki primary key.');
        }

        $row = DB::table($table)
            ->where($primaryKey, $id)
            ->first();

        if (!$row) {
            abort(404, 'Data tidak ditemukan.');
        }

        return view('database.edit', compact(
            'table',
            'columns',
            'row',
            'primaryKey'
        ));
    }

    /**
     * Update data
     */
    public function update(Request $request, $table, $id)
    {
        $this->validateTable($table);

        $columns = Schema::getColumnListing($table);
        $primaryKey = $this->getPrimaryKey($table);

        if (!$primaryKey) {
            abort(400, 'Tabel ini tidak memiliki primary key.');
        }

        $data = [];

        foreach ($columns as $column) {

            // Jangan update primary key
            if ($column === $primaryKey) {
                continue;
            }

            if ($request->has($column)) {
                $value = $request->input($column);

                $data[$column] = $value === '' ? null : $value;
            }
        }

        DB::table($table)
            ->where($primaryKey, $id)
            ->update($data);

        return redirect()
            ->route('database.table', $table)
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy($table, $id)
    {
        $this->validateTable($table);

        $primaryKey = $this->getPrimaryKey($table);

        if (!$primaryKey) {
            abort(400, 'Tabel ini tidak memiliki primary key.');
        }

        DB::table($table)
            ->where($primaryKey, $id)
            ->delete();

        return redirect()
            ->route('database.table', $table)
            ->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Pastikan tabel benar-benar ada
     */
    private function validateTable($table)
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            abort(404);
        }

        if (!Schema::hasTable($table)) {
            abort(404, 'Tabel tidak ditemukan.');
        }
    }

    /**
     * Ambil primary key tabel
     */
    private function getPrimaryKey($table)
    {
        $result = DB::select("
            SELECT a.attname AS column_name
            FROM pg_index i
            JOIN pg_attribute a
                ON a.attrelid = i.indrelid
                AND a.attnum = ANY(i.indkey)
            WHERE i.indrelid = ?::regclass
            AND i.indisprimary = true
            LIMIT 1
        ", [$table]);

        return $result[0]->column_name ?? null;
    }
}