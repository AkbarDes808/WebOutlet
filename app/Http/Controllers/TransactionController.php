<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(
        Request $request,
        StockService $stockService
    ) {
        $cart = $request->cart;
        $paymentMethod = $request->payment_method ?? 'cash';
        $paymentAmount = (int) ($request->payment_amount ?? 0);

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
                DB::rollBack();

                return response()->json([
                    'message' => 'User tidak login'
                ], 401);
            }

            // =========================
            // HITUNG TOTAL
            // =========================

            $subtotal = 0;

            foreach ($cart as $item) {

                $subtotal +=
                    ((int) $item['price']) *
                    ((int) $item['qty']);
            }

            $tax = 0;
            $total = $subtotal + $tax;

            // =========================
            // GENERATE CODE
            // =========================

            $orderNumber = 'ORD-' . now()->format('YmdHis');

            $kode = 'TRX-' . strtoupper(
                Str::random(6)
            );

            // =========================
            // DATA OUTLET & KASIR
            // =========================

            /*
             * Role user digunakan sebagai nama outlet.
             *
             * Contoh:
             * outlet 1 -> stok outlet 1
             * outlet 2 -> stok outlet 2
             * outlet 3 -> stok outlet 3
             */

            $role = strtolower(trim($user->role ?? ''));
            $outletMapping = [
                'outlet 1' => 'Outlet 1',
                'outlet 2' => 'Outlet 2',
                'outlet 3' => 'Outlet 3',
                'outlet 4' => 'Outlet 4',
                'outlet 5' => 'Outlet 5',
                'outlet 6' => 'Outlet 6',
                'outlet 7' => 'Outlet 7',
            ];

            if ($role === 'admin' || $role === 'spv') {

                $namaOutlet = trim((string) $request->input('outlet'));

                if ($namaOutlet === '') {
                    throw new \Exception(
                        'Outlet belum dipilih. Silakan pilih outlet terlebih dahulu.'
                    );
                }

                if (!in_array($namaOutlet, array_values($outletMapping), true)) {
                    throw new \Exception(
                        'Outlet transaksi tidak valid: ' . $namaOutlet
                    );
                }

            } elseif (isset($outletMapping[$role])) {

                $namaOutlet = $outletMapping[$role];

            } else {

                throw new \Exception(
                    'Role user tidak memiliki outlet yang valid: ' . $user->role
                );
            }
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

                'payment_method' => $paymentMethod,

                'payment_amount' => $paymentAmount,

                'change_amount' => max(
                    0,
                    $paymentAmount - $total
                ),

                'status' => 'paid',

                'created_at' => now(),

                'updated_at' => now(),
            ]);

            // =========================
            // INSERT TRANSACTION ITEMS
            // =========================

            foreach ($cart as $item) {

                $subtotalItem =
                    ((int) $item['price']) *
                    ((int) $item['qty']);

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

            // =========================
            // POTONG STOK SESUAI OUTLET
            // =========================
            //
            // Contoh:
            //
            // User = outlet 1
            // Maka StockService hanya akan
            // mengurangi stok pada:
            //
            // stock_item_outlets
            // outlet = outlet 1
            //
            // Bukan stok outlet lainnya.
            //
            // Jika stok tidak cukup atau resep
            // belum tersedia, exception akan terjadi
            // dan transaksi akan di-rollback.
            //

            $stockService->deductForTransaction(
                $trxId,
                $cart,
                $namaOutlet
            );

            // =========================
            // COMMIT
            // =========================

            DB::commit();

            // =========================
            // RESPONSE
            // =========================

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

                'payment_amount' => $paymentAmount,

                'change_amount' => max(
                    0,
                    $paymentAmount - $total
                ),

                'payment_method' => $paymentMethod,

                'created_at' => now()->format(
                    'd/m/Y H:i'
                ),

                'items' => collect($cart)
                    ->map(function ($item) {

                        return [
                            'name' => $item['name'],
                            'qty' => $item['qty'],
                            'price' => $item['price'],
                            'subtotal' =>
                                $item['price'] *
                                $item['qty'],
                        ];

                    })
                    ->values(),
            ]);

        } catch (\Exception $e) {

            // =========================
            // ROLLBACK
            // =========================

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' => 'Gagal transaksi',

                'error' => $e->getMessage(),

            ], 500);
        }
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $query = DB::table('transactions')
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
                'transactions.payment_method',
                'transactions.created_at'
            )
            ->addSelect(DB::raw('(
                SELECT COUNT(*)
                FROM transaction_items
                WHERE transaction_items.transaction_id = transactions.id
            ) as items_count'));

        // =========================
        // FILTER ROLE OUTLET
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

        // =========================
        // FILTER OUTLET
        // =========================

        if ($request->filled('outlet')) {

            $query->where(
                'transactions.nama_outlet',
                $request->outlet
            );
        }

        // =========================
        // FILTER DATE FROM - TO
        // =========================

        if ($request->filled('from')) {

            $query->whereDate(
                'transactions.created_at',
                '>=',
                $request->from
            );
        }

        if ($request->filled('to')) {

            $query->whereDate(
                'transactions.created_at',
                '<=',
                $request->to
            );
        }

        // =========================
        // FILTER STATUS
        // =========================

        if ($request->filled('status')) {

            $query->where(
                'transactions.status',
                $request->status
            );
        }

        // =========================
        // FILTER PAYMENT METHOD
        // =========================

        if ($request->filled('payment_method')) {

            $query->where(
                'transactions.payment_method',
                $request->payment_method
            );
        }

        // =========================
        // DROPDOWN OUTLET
        // =========================

        $outlets = DB::table('transactions')
            ->select('nama_outlet')
            ->distinct()
            ->orderBy('nama_outlet')
            ->pluck('nama_outlet');

        // =========================
        // RESULT
        // =========================

        $transactions = $query
            ->orderBy(
                'transactions.created_at',
                'desc'
            )
            ->paginate(10)
            ->withQueryString();

        return view(
            'kasir.history',
            compact(
                'transactions',
                'outlets'
            )
        );
    }

    public function detail($id)
    {
        $trx = DB::table('transactions')
            ->leftJoin(
                'users',
                'transactions.kasir_id',
                '=',
                'users.id'
            )
            ->select(
                'transactions.id',
                'transactions.order_number',
                'transactions.nama_outlet',
                'transactions.payment_method',
                'transactions.total',
                'transactions.payment_amount',
                'transactions.change_amount',
                'users.name as kasir_name'
            )
            ->where(
                'transactions.id',
                $id
            )
            ->first();

        if (!$trx) {

            return response()->json([
                'success' => false
            ], 404);
        }

        $items = DB::table('transaction_items')
            ->where(
                'transaction_id',
                $id
            )
            ->get();

        return response()->json([

            'success' => true,

            'trx' => [

                'order_number' =>
                    $trx->order_number,

                'nama_outlet' =>
                    $trx->nama_outlet,

                'payment_method' =>
                    $trx->payment_method,

                'kasir_name' =>
                    $trx->kasir_name,

                'total' =>
                    $trx->total,

                'payment_amount' =>
                    $trx->payment_amount,

                'change_amount' =>
                    $trx->change_amount,
            ],

            'items' => $items
        ]);
    }
}