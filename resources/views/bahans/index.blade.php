@extends('layouts.app')

@section('content')

@php
$isOutlet = preg_match('/^outlet\s\d+$/i', trim(Auth::user()->role));

$rows = [
    'teh' => 'Teh Kotak',
    'plastik_sedang' => 'Plastik Sedang',
];

$newItems = $stockItems->whereIn('nama', [
    'Saos Cabe',
    'Kertas Ayam',
    'Dus',
    'Plastik Kecil',
    'Plastik Sedang',
]);

$ayam = $stockItems->where('id', 12);

$outletNames = [
    'outlet 1' => 'Pusat',
    'outlet 2' => 'Indomaret',
    'outlet 3' => 'Bunderan',
    'outlet 4' => 'Mersi',
    'outlet 5' => 'Arca',
    'outlet 6' => 'Larangan',
    'outlet 7' => 'Unsoed',
];

function angka($value)
{
    return rtrim(
        rtrim(number_format((float) ($value ?? 0), 2, ',', '.'), '0'),
        ','
    );
}

function stokOutlet($id, $rows)
{
    return optional(
        $rows->firstWhere('stock_item_id', $id)
    )->stok ?? 0;
}

function stokTotal($id, $rows)
{
    return optional(
        $rows->firstWhere('stock_item_id', $id)
    )->total_stok ?? 0;
}

@endphp

<div class="max-w-6xl mx-auto px-4 py-5">

