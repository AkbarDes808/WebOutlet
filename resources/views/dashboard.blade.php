@extends('layouts.app')

@section('content')

@php
    function formatAngka($value)
    {
        if ($value === null || $value === '') {
            return '0';
        }

        $angka = (float) $value;

        return rtrim(
            rtrim(
                number_format($angka, 2, ',', '.'),
                '0'
            ),
            ','
        );
    }

    $user = Auth::user();
    $role = strtolower(trim($user->role ?? ''));

    $isAdmin = $role === 'admin';
    $isSpv = $role === 'spv';
    $isOutletUser = str_contains($role, 'outlet');

    $selectedOutlet = $selectedOutlet ?? request('outlet');
    $displayOutlet = $selectedOutlet ?: 'Semua Outlet';

    $chickenNames = [
        'ayam - sayap',
        'ayam - paha bawah',
        'ayam - dada',
        'ayam - paha atas',
    ];

    $otherItems = [
        'tepung' => 'Tepung',
        'teh' => 'Teh',
        'beras' => 'Beras',
        'plastik_sedang' => 'Plastik Sedang',
        'dus_chicken' => 'Dus Chicken',
    ];

    $chickenStockItems = collect();

    if (isset($stockItems)) {
        $chickenStockItems = collect($stockItems)
            ->filter(function ($stockItem) use ($chickenNames) {
                return in_array(
                    strtolower(trim($stockItem->nama ?? '')),
                    $chickenNames,
                    true
                );
            })
            ->sortBy(function ($stockItem) use ($chickenNames) {
                return array_search(
                    strtolower(trim($stockItem->nama ?? '')),
                    $chickenNames
                );
            })
            ->values();
    }

    $getStock = function ($id) use ($totalStok) {
        if (is_array($totalStok ?? null)) {
            return $totalStok[$id] ?? 0;
        }

        if (is_object($totalStok ?? null)) {
            return $totalStok->{$id} ?? 0;
        }

        return 0;
    };
@endphp

<div>

    <div class="w-full h-52">
        <img
            src="https://picsum.photos/1600/900"
            alt="Banner"
            class="object-cover w-full h-full"
        >
    </div>

    <div class="p-4 sm:p-8">

        <h1 class="text-2xl font-semibold mb-6">
            Welcome back, {{ $user->name ?? 'User' }}!
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <a
                href="{{ route('bahans.index') }}"
                class="p-6 bg-white shadow rounded-xl hover:shadow-md transition"
            >
                <div class="text-3xl mb-3">
                    📋
                </div>

                <h2 class="font-semibold">
                    Inventory
                </h2>

                <p class="text-sm text-gray-500">
                    Ubah stok bahan
                </p>
            </a>

            @if($isAdmin)
                <a
                    href="{{ route('marinasi.index') }}"
                    class="p-6 bg-white shadow rounded-xl hover:shadow-md transition"
                >
                    <div class="text-3xl mb-3">
                        🍗
                    </div>

                    <h2 class="font-semibold">
                        Marinasi
                    </h2>

                    <p class="text-sm text-gray-500">
                        Halaman marinasi
                    </p>
                </a>
            @endif

            <a
                href="{{ route('bahans.history') }}"
                class="p-6 bg-white shadow rounded-xl hover:shadow-md transition"
            >
                <div class="text-3xl mb-3">
                    📜
                </div>

                <h2 class="font-semibold">
                    History
                </h2>

                <p class="text-sm text-gray-500">
                    Riwayat perubahan stok
                </p>
            </a>

            @if(!$isOutletUser)
                <a
                    href="{{ route('outlets.index') }}"
                    class="p-6 bg-white shadow rounded-xl hover:shadow-md transition"
                >
                    <div class="text-3xl mb-3">
                        🏪
                    </div>

                    <h2 class="font-semibold">
                        Outlets
                    </h2>

                    <p class="text-sm text-gray-500">
                        Daftar outlet
                    </p>
                </a>
            @endif

        </div>

        <div class="p-4 sm:p-6 bg-white rounded-xl shadow">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

                <div>
                    <h2 class="text-lg font-semibold">
                        Inventory
                    </h2>

                    <p class="text-sm text-gray-500">
                        Stok bahan berdasarkan outlet
                    </p>
                </div>

                @if($isOutletUser)
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-sm">
                        <span>
                            🏪
                        </span>

                        <span class="font-medium">
                            {{ $displayOutlet }}
                        </span>
                    </div>
                @endif

            </div>

            @if(!$isOutletUser)

                <div class="bg-gray-50 p-4 rounded-lg border mb-6">

                    <form
                        method="GET"
                        action="{{ route('dashboard') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
                    >

                        <div class="md:col-span-3">

                            <label
                                for="outlet"
                                class="block text-sm font-medium mb-1"
                            >
                                Outlet
                            </label>

                            <select
                                id="outlet"
                                name="outlet"
                                class="w-full border-gray-300 rounded-lg focus:ring-gray-500 focus:border-gray-500"
                            >
                                <option value="">
                                    Semua Outlet
                                </option>

                                @foreach($outlets ?? [] as $outlet)
                                    <option
                                        value="{{ $outlet }}"
                                        {{ request('outlet') == $outlet ? 'selected' : '' }}
                                    >
                                        {{ $outlet }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div>

                            <button
                                type="submit"
                                class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition"
                            >
                                Filter
                            </button>

                        </div>

                    </form>

                </div>

            @endif

            <div class="mb-4">

                <div class="flex items-center justify-between">

                    <div class="text-sm text-gray-500">
                        Menampilkan stok:
                    </div>

                    <div class="font-semibold text-sm">
                        {{ $displayOutlet }}
                    </div>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm text-left">

                    <thead class="bg-gray-100 uppercase">

                        <tr>

                            <th class="px-4 py-3">
                                Bahan
                            </th>

                            <th class="px-4 py-3">
                                Satuan
                            </th>

                            <th class="px-4 py-3 text-right">
                                Sisa Stok
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @if($chickenStockItems->count() > 0)

                            @foreach($chickenStockItems as $chicken)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $chicken->nama }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $chicken->satuan ?? 'pcs' }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold">
                                        {{ formatAngka($getStock($chicken->id)) }}
                                    </td>

                                </tr>

                            @endforeach

                        @endif

                        @foreach($otherItems as $key => $label)

                            @php
                                $stok = 0;

                                if (isset($totalStok)) {
                                    if (is_array($totalStok)) {
                                        $stok = $totalStok[$key] ?? 0;
                                    } elseif (is_object($totalStok)) {
                                        $stok = $totalStok->{$key} ?? 0;
                                    }
                                }

                                $satuan = match ($key) {
                                    'garam' => 'gram',
                                    'tepung' => 'gram',
                                    'teh' => 'kotak',
                                    'beras' => 'porsi',
                                    'plastik_sedang' => 'pcs',
                                    'dus_chicken' => 'pcs',
                                    'dus_chicken_jumbo' => 'pcs',
                                    default => '-',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3 font-medium">
                                    {{ $label }}
                                </td>

                                <td class="px-4 py-3 text-gray-500">
                                    {{ $satuan }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ formatAngka($stok) }}
                                </td>

                            </tr>

                        @endforeach

                        @if($chickenStockItems->count() === 0 && count($otherItems) === 0)

                            <tr>

                                <td
                                    colspan="3"
                                    class="p-4 text-center text-gray-500"
                                >
                                    Tidak ada data stok.
                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection