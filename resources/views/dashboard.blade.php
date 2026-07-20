@extends('layouts.app')

@section('content')

@php
/**
 * FORMAT ANGKA INDONESIA (DESIMAL AMAN)
 * 12.5   -> 12,5
 * 12.00  -> 12
 * 1250.5 -> 1.250,5
 */
function formatAngka($value) {
    if ($value === null) return '0';

    $angka = (float) $value;
    $formatted = number_format($angka, 2, ',', '.');

    return rtrim(rtrim($formatted, '0'), ',');
}

$fields = [
    'tepung_roti'  => 'Tepung Roti',
    'tepung_bumbu' => 'Tepung Bumbu',
    'garam'        => 'Garam',
    'bubuk_cabe'   => 'Bubuk Cabe',
    'telur'        => 'Telur',
    'gula'         => 'Gula',
    'ayam'         => 'Ayam',
];
@endphp

<div>
    {{-- BANNER --}}
    <div class="w-full h-52">
        <img src="https://picsum.photos/1600/900"
             alt="Banner"
             class="object-cover w-full h-full">
    </div>

    <div class="p-4 sm:p-8">
        <h1 class="text-2xl font-semibold mb-6">
            Welcome back, {{ Auth::user()->name ?? 'User' }}!
        </h1>

        {{-- MENU KARTU --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            {{-- INVENTORY --}}
            <a href="{{ route('bahans.index') }}"
               class="p-6 bg-white shadow rounded-xl hover:shadow-md transition">
                <div class="text-3xl mb-3">📋</div>
                <h2 class="font-semibold">Inventory</h2>
                <p class="text-sm text-gray-500">Ubah stok bahan</p>
            </a>

            {{-- MARINASI --}}
            @if (in_array(strtolower(Auth::user()->role), ['admin']))
            <a href="{{ route('marinasi.index') }}"
               class="p-6 bg-white shadow rounded-xl hover:shadow-md transition">
                <div class="text-3xl mb-3">🍗</div>
                <h2 class="font-semibold">Marinasi</h2>
                <p class="text-sm text-gray-500">Halaman marinasi</p>
            </a>
            @endif

            {{-- HISTORY --}}
            <a href="{{ route('bahans.history') }}"
               class="p-6 bg-white shadow rounded-xl hover:shadow-md transition">
                <div class="text-3xl mb-3">📜</div>
                <h2 class="font-semibold">History</h2>
                <p class="text-sm text-gray-500">Riwayat perubahan stok</p>
            </a>

            {{-- OUTLETS --}}
            @if(!str_contains(strtolower(Auth::user()->role), 'outlet'))

            <a href="{{ route('outlets.index') }}"
            class="p-6 bg-white shadow rounded-xl hover:shadow-md transition">
                <div class="text-3xl mb-3">🏪</div>
                <h2 class="font-semibold">
                    Outlets
                </h2>
                <p class="text-sm text-gray-500">
                    Daftar outlet
                </p>
            </a>
            @endif
        </div>

        {{-- INVENTORY TABLE --}}
        <div class="p-4 sm:p-6 bg-white rounded-xl shadow">

            {{-- FILTER --}}
            @if(Auth::user()->role !== 'outlet')
            <div class="bg-gray-50 p-4 rounded-lg border mb-6">
                <form method="GET" action="{{ route('dashboard') }}"
                      class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    <div>
                        <label class="block text-sm font-medium mb-1">Outlet</label>
                        <select name="outlet"
                                class="w-full border-gray-300 rounded-lg">
                            <option value="">Semua Outlet</option>
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet }}"
                                    {{ request('outlet') == $outlet ? 'selected' : '' }}>
                                    {{ $outlet }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-start-4">
                        <button type="submit"
                                class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 uppercase">
                        <tr>
                            <th class="px-4 py-3">Bahan</th>
                            <th class="px-4 py-3 text-right">Total Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                        @if(isset($totalStok))
                            @foreach($fields as $key => $label)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">
                                    {{ $label }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ formatAngka($totalStok->$key ?? 0) }}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2"
                                    class="p-4 text-center text-gray-500">
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