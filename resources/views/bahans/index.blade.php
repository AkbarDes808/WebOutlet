@extends('layouts.app')

@section('content')

@php

$isOutlet = preg_match(
    '/^outlet\s\d+$/i',
    trim(Auth::user()->role)
);

$rows = [

    'tepung_roti' => 'Tepung Roti',

    'tepung_bumbu' => 'Tepung Bumbu',

    'garam' => 'Garam',

    'bubuk_cabe' => 'Bubuk Cabe',

    'telur' => 'Telur',

    'gula' => 'Gula',

    'ayam' => 'Ayam',

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

function formatAngka($value)
{
    if ($value === null) {
        return '0';
    }

    return rtrim(
        rtrim(
            number_format(
                (float) $value,
                2,
                ',',
                '.'
            ),
            '0'
        ),
        ','
    );
}

@endphp

<div class="max-w-6xl mx-auto p-6">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold">
            Stok Bahan & Kemasan
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Kelola stok bahan dan kemasan setiap outlet.
        </p>

    </div>

    {{-- ===================================================== --}}
    {{-- SUCCESS ALERT --}}
    {{-- ===================================================== --}}

    @if(session('success'))

        <div
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-5"
        >
            {{ session('success') }}
        </div>

    @endif

    {{-- ===================================================== --}}
    {{-- ERROR ALERT --}}
    {{-- ===================================================== --}}

    @if(session('error'))

        <div
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-5"
        >
            {{ session('error') }}
        </div>

    @endif

    {{-- ===================================================== --}}
    {{-- VALIDATION ERROR --}}
    {{-- ===================================================== --}}

    @if($errors->any())

        <div
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-5"
        >

            <ul class="list-disc list-inside">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- ===================================================== --}}
    {{-- PILIH OUTLET ADMIN / SPV --}}
    {{-- ===================================================== --}}

    @if(!$isOutlet)

        <form
            method="GET"
            action="{{ route('bahans.index') }}"
            class="mb-6"
        >

            <label class="block font-semibold mb-2">
                Pilih Outlet
            </label>

            <select
                name="outlet"
                onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg p-2 w-full md:w-1/2 bg-white"
            >

                <option value="">
                    -- Total Semua Outlet --
                </option>

                @foreach($outlets as $outlet)

                    <option
                        value="{{ $outlet }}"
                        {{ ($selectedOutlet ?? '') == $outlet ? 'selected' : '' }}
                    >
                        {{ $outlet }}
                    </option>

                @endforeach

            </select>

        </form>

    @endif

    {{-- ===================================================== --}}
    {{-- STOK OUTLET --}}
    {{-- ===================================================== --}}

    @if($selectedOutlet && $bahan)

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

            <div class="px-5 py-4 border-b bg-gray-50">

                <h2 class="text-xl font-semibold">
                    Stok Saat Ini
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $selectedOutlet }}
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full border-collapse text-sm">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="border p-3 text-left">
                                Item
                            </th>

                            <th class="border p-3 text-right">
                                Jumlah
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($rows as $key => $label)

                            <tr class="hover:bg-gray-50">

                                <td class="border p-3">
                                    {{ $label }}
                                </td>

                                <td class="border p-3 text-right font-semibold">

                                    {{ formatAngka($bahan->$key ?? 0) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @elseif($selectedOutlet)

        <div
            class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 rounded-lg"
        >
            Data stok untuk {{ $selectedOutlet }} belum tersedia.
        </div>

    @endif

    {{-- ===================================================== --}}
    {{-- TOTAL SEMUA OUTLET --}}
    {{-- ADMIN / SPV --}}
    {{-- ===================================================== --}}

    @if(!$isOutlet && !$selectedOutlet)

        <div class="border border-gray-200 rounded-xl bg-gray-50 overflow-hidden">

            <div class="px-5 py-4 border-b bg-gray-100">

                <h2 class="text-xl font-semibold">
                    Total Semua Outlet
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Total stok berdasarkan record terbaru setiap outlet.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full border-collapse text-sm">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="border p-3 text-left">
                                Item
                            </th>

                            <th class="border p-3 text-right">
                                Total
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($rows as $key => $label)

                            <tr class="hover:bg-gray-100">

                                <td class="border p-3">
                                    {{ $label }}
                                </td>

                                <td class="border p-3 text-right font-semibold">

                                    {{ formatAngka($totalStok->$key ?? 0) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

    {{-- ===================================================== --}}
    {{-- FORM UPDATE --}}
    {{-- ADMIN / SPV ONLY --}}
    {{-- ===================================================== --}}

    @if(!$isOutlet && $selectedOutlet)

        <hr class="my-8">

        <div class="bg-white border border-gray-200 rounded-xl p-5">

            <h2 class="text-xl font-semibold mb-1">
                Input Perubahan Stok
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Masukkan jumlah tambahan stok. Nilai yang dimasukkan akan
                ditambahkan ke stok terakhir {{ $selectedOutlet }}.
            </p>

            <form
                class="stok-form"
                action="{{ route('bahans.store') }}"
                method="POST"
            >

                @csrf

                <input
                    type="hidden"
                    name="nama_outlet"
                    value="{{ $selectedOutlet }}"
                >

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    @foreach($rows as $key => $label)

                        <div>

                            <label
                                for="stok_{{ $key }}"
                                class="block mb-1 font-medium"
                            >
                                {{ $label }}
                            </label>

                            <input
                                id="stok_{{ $key }}"
                                type="text"
                                name="{{ $key }}"
                                inputmode="decimal"
                                autocomplete="off"
                                placeholder="Masukkan jumlah"
                                class="stok-input w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >

                        </div>

                    @endforeach

                </div>

                <div class="mt-5 flex items-center gap-3">

                    <button
                        disabled
                        type="submit"
                        class="btn-submit bg-gray-400 text-white px-6 py-2 rounded-lg cursor-not-allowed transition"
                    >
                        Simpan Perubahan
                    </button>

                    <button
                        type="reset"
                        class="btn-reset bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition"
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

        const resetButton = form.querySelector('.btn-reset');

        function cekButton() {

            let aktif = false;

            inputs.forEach(function (input) {

                if (input.value.trim() !== '') {
                    aktif = true;
                }

            });

            button.disabled = !aktif;

            button.classList.toggle(
                'bg-blue-600',
                aktif
            );

            button.classList.toggle(
                'hover:bg-blue-700',
                aktif
            );

            button.classList.toggle(
                'bg-gray-400',
                !aktif
            );

            button.classList.toggle(
                'cursor-not-allowed',
                !aktif
            );

            button.classList.toggle(
                'cursor-pointer',
                aktif
            );

        }

        inputs.forEach(function (input) {

            input.addEventListener(
                'input',
                cekButton
            );

        });

        if (resetButton) {

            resetButton.addEventListener(
                'click',
                function () {

                    setTimeout(
                        cekButton,
                        0
                    );

                }
            );

        }

        cekButton();

    });

});

</script>

@endsection