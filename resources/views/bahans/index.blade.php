@extends('layouts.app')

@section('content')

@php
$rows = [
    // ===== BAHAN LAMA =====
    'tepung_roti'  => 'Tepung Roti',
    'tepung_bumbu' => 'Tepung Bumbu',
    'garam'        => 'Garam',
    'bubuk_cabe'   => 'Bubuk Cabe',
    'telur'        => 'Telur',
    'gula'         => 'Gula',
    'ayam'         => 'Ayam',

    // ===== BAHAN BARU =====
    'tepung'       => 'Tepung',
    'teh'          => 'Teh',
    'beras'        => 'Beras',
    'cup'          => 'Cup',

    // ===== KEMASAN =====
    'kertas_chicken_kecil'   => 'Kertas Chicken Kecil',
    'kertas_chicken_sedang'  => 'Kertas Chicken Sedang',
    'kertas_chicken_besar'   => 'Kertas Chicken Besar',

    'dus_chicken'            => 'Dus Chicken',
    'dus_chicken_jumbo'      => 'Dus Chicken Jumbo',

    'plastik_cup_isi_1'      => 'Plastik Cup Isi 1',
    'plastik_cup_isi_2'      => 'Plastik Cup Isi 2',

    'plastik_ayam_kecil'     => 'Plastik Ayam Kecil',
    'plastik_sedang'         => 'Plastik Sedang',
    'plastik_tanggung'       => 'Plastik Tanggung',
    'plastik_besar'          => 'Plastik Besar',
    'plastik_jumbo'          => 'Plastik Jumbo',
];

function formatAngka($value) {
    if ($value === null) return '0';
    $angka = (float) $value;
    $formatted = number_format($angka, 2, ',', '.');
    return rtrim(rtrim($formatted, '0'), ',');
}
@endphp

<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Stok Bahan & Kemasan</h1>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- PILIH OUTLET --}}
    @if(Auth::user()->role !== 'outlet')
        <div class="mb-6">
            <form action="{{ route('bahans.index') }}" method="GET">
                <label class="block text-lg font-semibold mb-2">Pilih Outlet</label>
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

            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100 text-center">
                    <tr>
                        <th class="p-2 border">Item</th>
                        <th class="p-2 border">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key => $label)
                        <tr>
                            <td class="p-2 border">{{ $label }}</td>
                            <td class="p-2 border text-right font-medium">
                                {{ formatAngka($bahan->$key ?? 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        {{-- FORM PERUBAHAN --}}
        <h2 class="text-xl font-semibold mb-2">
            {{ Auth::user()->role === 'outlet'
                ? 'Input Bahan Terpakai'
                : 'Input Perubahan Stok' }}
        </h2>

        <form class="stok-form" action="{{ route('bahans.store') }}" method="POST">
            @csrf
            <input type="hidden" name="nama_outlet" value="{{ $selectedOutlet }}">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rows as $key => $label)
                    <div>
                        <label class="block mb-1 font-medium">{{ $label }}</label>
                        <input type="text"
                               name="{{ $key }}"
                               inputmode="numeric"
                               placeholder="Masukkan jumlah"
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

            <table class="min-w-full border text-sm mb-6">
                <thead class="bg-gray-200 text-center">
                    <tr>
                        <th class="p-2 border">Item</th>
                        <th class="p-2 border">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $key => $label)
                        <tr>
                            <td class="p-2 border">{{ $label }}</td>
                            <td class="p-2 border text-right font-medium">
                                {{ formatAngka($totalStok->$key ?? 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
                                <input type="text"
                                       name="{{ $key }}"
                                       inputmode="numeric"
                                       placeholder="Masukkan jumlah"
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
{{-- JAVASCRIPT --}}
{{-- ================= --}}
<script>
document.querySelectorAll('.stok-form').forEach(form => {

    const inputs = form.querySelectorAll('.stok-input');
    const btn = form.querySelector('.btn-submit');

    function cekButton() {
        let aktif = false;
        inputs.forEach(i => {
            if (i.value.trim() !== '' && i.value.trim() !== '-') aktif = true;
        });
        if (!btn) return;
        btn.disabled = !aktif;
        btn.classList.toggle('bg-blue-600', aktif);
        btn.classList.toggle('hover:bg-blue-700', aktif);
        btn.classList.toggle('bg-gray-400', !aktif);
        btn.classList.toggle('cursor-not-allowed', !aktif);
    }

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            let val = this.value;
            val = val.replace(/[^0-9,\-]/g, '');

            if (val.includes('-') && val[0] !== '-') {
                val = val.replace(/-/g, '');
            }

            const parts = val.split(',');
            if (parts.length > 2) {
                val = parts[0] + ',' + parts.slice(1).join('');
            }

            this.value = val;
            cekButton();
        });
    });

    form.addEventListener('submit', () => {
        inputs.forEach(input => {
            let val = input.value.trim();
            if (val === '' || val === '-') {
                input.value = '';
                return;
            }
            input.value = val.replace(',', '.');
        });
    });

});
</script>
@endsection
