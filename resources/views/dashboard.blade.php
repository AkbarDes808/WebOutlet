@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | FORMAT ANGKA INDONESIA
    |--------------------------------------------------------------------------
    | 12.5   -> 12,5
    | 12.00  -> 12
    | 1250.5 -> 1.250,5
    */
    function formatAngka($value)
    {
        if ($value === null) {
            return '0';
        }

        $angka = (float) $value;
        $formatted = number_format($angka, 2, ',', '.');

        return rtrim(rtrim($formatted, '0'), ',');
    }

    /*
    |--------------------------------------------------------------------------
    | USER & ROLE
    |--------------------------------------------------------------------------
    */
    $user = Auth::user();

    $role = strtolower(trim($user->role ?? ''));

    $isAdmin = $role === 'admin';
    $isSpv = $role === 'spv';
    $isOutletUser = str_contains($role, 'outlet');

    /*
    |--------------------------------------------------------------------------
    | OUTLET AKTIF
    |--------------------------------------------------------------------------
    */
    $selectedOutlet = $selectedOutlet ?? request('outlet');

    $displayOutlet = $selectedOutlet ?: 'Semua Outlet';

    /*
    |--------------------------------------------------------------------------
    | FIELD INVENTORY
    |--------------------------------------------------------------------------
    */
    $fields = [
        // Bahan lama
        'tepung_roti' => 'Tepung Roti',
        'tepung_bumbu' => 'Tepung Bumbu',
        'garam' => 'Garam',
        'bubuk_cabe' => 'Bubuk Cabe',
        'telur' => 'Telur',
        'gula' => 'Gula',
        'ayam' => 'Ayam',

        // Bahan & kemasan baru
        'tepung' => 'Tepung',
        'teh' => 'Teh',
        'beras' => 'Beras',
        'cup' => 'Cup',
        'kertas_chicken_kecil' => 'Kertas Chicken Kecil',
        'kertas_chicken_sedang' => 'Kertas Chicken Sedang',
        'kertas_chicken_besar' => 'Kertas Chicken Besar',
        'dus_chicken' => 'Dus Chicken',
        'dus_chicken_jumbo' => 'Dus Chicken Jumbo',
        'plastik_cup_isi_1' => 'Plastik Cup Isi 1',
        'plastik_cup_isi_2' => 'Plastik Cup Isi 2',
        'plastik_ayam_kecil' => 'Plastik Ayam Kecil',
        'plastik_sedang' => 'Plastik Sedang',
        'plastik_tanggung' => 'Plastik Tanggung',
        'plastik_besar' => 'Plastik Besar',
        'plastik_jumbo' => 'Plastik Jumbo',
    ];
@endphp

<div>

    {{-- =========================================================
         BANNER
         ========================================================= --}}
    <div class="w-full h-52">
        <img
            src="https://picsum.photos/1600/900"
            alt="Banner"
            class="object-cover w-full h-full"
        >
    </div>

    <div class="p-4 sm:p-8">

        {{-- =====================================================
             WELCOME
             ===================================================== --}}
        <h1 class="text-2xl font-semibold mb-6">
            Welcome back, {{ $user->name ?? 'User' }}!
        </h1>

        {{-- =====================================================
             MENU KARTU
             ===================================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            {{-- INVENTORY --}}
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

            {{-- MARINASI --}}
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

            {{-- HISTORY --}}
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

            {{-- OUTLETS --}}
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

        {{-- =====================================================
             INVENTORY TABLE
             ===================================================== --}}
        <div class="p-4 sm:p-6 bg-white rounded-xl shadow">

            {{-- HEADER --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

                <div>
                    <h2 class="text-lg font-semibold">
                        Inventory
                    </h2>

                    <p class="text-sm text-gray-500">
                        Stok bahan berdasarkan outlet
                    </p>
                </div>

                {{-- INFORMASI OUTLET USER --}}
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

            {{-- =================================================
                 FILTER OUTLET
                 ================================================= --}}
            @if(!$isOutletUser)

                <div class="bg-gray-50 p-4 rounded-lg border mb-6">

                    <form
                        method="GET"
                        action="{{ route('dashboard') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
                    >

                        {{-- OUTLET --}}
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

                        {{-- BUTTON FILTER --}}
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

            {{-- =================================================
                 INFO OUTLET AKTIF
                 ================================================= --}}
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

            {{-- =================================================
                 TABLE
                 ================================================= --}}
            <div class="overflow-x-auto">

                <table class="min-w-full text-sm text-left">

                    <thead class="bg-gray-100 uppercase">

                        <tr>

                            <th class="px-4 py-3">
                                Bahan
                            </th>

                            <th class="px-4 py-3 text-right">
                                Total Stok
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @if(isset($totalStok))

                            @foreach($fields as $key => $label)

                                <tr class="hover:bg-gray-50">

                                    {{-- NAMA BAHAN --}}
                                    <td class="px-4 py-3 font-medium">
                                        {{ $label }}
                                    </td>

                                    {{-- TOTAL STOK --}}
                                    <td class="px-4 py-3 text-right font-semibold">
                                        {{ formatAngka($totalStok->{$key} ?? 0) }}
                                    </td>

                                </tr>

                            @endforeach

                        @else

                            <tr>

                                <td
                                    colspan="2"
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