{{-- HEADER --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Stok Bahan & Kemasan</h1>
        <p class="text-sm text-gray-500">Kelola stok bahan dan perlengkapan outlet.</p>
    </div>

    @if($selectedOutlet)
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
            {{ $outletNames[strtolower($selectedOutlet)] ?? $selectedOutlet }}
        </div>
    @endif
</div>

{{-- ALERT --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        @foreach($errors->all() as $error)
            <div class="text-sm">{{ $error }}</div>
        @endforeach
    </div>
@endif

{{-- OUTLET --}}
@if(!$isOutlet)
    <div class="bg-white border rounded-xl p-4 mb-5">
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Pilih Outlet
        </label>

        <form method="GET" action="{{ route('bahans.index') }}">
            <select
                name="outlet"
                onchange="this.form.submit()"
                class="w-full md:w-80 border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Total Semua Outlet</option>

                @foreach($outlets as $outlet)
                    <option
                        value="{{ $outlet }}"
                        {{ ($selectedOutlet ?? '') == $outlet ? 'selected' : '' }}
                    >
                        {{ $outletNames[strtolower($outlet)] ?? $outlet }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
@endif

{{-- CARD STOK --}}
<div class="mb-5">

    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $selectedOutlet ? 'Stok Saat Ini' : 'Total Semua Outlet' }}
            </h2>

            <p class="text-xs text-gray-500">
                {{ $selectedOutlet
                    ? 'Jumlah stok pada outlet yang dipilih.'
                    : 'Jumlah stok gabungan seluruh outlet.' }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">

        {{-- AYAM --}}
        @foreach($ayam as $item)
            @php
                $stok = $selectedOutlet
                    ? stokOutlet($item->id, $stockOutletRows)
                    : stokTotal($item->id, $totalSemuaOutlet);
            @endphp

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-orange-100 text-orange-600 mb-3">
                    🍗
                </div>

                <div class="text-xs text-gray-500">Bagian Ayam</div>

                <div class="font-semibold text-gray-800 mt-1">
                    {{ $item->nama }}
                </div>

                <div class="text-2xl font-bold text-gray-900 mt-3">
                    {{ angka($stok) }}
                </div>

                <div class="text-xs text-gray-400 mt-1">
                    {{ $item->satuan }}
                </div>
            </div>
        @endforeach

        {{-- BAHAN --}}
        @foreach($rows as $key => $label)
            @php
                $stok = $selectedOutlet
                    ? ($bahan->$key ?? 0)
                    : ($totalStok->$key ?? 0);
            @endphp

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 mb-3">
                    📦
                </div>

                <div class="text-xs text-gray-500">Bahan</div>

                <div class="font-semibold text-gray-800 mt-1">
                    {{ $label }}
                </div>

                <div class="text-2xl font-bold text-gray-900 mt-3">
                    {{ angka($stok) }}
                </div>

                <div class="text-xs text-gray-400 mt-1">
                    stok
                </div>
            </div>
        @endforeach

        {{-- 5 ITEM BARU --}}
        @foreach($newItems as $item)
            @php
                $stok = $selectedOutlet
                    ? stokOutlet($item->id, $stockOutletRows)
                    : stokTotal($item->id, $totalSemuaOutlet);
            @endphp

            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">

                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-100 text-green-600 mb-3">
                    📦
                </div>

                <div class="text-xs text-gray-500">
                    {{ $item->kategori }}
                </div>

                <div class="font-semibold text-gray-800 mt-1">
                    {{ $item->nama }}
                </div>

                <div class="text-2xl font-bold text-gray-900 mt-3">
                    {{ angka($stok) }}
                </div>

                <div class="text-xs text-gray-400 mt-1">
                    {{ $item->satuan }}
                </div>

            </div>
        @endforeach

    </div>
</div>

{{-- FORM INPUT --}}
@if(!$isOutlet && $selectedOutlet)

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-gray-800">
                Tambah Stok
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Masukkan jumlah stok yang ingin ditambahkan.
            </p>
        </div>

        <form
            action="{{ route('bahans.store') }}"
            method="POST"
            class="stok-form"
        >
            @csrf

            <input
                type="hidden"
                name="nama_outlet"
                value="{{ $selectedOutlet }}"
            >

            {{-- AYAM --}}
            @if($ayam->isNotEmpty())
                <div class="mb-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">
                        Bagian Ayam
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                        @foreach($ayam as $item)
                            <div>
                                <label
                                    for="stock_item_{{ $item->id }}"
                                    class="text-sm font-medium text-gray-700"
                                >
                                    {{ $item->nama }}
                                </label>

                                <input
                                    id="stock_item_{{ $item->id }}"
                                    type="number"
                                    name="stock_item_{{ $item->id }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0"
                                    class="stok-input w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif

            {{-- BAHAN --}}
            <div class="mb-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    Bahan
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                    @foreach($rows as $key => $label)
                        <div>
                            <label
                                for="stok_{{ $key }}"
                                class="text-sm font-medium text-gray-700"
                            >
                                {{ $label }}
                            </label>

                            <input
                                id="stok_{{ $key }}"
                                type="number"
                                name="{{ $key }}"
                                min="0"
                                step="0.01"
                                placeholder="0"
                                class="stok-input w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- ITEM BARU --}}
            @if($newItems->isNotEmpty())
                <div class="mb-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">
                        Menu Tambahan & Gratis
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                        @foreach($newItems as $item)
                            <div>
                                <label
                                    for="stock_item_{{ $item->id }}"
                                    class="text-sm font-medium text-gray-700"
                                >
                                    {{ $item->nama }}{{ $item->nama === 'Plastik Sedang' && $item->kategori === 'Menu Gratis' ? ' (Gratis)' : '' }}
                                </label>

                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $item->kategori }}
                                </div>

                                <input
                                    id="stock_item_{{ $item->id }}"
                                    type="number"
                                    name="stock_item_{{ $item->id }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0"
                                    class="stok-input w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif

            <div class="flex gap-2 pt-2">

                <button
                    type="submit"
                    disabled
                    class="btn-submit bg-gray-400 text-white px-5 py-2 rounded-lg cursor-not-allowed transition"
                >
                    Simpan
                </button>

                <button
                    type="reset"
                    class="btn-reset bg-gray-100 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-200 transition"
                >
                    Reset
                </button>

            </div>

        </form>
    </div>

@endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.stok-form').forEach(function (form) {

        const inputs = form.querySelectorAll('.stok-input');
        const button = form.querySelector('.btn-submit');
        const reset = form.querySelector('.btn-reset');

        function checkInput() {
            const aktif = [...inputs].some(input =>
                input.value.trim() !== '' &&
                parseFloat(input.value) > 0
            );

            button.disabled = !aktif;

            button.classList.toggle('bg-blue-600', aktif);
            button.classList.toggle('hover:bg-blue-700', aktif);
            button.classList.toggle('bg-gray-400', !aktif);
            button.classList.toggle('cursor-not-allowed', !aktif);
            button.classList.toggle('cursor-pointer', aktif);
        }

        inputs.forEach(input => {
            input.addEventListener('input', checkInput);
            input.addEventListener('change', checkInput);
        });

        if (reset) {
            reset.addEventListener('click', function () {
                setTimeout(checkInput, 0);
            });
        }

        checkInput();
    });

});
</script>

@endsection