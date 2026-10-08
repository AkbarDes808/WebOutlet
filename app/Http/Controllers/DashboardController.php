<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private array $fields = [
        'tepung_roti',
        'tepung_bumbu',
        'bubuk_cabe',
        'telur',
        'gula',
        'ayam',
        'tepung',
        'teh',
        'beras',
        'cup',
        'kertas_chicken_kecil',
        'kertas_chicken_sedang',
        'kertas_chicken_besar',
        'dus_chicken',
        'plastik_cup_isi_1',
        'plastik_cup_isi_2',
        'plastik_ayam_kecil',
        'plastik_sedang',
        'plastik_tanggung',
        'plastik_besar',
        'plastik_jumbo',
    ];

    private array $outletMapping = [
        'outlet 1' => 'Outlet 1',
        'outlet 2' => 'Outlet 2',
        'outlet 3' => 'Outlet 3',
        'outlet 4' => 'Outlet 4',
        'outlet 5' => 'Outlet 5',
        'outlet 6' => 'Outlet 6',
        'outlet 7' => 'Outlet 7',
        'outlet 8' => 'Event 1',
        'outlet 9' => 'Event 2',
        'outlet 10' => 'Event',
    ];

    private array $dashboardItems = [
        'Ayam',
        'Tepung',
        'Teh Kotak',
        'Nasi',
        'Plastik Sedang',
        'Dus',
    ];

    private array $stockNameAliases = [
        'teh' => 'teh kotak',
        'beras' => 'nasi',
        'dus chicken' => 'dus',
        'saus cabe' => 'saos cabe',
        'saus sambal sachet' => 'saos cabe',
        'cabe' => 'kantong sambal',
    ];

    public function index(Request $request)
    {
        $activeTab = 'inventory';
        $user = Auth::user();

        $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $role = strtolower(trim($user->role ?? ''));

        $isOutletUser = str_starts_with($role, 'outlet ');

        if ($isOutletUser) {
            $filterOutlet = $this->outletMapping[$role] ?? null;

            $outlets = $filterOutlet
                ? collect([$filterOutlet])
                : collect();
        } else {
            $filterOutlet = $this->normalizeOutlet(
                $request->input('outlet')
            );

            $outlets = collect(array_values($this->outletMapping));
        }

        $viewData = [
            'outlets' => $outlets,
            'activeTab' => $activeTab,
            'user' => $user,
            'totalStok' => [],
            'totalSemuaOutlet' => [],
            'history' => collect(),
            'selectedOutlet' => $filterOutlet,
            'isOutletUser' => $isOutletUser,
            'stockItems' => collect(),
            'stockOutletRows' => collect(),
            'dashboardItems' => collect(),
        ];

        $inventoryData = $this->getInventoryData($filterOutlet);

        $viewData['stockItems'] = $inventoryData['stockItems'];
        $viewData['stockOutletRows'] = $inventoryData['stockOutletRows'];
        $viewData['totalStok'] = $inventoryData['totalStok'];
        $viewData['totalSemuaOutlet'] = $inventoryData['totalSemuaOutlet'];
        $viewData['dashboardItems'] = $inventoryData['dashboardItems'];

        return view('dashboard', $viewData);
    }

    private function getInventoryData(?string $filterOutlet): array
    {
        $stockItems = DB::table('stock_items')
            ->where('aktif', true)
            ->orderBy('id')
            ->get();

        $stockItemsByName = $stockItems->keyBy(
            fn ($item) => $this->normalizeName($item->nama)
        );

        $selectedOutlet = $filterOutlet
            ? strtolower(trim($filterOutlet))
            : null;

        $stockOutletRows = collect();

        if ($selectedOutlet) {
            $stockOutletRows = DB::table('stock_item_outlets')
                ->whereRaw(
                    'LOWER(TRIM(outlet)) = ?',
                    [$selectedOutlet]
                )
                ->get()
                ->groupBy('stock_item_id');
        }

        $allOutletStocks = DB::table('stock_item_outlets')
            ->get()
            ->groupBy('stock_item_id');

        $totalStok = [];
        $totalSemuaOutlet = [];

        foreach ($stockItems as $stockItem) {
            $stockId = $stockItem->id;

            $totalStok[$stockId] = 0;

            if ($stockOutletRows->has($stockId)) {
                $totalStok[$stockId] = $stockOutletRows
                    ->get($stockId)
                    ->sum(fn ($row) => (float) ($row->stok ?? 0));
            }

            $totalSemuaOutlet[$stockId] = 0;

            if ($allOutletStocks->has($stockId)) {
                foreach ($allOutletStocks->get($stockId) as $stockRow) {
                    $totalSemuaOutlet[$stockId] += (float) (
                        $stockRow->stok ?? 0
                    );
                }
            }
        }

        // Dashboard mengikuti item yang ditampilkan pada halaman Stok.
        $legacyStok = 0;

        if ($selectedOutlet) {
            $legacyBahan = Bahan::query()
                ->whereRaw('LOWER(TRIM(nama_outlet)) = ?', [$selectedOutlet])
                ->orderByDesc('id')
                ->first();

            $legacyStok = (float) ($legacyBahan->plastik_sedang ?? 0);
        } else {
            $legacyStok = (float) Bahan::query()->sum('plastik_sedang');
        }

        $dashboardItems = collect([
            'Ayam' => ['kategori' => 'Bagian Ayam'],
            'Teh Kotak' => ['kategori' => 'Bahan'],
            'Plastik Sedang' => ['kategori' => 'Bahan', 'legacy' => true],
            'Kantong Sambal' => ['kategori' => 'Bahan'],
            'Saos Cabe' => ['kategori' => 'Menu Tambahan'],
            'Kertas Ayam' => ['kategori' => 'Menu Gratis'],
            'Dus' => ['kategori' => 'Menu Gratis'],
            'Plastik Kecil' => ['kategori' => 'Menu Gratis'],
            'Plastik Sedang (Gratis)' => ['nama' => 'Plastik Sedang', 'kategori' => 'Menu Gratis'],
        ])->map(function ($config, $key) use ($stockItems, $selectedOutlet, $totalStok, $totalSemuaOutlet, $legacyStok) {
            $name = $config['nama'] ?? $key;

            if (!empty($config['legacy'])) {
                return (object) [
                    'id' => null,
                    'nama' => $name,
                    'kategori' => $config['kategori'],
                    'satuan' => 'stok',
                    'stok' => $legacyStok,
                ];
            }

            $stockItem = $stockItems->first(
                fn ($item) => $this->normalizeName($item->nama) === $this->normalizeName($name)
            );

            if (!$stockItem) {
                return null;
            }

            $stockId = $stockItem->id;
            $stok = $selectedOutlet
                ? (float) ($totalStok[$stockId] ?? 0)
                : (float) ($totalSemuaOutlet[$stockId] ?? 0);

            return (object) [
                'id' => $stockId,
                'nama' => $name,
                'kategori' => $config['kategori'],
                'satuan' => $stockItem->satuan ?? 'pcs',
                'stok' => $stok,
            ];
        })->filter()->values();

        return [
            'stockItems' => $stockItems,
            'stockOutletRows' => $stockOutletRows,
            'totalStok' => $totalStok,
            'totalSemuaOutlet' => $totalSemuaOutlet,
            'dashboardItems' => $dashboardItems,
        ];
    }

    private function getLegacyStocks(?string $filterOutlet): array
    {
        $result = [];

        foreach ($this->dashboardItems as $name) {
            if (!isset($this->legacyFieldMapping[$name])) {
                continue;
            }

            $field = $this->legacyFieldMapping[$name];

            $query = Bahan::query();

            if ($filterOutlet) {
                $query->where(
                    'nama_outlet',
                    $filterOutlet
                );
            }

            $latestRows = $query
                ->orderBy('nama_outlet')
                ->orderByDesc('id')
                ->get()
                ->groupBy('nama_outlet')
                ->map(function ($rows) {
                    return $rows->first();
                });

            if ($filterOutlet) {
                $latest = $latestRows->first();

                $result[$name] = $latest
                    ? (float) ($latest->{$field} ?? 0)
                    : 0;

                continue;
            }

            $result[$name] = $latestRows->sum(
                fn ($row) => (float) ($row->{$field} ?? 0)
            );
        }

        return $result;
    }

    private function getHistory(
        ?string $filterOutlet,
        ?string $startDate,
        ?string $endDate
    ) {
        $history = collect();

        $legacyHistory = $this->getLegacyHistory(
            $filterOutlet,
            $startDate,
            $endDate
        );

        foreach ($legacyHistory as $item) {
            $history->push($item);
        }

        $deductionHistory = $this->getStockDeductionHistory(
            $filterOutlet,
            $startDate,
            $endDate
        );

        foreach ($deductionHistory as $item) {
            $history->push($item);
        }

        return $history
            ->sortByDesc('created_at')
            ->values();
    }

    private function getLegacyHistory(
        ?string $filterOutlet,
        ?string $startDate,
        ?string $endDate
    ) {
        $historyQuery = Bahan::query()
            ->orderBy('created_at', 'asc');

        if ($filterOutlet) {
            $historyQuery->where(
                'nama_outlet',
                $filterOutlet
            );
        }

        if ($startDate && $endDate) {
            $historyQuery->whereBetween(
                'created_at',
                [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ]
            );
        } elseif ($startDate) {
            $historyQuery->where(
                'created_at',
                '>=',
                $startDate . ' 00:00:00'
            );
        } elseif ($endDate) {
            $historyQuery->where(
                'created_at',
                '<=',
                $endDate . ' 23:59:59'
            );
        }

        $rawHistory = $historyQuery->get();

        $processedHistory = collect();

        $lastStateByOutlet = [];

        foreach ($rawHistory as $row) {
            $outlet = $row->nama_outlet;

            $prev = $lastStateByOutlet[$outlet] ?? null;

            $changes = [];

            foreach ($this->fields as $field) {
                $curr = (float) ($row->{$field} ?? 0);

                $prevVal = $prev
                    ? (float) ($prev->{$field} ?? 0)
                    : 0.0;

                $changes[$field] = (object) [
                    'total' => $curr,
                    'change' => $curr - $prevVal,
                ];
            }

            $processedHistory->push(
                (object) [
                    'created_at' => $row->created_at,
                    'nama_outlet' => $outlet,
                    'data' => $changes,
                    'type' => 'Penambahan',
                    'source' => 'bahans',
                ]
            );

            $lastStateByOutlet[$outlet] = $row;
        }

        return $processedHistory;
    }

    private function getStockDeductionHistory(
        ?string $filterOutlet,
        ?string $startDate,
        ?string $endDate
    ) {
        if (!DB::getSchemaBuilder()->hasTable('stock_deductions')) {
            return collect();
        }

        $query = DB::table('stock_deductions as sd')
            ->leftJoin(
                'stock_items as si',
                'si.id',
                '=',
                'sd.stock_item_id'
            )
            ->select(
                'sd.*',
                'si.nama as stock_item_nama'
            )
            ->orderByDesc('sd.created_at');

        if ($filterOutlet) {
            $query->whereRaw(
                'LOWER(TRIM(sd.outlet)) = ?',
                [strtolower(trim($filterOutlet))]
            );
        }

        if ($startDate && $endDate) {
            $query->whereBetween(
                'sd.created_at',
                [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ]
            );
        } elseif ($startDate) {
            $query->where(
                'sd.created_at',
                '>=',
                $startDate . ' 00:00:00'
            );
        } elseif ($endDate) {
            $query->where(
                'sd.created_at',
                '<=',
                $endDate . ' 23:59:59'
            );
        }

        $rows = $query->get();

        return $rows->map(function ($row) {
            $itemName = $row->stock_item_nama
                ?? 'Stock Item #' . $row->stock_item_id;

            $quantity = (float) (
                $row->jumlah
                ?? $row->qty
                ?? $row->quantity
                ?? $row->stok
                ?? 0
            );

            return (object) [
                'created_at' => $row->created_at,
                'nama_outlet' => $this->displayOutlet(
                    $row->outlet ?? null
                ),
                'type' => 'Penggunaan',
                'source' => 'stock_deductions',
                'stock_item_id' => $row->stock_item_id ?? null,
                'stock_item_nama' => $itemName,
                'quantity' => $quantity,
                'data' => [
                    $this->makeHistoryKey($itemName) => (object) [
                        'total' => 0,
                        'change' => -$quantity,
                    ],
                ],
            ];
        });
    }

    private function normalizeOutlet(?string $outlet): ?string
    {
        if (!$outlet) {
            return null;
        }

        $normalized = strtolower(trim($outlet));

        foreach ($this->outletMapping as $role => $displayName) {
            if ($normalized === strtolower($role)) {
                return $displayName;
            }

            if ($normalized === strtolower($displayName)) {
                return $displayName;
            }
        }

        return null;
    }

    private function displayOutlet(?string $outlet): ?string
    {
        if (!$outlet) {
            return null;
        }

        $normalized = strtolower(trim($outlet));

        foreach ($this->outletMapping as $role => $displayName) {
            if (
                $normalized === strtolower($role) ||
                $normalized === strtolower($displayName)
            ) {
                return $displayName;
            }
        }

        return $outlet;
    }

    private function normalizeName(?string $name): string
    {
        return strtolower(
            preg_replace(
                '/\s+/',
                ' ',
                trim($name ?? '')
            )
        );
    }

    private function makeHistoryKey(string $name): string
    {
        $key = strtolower(trim($name));

        $key = preg_replace(
            '/[^a-z0-9]+/',
            '_',
            $key
        );

        return trim($key, '_');
    }
}