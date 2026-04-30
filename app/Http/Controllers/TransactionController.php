<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $cart = $request->cart;

        if (!$cart || count($cart) === 0) {
            return response()->json([
                'message' => 'Cart kosong'
            ], 400);
        }

        DB::beginTransaction();

        try {

            // =========================
            // HITUNG TOTAL
            // =========================
            $subtotal = 0;

            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }

            $tax = round($subtotal * 0.10);
            $total = $subtotal + $tax;

            // =========================
            // GENERATE CODE
            // =========================
            $orderNumber = 'ORD-' . now()->format('YmdHis');
            $kode = 'TRX-' . strtoupper(Str::random(6));

            // =========================
            // DATA USER
            // =========================
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User tidak login'
                ], 401);
            }

            // 🔥 pastikan field ini ada di tabel users
            $namaOutlet = $user->outlet ?? 'Pusat';

            // =========================
            // INSERT TRANSACTION
            // =========================
            $trxId = DB::table('transactions')->insertGetId([
                'user_id' => $user->id,
                'nama_outlet' => $namaOutlet,
                'order_number' => $orderNumber,
                'kode' => $kode,
                'kasir' => $user->name,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =========================
            // INSERT ITEMS
            // =========================
            foreach ($cart as $item) {

                $subtotalItem = $item['price'] * $item['qty'];

                DB::table('transaction_items')->insert([
                    'transaction_id' => $trxId,
                    'menu_name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotalItem, // 🔥 WAJIB (fix error kamu)
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil',
                'kode' => $kode
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Gagal transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function history()
        {
            $transactions = DB::table('transactions')
                ->select(
                    'transactions.id',
                    'transactions.nama_outlet',
                    'transactions.order_number',
                    'transactions.kasir',
                    'transactions.total',
                    'transactions.status',
                    'transactions.created_at',
                    DB::raw('COUNT(transaction_items.id) as items_count')
                )
                ->leftJoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->groupBy(
                    'transactions.id',
                    'transactions.nama_outlet',
                    'transactions.order_number',
                    'transactions.kasir',
                    'transactions.total',
                    'transactions.status',
                    'transactions.created_at'
                )
                ->orderBy('transactions.created_at', 'desc')
                ->paginate(10);

            return view('kasir.history', compact('transactions'));
        }
}