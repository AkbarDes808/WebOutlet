@extends('layouts.app')

@section('content')

@php
$rows = [
    'tepung_roti'  => 'Tepung Roti',
    'tepung_bumbu' => 'Tepung Bumbu',
    'garam'        => 'Garam',
    'bubuk_cabe'   => 'Bubuk Cabe',
    'telur'        => 'Telur',
    'gula'         => 'Gula',
    'ayam'         => 'Ayam',
];
@endphp

<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Stok Bahan</h1>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- PILIH OUTLET (ADMIN / SPV) --}}
    @if(Auth::user()->role !== 'outlet')
    <div class="mb-6">
        <form action="{{ route('bahans.index') }}" method="GET">
            <label class="block text-xl font-semibold mb-2">Pilih Outlet</label>
            <select name="outlet"
                    onchange="this.form.submit()"
                    class="w-full md:w-1/2 border rounded p-2 bg-white">
                <option value="">-- Tampilkan Total Stok Semua Outlet --</option>
                @foreach($outlets as $outlet)
                    <option value="{{ $outlet }}"
                        {{ ($selectedOutlet ?? '') == $outlet ? 'selected' : '' }}>
                        {{ $outlet }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    {{-- ===================== --}}
    {{-- JIKA OUTLET DIPILIH --}}
    {{-- ===================== --}}
    @if($selectedOutlet)

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">
            Stok Saat Ini (Outlet: {{ $selectedOutlet }})
        </h2>

        <table class="min-w-full border text-sm text-center">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Bahan</th>
                    <th class="p-2 border">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $key => $label)
                <tr>
                    <td class="p-2 border text-left">{{ $label }}</td>
                    <td class="p-2 border">{{ $bahan->$key ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr class="my-6">

    {{-- FORM PERUBAHAN STOK --}}
    @if(Auth::user()->role === 'outlet')
        <h2 class="text-xl font-semibold mb-2">Input Bahan Terpakai</h2>
        <p class="text-sm text-gray-600 mb-4">Hanya boleh mengurangi stok.</p>
    @else
        <h2 class="text-xl font-semibold mb-2">Input Perubahan Stok</h2>
        <p class="text-sm text-gray-600 mb-4">Angka (+) menambah, (-) mengurangi.</p>
    @endif

    <form class="stok-form" action="{{ route('bahans.store') }}" method="POST">
        @csrf
        <input type="hidden" name="nama_outlet" value="{{ $selectedOutlet }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($rows as $key => $label)
            <div>
                <label class="block mb-1 font-medium">{{ $label }}</label>
                <input type="number"
                       name="{{ $key }}"
                       placeholder="Masukkan angka"
                       @if(Auth::user()->role === 'outlet') max="0" @endif
                       class="stok-input w-full border rounded p-2">
            </div>
            @endforeach
        </div>

        <button type="submit"
                class="btn-submit mt-4 bg-gray-400 text-white px-6 py-2 rounded cursor-not-allowed"
                disabled>
            Simpan Perubahan
        </button>
    </form>

    {{-- =========================== --}}
    {{-- JIKA OUTLET TIDAK DIPILIH --}}
    {{-- =========================== --}}
    @else

    <div class="p-4 border rounded bg-gray-50">
        <h2 class="text-xl font-semibold mb-4">📊 Total Stok Semua Outlet</h2>

        <table class="min-w-full border text-sm text-center mb-6">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Bahan</th>
                    <th class="p-2 border">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($rows as $key => $label)
                <tr>
                    <td class="p-2 border text-left">{{ $label }}</td>
                    <td class="p-2 border font-medium">{{ $totalStok->$key ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- FORM TAMBAH / KURANG STOK --}}
        @if(Auth::user()->role !== 'outlet')
        <hr class="my-6">

        <h2 class="text-xl font-semibold mb-2">Input Perubahan Stok (Pilih Outlet)</h2>

        <form class="stok-form" action="{{ route('bahans.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Outlet</label>
                <select name="nama_outlet" required
                        class="w-full md:w-1/2 border rounded p-2 bg-white">
                    <option value="">-- Pilih Outlet --</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet }}">{{ $outlet }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rows as $key => $label)
                <div>
                    <label class="block mb-1 font-medium">{{ $label }}</label>
                    <input type="number"
                           name="{{ $key }}"
                           placeholder="Masukkan angka"
                           class="stok-input w-full border rounded p-2">
                </div>
                @endforeach
            </div>

            <button type="submit"
                    class="btn-submit mt-4 bg-gray-400 text-white px-6 py-2 rounded cursor-not-allowed"
                    disabled>
                Simpan Perubahan
            </button>
        </form>
        @endif
    </div>

    @endif
</div>

{{-- ================= --}}
{{-- JAVASCRIPT LOGIC --}}
{{-- ================= --}}
<script>
document.querySelectorAll('.stok-form').forEach(form => {
    const inputs = form.querySelectorAll('.stok-input');
    const button = form.querySelector('.btn-submit');

    function cekPerubahan() {
        let aktif = false;

        inputs.forEach(input => {
            if (input.value !== '' && Number(input.value) !== 0) {
                aktif = true;
            }
        });

        button.disabled = !aktif;
        button.classList.toggle('bg-blue-600', aktif);
        button.classList.toggle('hover:bg-blue-700', aktif);
        button.classList.toggle('bg-gray-400', !aktif);
        button.classList.toggle('cursor-not-allowed', !aktif);
    }

    inputs.forEach(input => {
        input.addEventListener('input', cekPerubahan);
    });
});
</script>

@endsection
