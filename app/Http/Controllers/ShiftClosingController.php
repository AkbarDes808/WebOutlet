<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftClosing;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ShiftClosingController extends Controller
{
    private function getOutlet($user)
    {
        return strtolower(trim($user->role));
    }

    private function baseQuery($today, $outlet)
    {
        return Transaction::whereDate('created_at', $today)
            ->whereRaw('LOWER(TRIM(nama_outlet)) = ?', [$outlet]);
    }

    public function index()
    {
        $user = auth()->user();

        $today = now()->toDateString();
        $outlet = $this->getOutlet($user);

        $cashTotal = $this->baseQuery($today, $outlet)
            ->whereRaw('LOWER(payment_method) = ?', ['cash'])
            ->sum('total');

        $qrisTotal = $this->baseQuery($today, $outlet)
            ->whereRaw('LOWER(payment_method) = ?', ['qris'])
            ->sum('total');

        $totalPenjualan = $this->baseQuery($today, $outlet)
            ->sum('total');

        $totalTransaksi = $this->baseQuery($today, $outlet)
            ->count();

        $cashOrders = $this->baseQuery($today, $outlet)
            ->whereRaw('LOWER(payment_method) = ?', ['cash'])
            ->count();

        $qrisOrders = $this->baseQuery($today, $outlet)
            ->whereRaw('LOWER(payment_method) = ?', ['qris'])
            ->count();

        $actualCash = 0;

        return view('shift.index', compact(
            'user',
            'cashTotal',
            'qrisTotal',
            'totalPenjualan',
            'totalTransaksi',
            'cashOrders',
            'qrisOrders',
            'actualCash'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $today = now()->toDateString();
        $outlet = $this->getOutlet($user);

        $alreadyClosed = ShiftClosing::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($alreadyClosed) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        }

        DB::beginTransaction();

        try {

            $cashTotal = $this->baseQuery($today, $outlet)
                ->whereRaw('LOWER(payment_method) = ?', ['cash'])
                ->sum('total');

            $qrisTotal = $this->baseQuery($today, $outlet)
                ->whereRaw('LOWER(payment_method) = ?', ['qris'])
                ->sum('total');

            $totalPenjualan = $this->baseQuery($today, $outlet)
                ->sum('total');

            $totalTransaksi = $this->baseQuery($today, $outlet)
                ->count();

            $cashOrders = $this->baseQuery($today, $outlet)
                ->whereRaw('LOWER(payment_method) = ?', ['cash'])
                ->count();

            $qrisOrders = $this->baseQuery($today, $outlet)
                ->whereRaw('LOWER(payment_method) = ?', ['qris'])
                ->count();

            $uangModal = (float) ($request->uang_modal ?? 0);

            $pengeluaranLainnya = (float) ($request->pengeluaran_lainnya ?? 0);


            // TOTAL KEMBALIAN CASH
            $totalKembalian = $this->baseQuery($today, $outlet)
                ->whereRaw('LOWER(payment_method) = ?', ['cash'])
                ->sum('change_amount');


            // CASH DRAWER
            // Modal + Cash Masuk - Kembalian - Pengeluaran
            $actualCash =
                $uangModal +
                $cashTotal -
                $totalKembalian -
                $pengeluaranLainnya;

            $selisih =
                $actualCash -
                $cashTotal;


            ShiftClosing::create([

                'user_id' => $user->id,
                'outlet' => $outlet,
                'kasir' => $user->name,

                'tanggal' => $today,
                'waktu_mulai' => $user->shift_started_at
                    ? $user->shift_started_at->format('H:i:s')
                    : now()->format('H:i:s'),
                'waktu_selesai' => now()->format('H:i:s'),

                'total_transaksi' => $totalTransaksi,
                'total_penjualan' => $totalPenjualan,

                'cash_total' => $cashTotal,
                'cash_orders' => $cashOrders,

                'qris_total' => $qrisTotal,
                'qris_orders' => $qrisOrders,

                'uang_modal' => $uangModal,
                'pengeluaran_lainnya' => $pengeluaranLainnya,
                'total_kembalian' => $totalKembalian,
                'actual_cash' => $actualCash,
                'selisih' => $selisih,

                'catatan' => $request->catatan,

            ]);

            DB::commit();

            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Gagal menutup shift: '.$e->getMessage()
            );
        }
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $query = ShiftClosing::query();

        if (!in_array(strtolower($user->role), ['admin', 'spv'])) {
            $query->where(
                'outlet',
                $this->getOutlet($user)
            );
        }

        if ($request->outlet) {
            $query->where('outlet', $request->outlet);
        }

        if ($request->kasir) {
            $query->where('kasir', $request->kasir);
        }

        if ($request->from && $request->to) {
            $query->whereBetween(
                'tanggal',
                [
                    $request->from,
                    $request->to
                ]
            );
        }

        $shiftClosings = $query
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $outlets = ShiftClosing::select('outlet')
            ->distinct()
            ->pluck('outlet');

        $kasirs = ShiftClosing::select('kasir')
            ->distinct()
            ->pluck('kasir');

        return view('shift.history', compact(
            'shiftClosings',
            'outlets',
            'kasirs',
            'user'
        ));
    }
}