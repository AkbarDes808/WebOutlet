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

        // =========================
        // VALIDASI CART
        // =========================
        if (!$cart || count($cart) === 0) {

            return response()->json([
                'message' => 'Cart kosong'
            ], 400);
        }

        DB::beginTransaction();

        try {

            // =========================
            // USER LOGIN
            // =========================
            $user = auth()->user();

            if (!$user) {

                return response()->json([
                    'message' => 'User tidak login'
                ], 401);
            }

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
            // DATA OUTLET & KASIR
            // =========================
            $namaOutlet = $user->role;
            $kasirId = $user->id;

            // =========================
            // INSERT TRANSACTION
            // =========================
            $trxId = DB::table('transactions')->insertGetId([

                'user_id' => $user->id,
                'nama_outlet' => $namaOutlet,
                'order_number' => $orderNumber,
                'kode' => $kode,
                'kasir_id' => $kasirId,

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
                    'subtotal' => $subtotalItem,

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Transaksi berhasil',

                'kode' => $kode,

                'order_number' => $orderNumber,

                'outlet' => $namaOutlet,

                'kasir' => $user->name,

                'subtotal' => $subtotal,

                'tax' => $tax,

                'total' => $total,

                'created_at' => now()->format('d/m/Y H:i'),

                'items' => collect($cart)->map(function ($item) {

                    return [
                        'name' => $item['name'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['qty'],
                    ];

                })->values(),

            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' => 'Gagal transaksi',

                'error' => $e->getMessage()

            ], 500);
        }
    }

    public function history()
    {
        $user = auth()->user();

        $query = DB::table('transactions')

            ->leftJoin(
                'transaction_items',
                'transactions.id',
                '=',
                'transaction_items.transaction_id'
            )

            ->leftJoin(
                'users',
                'transactions.kasir_id',
                '=',
                'users.id'
            )

            ->select(
                'transactions.id',
                'transactions.nama_outlet',
                'transactions.order_number',
                'users.name as kasir',
                'transactions.total',
                'transactions.status',
                'transactions.created_at',

                DB::raw('COUNT(transaction_items.id) as items_count')
            );

        // =========================
        // FILTER BERDASARKAN ROLE
        // =========================
        if (
            $user->role !== 'admin' &&
            $user->role !== 'SPV'
        ) {

            $query->where(
                'transactions.nama_outlet',
                $user->role
            );
        }

        $transactions = $query

            ->groupBy(
                'transactions.id',
                'transactions.nama_outlet',
                'transactions.order_number',
                'users.name',
                'transactions.total',
                'transactions.status',
                'transactions.created_at'
            )

            ->orderBy('transactions.created_at', 'desc')

            ->paginate(10);

        return view('kasir.history', compact('transactions'));
    }
}