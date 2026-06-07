<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftClosing;

class ShiftClosingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('shift.index', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'tanggal'          => 'required',
            'waktu_mulai'      => 'required',
            'waktu_selesai'    => 'required',

            'total_transaksi'  => 'required|numeric',

            'total_penjualan'  => 'required|numeric',

            'cash_total'       => 'required|numeric',
            'cash_orders'      => 'required|numeric',

            'qris_total'       => 'required|numeric',
            'qris_orders'      => 'required|numeric',

            'expected_cash'    => 'required|numeric',

            'actual_cash'      => 'required|numeric',

            'catatan'          => 'nullable',
        ]);

        // =========================
        // HITUNG SELISIH
        // =========================
        $selisih =
            $request->actual_cash -
            $request->expected_cash;

        // =========================
        // SIMPAN DATABASE
        // =========================
        ShiftClosing::create([

            'user_id' => auth()->id(),

            'outlet' => auth()->user()->role,

            'kasir' => auth()->user()->name,

            'tanggal' => $request->tanggal,

            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,

            'total_transaksi' => $request->total_transaksi,

            'total_penjualan' => $request->total_penjualan,

            'cash_total' => $request->cash_total,
            'cash_orders' => $request->cash_orders,

            'qris_total' => $request->qris_total,
            'qris_orders' => $request->qris_orders,

            'expected_cash' => $request->expected_cash,

            'actual_cash' => $request->actual_cash,

            'selisih' => $selisih,

            'catatan' => $request->catatan,
        ]);

        return back()->with(
            'success',
            'Shift berhasil ditutup'
        );
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $query = ShiftClosing::query();

        // Admin & SPV bisa lihat semua outlet
        if (
            strtolower($user->role) !== 'admin' &&
            strtolower($user->role) !== 'spv'
        ) {
            // Outlet hanya lihat outlet miliknya
            $query->where('outlet', $user->role);
        }

        // Filter outlet hanya untuk admin/spv
        if (
            (strtolower($user->role) === 'admin' ||
            strtolower($user->role) === 'spv')
            && $request->filled('outlet')
        ) {
            $query->where('outlet', $request->outlet);
        }

        // Filter tanggal
        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        // Filter kasir
        if ($request->filled('kasir')) {
            $query->where('kasir', $request->kasir);
        }

        $shiftClosings = $query
            ->latest()
            ->paginate(10);

        // Dropdown outlet hanya untuk admin/spv
        $outlets = collect();

        if (
            strtolower($user->role) === 'admin' ||
            strtolower($user->role) === 'spv'
        ) {
            $outlets = ShiftClosing::select('outlet')
                ->distinct()
                ->pluck('outlet');
        }

        $kasirs = ShiftClosing::select('kasir')
            ->distinct()
            ->pluck('kasir');

        return view('Shift.history', compact(
            'shiftClosings',
            'outlets',
            'kasirs'
        ));
    }
}