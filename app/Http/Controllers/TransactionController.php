<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | OUTLET MAPPING
    |--------------------------------------------------------------------------
    */

    private array $outletMapping = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
        'outlet 8' => 'Outlet 8',
        'outlet 9' => 'Outlet 9',
        'outlet 10' => 'Outlet 10',
    ];

    /*
    |--------------------------------------------------------------------------
    | STORE TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        StockService $stockService
    ) {
        $cart =
            $request->input(
                'cart'
            );

        $paymentMethod =
            $request->input(
                'payment_method',
                'cash'
            );

        $paymentAmount =
            (int) $request->input(
                'payment_amount',
                0
            );

        /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */

        $event =
            trim(
                (string)
                $request->input(
                    'event',
                    ''
                )
            );

        $event =
            $event !== ''
                ? $event
                : null;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI CART
        |--------------------------------------------------------------------------
        */

        if (
            !$cart ||
            !is_array($cart) ||
            count($cart) === 0
        ) {
            return response()->json([
                'success' =>
                    false,
                'message' =>
                    'Cart kosong',
            ], 400);
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $user =
                auth()->user();

            if (!$user) {
                DB::rollBack();

                return response()->json([
                    'success' =>
                        false,
                    'message' =>
                        'User tidak login',
                ], 401);
            }

            /*
            |--------------------------------------------------------------------------
            | HITUNG SUBTOTAL
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            foreach ($cart as $item) {
                $price =
                    (int) (
                        $item['price']
                        ?? 0
                    );

                $qty =
                    (int) (
                        $item['qty']
                        ?? 0
                    );

                if ($qty <= 0) {
                    throw new \Exception(
                        'Jumlah item tidak valid.'
                    );
                }

                $subtotal +=
                    $price * $qty;
            }

            $tax = 0;

            $total =
                $subtotal + $tax;

            // Tolak transaksi jika total keranjang nol (menu gratis saja).
            if ($total <= 0) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Masukkan jumlah uang yang diterima.',
                ], 422);
            }

            $allowedPaymentMethods = ['cash', 'qris'];
            $paymentMethod = strtolower(trim((string) $paymentMethod));

            if (!in_array($paymentMethod, $allowedPaymentMethods, true)) {
                throw new \Exception('Metode pembayaran tidak valid.');
            }

            if ($paymentAmount < $total) {
                throw new \Exception(
                    'Pembayaran kurang. Total: Rp ' .
                    number_format($total, 0, ',', '.') .
                    ', dibayar: Rp ' .
                    number_format($paymentAmount, 0, ',', '.') . '.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CODE TRANSACTION
            |--------------------------------------------------------------------------
            */

            $orderNumber =
                'ORD-' .
                now()->format(
                    'YmdHis'
                ) .
                '-' .
                strtoupper(
                    Str::random(4)
                );

            $kode =
                'TRX-' .
                strtoupper(
                    Str::random(6)
                );

            /*
            |--------------------------------------------------------------------------
            | ROLE & OUTLET
            |--------------------------------------------------------------------------
            */

            $role =
                strtolower(
                    trim(
                        (string)
                        (
                            $user->role
                            ?? ''
                        )
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | ADMIN / SPV
            |
            | Bisa memilih outlet.
            |--------------------------------------------------------------------------
            */

            if ($role === 'admin') {
                $requestedOutlet =
                    strtolower(
                        trim(
                            (string)
                            $request->input(
                                'outlet',
                                ''
                            )
                        )
                    );

                if (
                    !isset(
                        $this->outletMapping[
                            $requestedOutlet
                        ]
                    )
                ) {
                    throw new \Exception(
                        'Outlet belum dipilih atau tidak valid.'
                    );
                }

                $namaOutlet =
                    $this->outletMapping[
                        $requestedOutlet
                    ];
            }

            /*
            |--------------------------------------------------------------------------
            | KASIR OUTLET
            |
            | Outlet 1 -> Outlet 1
            | Outlet 2 -> Outlet 2
            | dst.
            |--------------------------------------------------------------------------
            */

            elseif (
                isset(
                    $this->outletMapping[
                        $role
                    ]
                )
            ) {
                $namaOutlet =
                    $this->outletMapping[
                        $role
                    ];
            }

            else {
                throw new \Exception(
                    'Role user tidak memiliki outlet yang valid: ' .
                    ($user->role ?? '')
                );
            }

            $kasirId =
                $user->id;

            /*
            |--------------------------------------------------------------------------
            | INSERT TRANSACTION
            |--------------------------------------------------------------------------
            */

            $trxId =
                DB::table(
                    'transactions'
                )->insertGetId([
                    'user_id' =>
                        $user->id,

                    'nama_outlet' =>
                        $namaOutlet,

                    'event' =>
                        $event,

                    'order_number' =>
                        $orderNumber,

                    'kode' =>
                        $kode,

                    'kasir_id' =>
                        $kasirId,

                    'subtotal' =>
                        $subtotal,

                    'tax' =>
                        $tax,

                    'total' =>
                        $total,

                    'payment_method' =>
                        $paymentMethod,

                    'payment_amount' =>
                        $paymentAmount,

                    'change_amount' =>
                        max(
                            0,
                            $paymentAmount -
                            $total
                        ),

                    'status' =>
                        'paid',

                    'created_at' =>
                        now(),

                    'updated_at' =>
                        now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION ITEMS
            |--------------------------------------------------------------------------
            */

            foreach ($cart as $item) {
                $price =
                    (int) (
                        $item['price']
                        ?? 0
                    );

                $qty =
                    (int) (
                        $item['qty']
                        ?? 0
                    );

                $subtotalItem =
                    $price * $qty;

                DB::table(
                    'transaction_items'
                )->insert([
                    'transaction_id' =>
                        $trxId,

                    'menu_name' =>
                        $item['name'],

                    'price' =>
                        $price,

                    'qty' =>
                        $qty,

                    'subtotal' =>
                        $subtotalItem,

                    'created_at' =>
                        now(),

                    'updated_at' =>
                        now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | POTONG STOK
            |
            | Kalau stok 0:
            |
            | StockService akan throw exception.
            |
            | Karena masih dalam transaction,
            | transactions + transaction_items
            | ikut ROLLBACK.
            |--------------------------------------------------------------------------
            */

            $stockService
                ->deductForTransaction(
                    $trxId,
                    $cart,
                    $namaOutlet
                );

            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' =>
                    true,

                'message' =>
                    'Transaksi berhasil',

                'kode' =>
                    $kode,

                'order_number' =>
                    $orderNumber,

                'outlet' =>
                    $namaOutlet,

                'event' =>
                    $event,

                'kasir' =>
                    $user->name,

                'subtotal' =>
                    $subtotal,

                'tax' =>
                    $tax,

                'total' =>
                    $total,

                'payment_amount' =>
                    $paymentAmount,

                'change_amount' =>
                    max(
                        0,
                        $paymentAmount -
                        $total
                    ),

                'payment_method' =>
                    $paymentMethod,

                'created_at' =>
                    now()->format(
                        'd/m/Y H:i'
                    ),

                'items' =>
                    collect($cart)
                        ->map(
                            function ($item) {
                                return [
                                    'name' =>
                                        $item['name'],

                                    'qty' =>
                                        $item['qty'],

                                    'price' =>
                                        $item['price'],

                                    'subtotal' =>
                                        (
                                            $item['price'] *
                                            $item['qty']
                                        ),
                                ];
                            }
                        )
                        ->values(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return response()->json([
                'success' =>
                    false,

                'message' =>
                    'Gagal transaksi',

                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(
        Request $request
    ) {
        $user =
            auth()->user();

        $role =
            strtolower(
                trim(
                    (string)
                    (
                        $user->role
                        ?? ''
                    )
                )
            );

        $query =
            DB::table(
                'transactions'
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
                    'transactions.event',
                    'transactions.order_number',
                    'users.name as kasir',
                    'transactions.total',
                    'transactions.status',
                    'transactions.payment_method',
                    'transactions.created_at'
                )
                ->addSelect(
                    DB::raw(
                        '(
                            SELECT COUNT(*)
                            FROM transaction_items
                            WHERE transaction_items.transaction_id =
                                  transactions.id
                        ) as items_count'
                    )
                );

        /*
        |--------------------------------------------------------------------------
        | FILTER ROLE OUTLET
        |--------------------------------------------------------------------------
        */

        // Admin dan SPV dapat melihat seluruh transaksi.
        // User outlet hanya boleh melihat transaksi outlet miliknya.
        $isAdminOrSpv = in_array($role, ['admin', 'spv'], true);

        if (!$isAdminOrSpv) {
            if (
                !isset(
                    $this->outletMapping[
                        $role
                    ]
                )
            ) {
                abort(403);
            }

            $query->where(
                'transactions.nama_outlet',
                $this->outletMapping[
                    $role
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER OUTLET
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'outlet'
            )
        ) {
            $outlet =
                strtolower(
                    trim(
                        (string)
                        $request->input(
                            'outlet'
                        )
                    )
                );

            if (
                isset(
                    $this->outletMapping[
                        $outlet
                    ]
                )
            ) {
                $query->where(
                    'transactions.nama_outlet',
                    $this->outletMapping[
                        $outlet
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER DATE
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
        ]);

        if (
            $request->filled('from')
        ) {
            $query->whereDate(
                'transactions.created_at',
                '>=',
                $request->from
            );
        }

        if (
            $request->filled('to')
        ) {
            $query->whereDate(
                'transactions.created_at',
                '<=',
                $request->to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'status'
            )
        ) {
            $query->where(
                'transactions.status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHOD
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'payment_method'
            )
        ) {
            $query->where(
                'transactions.payment_method',
                $request->payment_method
            );
        }

        /*
        |--------------------------------------------------------------------------
        | OUTLET DROPDOWN
        |--------------------------------------------------------------------------
        */

        $outlets =
            DB::table(
                'transactions'
            )
                ->select(
                    'nama_outlet'
                )
                ->distinct()
                ->orderBy(
                    'nama_outlet'
                )
                ->pluck(
                    'nama_outlet'
                );

        /*
        |--------------------------------------------------------------------------
        | RESULT
        |--------------------------------------------------------------------------
        */

        $transactions =
            $query
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

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function receipt(int $id)
    {
        return $this->detail($id);
    }

    public function detail(
        int $id
    ) {
        $trx =
            DB::table(
                'transactions'
            )
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
                    'transactions.event',
                    'transactions.payment_method',
                    'transactions.status',
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
                'success' =>
                    false,
            ], 404);
        }

        $items =
            DB::table(
                'transaction_items'
            )
                ->where(
                    'transaction_id',
                    $id
                )
                ->get();

        return response()->json([
            'success' =>
                true,

            'trx' => [
                'order_number' =>
                    $trx->order_number,

                'nama_outlet' =>
                    $trx->nama_outlet,

                'event' =>
                    $trx->event,

                'payment_method' =>
                    $trx->payment_method,

                'status' =>
                    $trx->status,

                'kasir_name' =>
                    $trx->kasir_name,

                'total' =>
                    $trx->total,

                'payment_amount' =>
                    $trx->payment_amount,

                'change_amount' =>
                    $trx->change_amount,
            ],

            'items' =>
                $items,
        ]);
    }
}