@extends('layouts.app')
@section('content')
@php
$isOutlet = str_contains(
    strtolower(Auth::user()->role),
    'outlet'
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
    if($value === null){
        return 0;
    }
    return rtrim(
        rtrim(
            number_format(
                (float)$value,
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
    <h1 class="text-2xl font-bold mb-6">
        Stok Bahan & Kemasan
    </h1>
    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif

    {{-- ========================= --}}
    {{-- PILIH OUTLET ADMIN/SPV --}}
    {{-- ========================= --}}
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
                class="border rounded p-2 w-full md:w-1/2"
            >
                <option value="">
                    -- Total Semua Outlet --
                </option>
                @foreach($outlets as $outlet)
                    <option 
                        value="{{ $outlet }}"
                        {{ ($selectedOutlet ?? '') == $outlet ? 'selected':'' }}
                    >
                        {{ $outlet }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif

    {{-- ========================= --}}
    {{-- STOK OUTLET --}}
    {{-- ========================= --}}
    @if($selectedOutlet && $bahan)
        <div>
            <h2 class="text-xl font-semibold mb-4">
                Stok Saat Ini :
                {{ $selectedOutlet }}
            </h2>
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">
                            Item
                        </th>
                        <th class="border p-2">
                            Jumlah
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key=>$label)
                        <tr>
                            <td class="border p-2">
                                {{ $label }}
                            </td>
                            <td class="border p-2 text-right font-semibold">
                                {{ formatAngka($bahan->$key ?? 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ========================= --}}
    {{-- TOTAL SEMUA OUTLET --}}
    {{-- ADMIN ONLY --}}
    {{-- ========================= --}}
    @if(!$isOutlet && !$selectedOutlet)
        <div class="border rounded p-5 bg-gray-50">
            <h2 class="text-xl font-semibold mb-4">
                Total Semua Outlet
            </h2>
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">
                            Item
                        </th>
                        <th class="border p-2">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key=>$label)
                        <tr>
                            <td class="border p-2">
                                {{ $label }}
                            </td>
                            <td class="border p-2 text-right">
                                {{ formatAngka($totalStok->$key ?? 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ========================= --}}
    {{-- FORM UPDATE --}}
    {{-- ADMIN/SPV ONLY --}}
    {{-- ========================= --}}
    @if(!$isOutlet && $selectedOutlet)
        <hr class="my-8">
        <h2 class="text-xl font-semibold mb-4">
            Input Perubahan Stok
        </h2>
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
                @foreach($rows as $key=>$label)
                    <div>
                        <label class="block mb-1 font-medium">
                            {{ $label }}
                        </label>
                        <input
                            type="text"
                            name="{{ $key }}"
                            inputmode="numeric"
                            placeholder="Masukkan jumlah"
                            class="stok-input w-full border rounded p-2"
                        >
                    </div>
                @endforeach
            </div>
            <button
                disabled
                type="submit"
                class="btn-submit mt-5 bg-gray-400 text-white px-6 py-2 rounded"
            >
                Simpan Perubahan
            </button>
        </form>
    @endif
</div>
<script>
document.querySelectorAll('.stok-form')
.forEach(form => {
    const inputs =
        form.querySelectorAll('.stok-input');
    const button =
        form.querySelector('.btn-submit');
    function cekButton(){
        let aktif = false;
        inputs.forEach(input => {
            if(input.value.trim() !== ''){

                aktif = true;

            }
        });
        button.disabled = !aktif;
        button.classList.toggle(
            'bg-blue-600',
            aktif
        );
        button.classList.toggle(
            'bg-gray-400',
            !aktif
        );
    }
    inputs.forEach(input => {


        input.addEventListener(
            'input',
            cekButton
        );


    });


});
</script>
@endsection