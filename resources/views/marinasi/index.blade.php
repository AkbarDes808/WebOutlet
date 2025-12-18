@extends('layouts.app')

@section('content')

@php
/**
 * FORMAT ANGKA INDONESIA
 */
function formatAngka($v) {
    if ($v === null) return '0';
    $v = (float)$v;
    return rtrim(rtrim(number_format($v, 2, ',', '.'), '0'), ',');
}

/**
 * RASIO BAHAN PER 1 AYAM
 * 👉 SILAKAN UBAH SESUAI KEBUTUHAN
 */
$ratio = [
    'lada'          => 2,    // kg
    'gula'          => 1,    // kg
    'bawang_putih'  => 0.5,  // kg
    'saus_teriyaki' => 0.3,  // kg
    'garam'         => 0.2,
    'ketumbar'      => 0.1,
];

$labels = [
    'lada'          => 'Lada',
    'gula'          => 'Gula',
    'bawang_putih'  => 'Bawang Putih',
    'saus_teriyaki' => 'Saus Teriyaki',
    'garam'         => 'Garam',
    'ketumbar'      => 'Ketumbar',
];
@endphp

<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">🍗 Marinasi (Batch System)</h1>

    {{-- INPUT UTAMA --}}
    <div class="bg-white border rounded-lg p-4 mb-6">
        <h2 class="font-semibold mb-4">Input Produksi</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">
                    Jumlah Ayam (pcs)
                </label>
                <input type="text"
                       id="jumlahAyam"
                       class="w-full border rounded p-2"
                       placeholder="Contoh: 10"
                       autocomplete="off">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Jumlah Batch / Karung
                </label>
                <input type="number"
                       id="jumlahBatch"
                       min="1"
                       value="1"
                       class="w-full border rounded p-2">
            </div>
        </div>
    </div>

    {{-- HASIL KONVERSI --}}
    <form id="formMarinasi" method="POST" action="{{ route('marinasi.store') }}">
        @csrf

        <input type="hidden" name="total_ayam" id="totalAyamHidden">
        <input type="hidden" name="jumlah_batch" id="batchHidden">

        <div class="bg-gray-50 border rounded-lg p-4">
            <h2 class="font-semibold mb-4">Hasil Perhitungan Bahan</h2>

            <table class="min-w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border text-left">Bahan</th>
                        <th class="p-2 border text-right">Total</th>
                        <th class="p-2 border text-right">Per Batch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ratio as $key => $perAyam)
                    <tr>
                        <td class="p-2 border">{{ $labels[$key] }}</td>

                        <td class="p-2 border text-right font-semibold">
                            <span id="total_{{ $key }}">0</span> kg
                        </td>

                        <td class="p-2 border text-right">
                            <span id="batch_{{ $key }}">0</span> kg
                        </td>

                        <input type="hidden" name="{{ $key }}" id="input_{{ $key }}">
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 hidden" id="btnSimpan">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Simpan Batch Marinasi
            </button>
        </div>
    </form>
</div>
{{-- ========================= --}}
{{-- TABEL RIWAYAT BATCH --}}
{{-- ========================= --}}
<div class="bg-white border rounded-lg p-4 mb-6">
    <h2 class="text-lg font-semibold mb-4">
        📦 Riwayat Batch Marinasi
    </h2>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border">
            <thead class="bg-gray-100 text-center">
                <tr>
                    <th class="p-2 border">Kode Batch</th>
                    <th class="p-2 border">Total Ayam</th>
                    <th class="p-2 border">Jumlah Batch</th>
                    <th class="p-2 border">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr class="text-center hover:bg-gray-50">
                        <td class="p-2 border font-semibold">
                            {{ $batch->kode_batch }}
                        </td>
                        <td class="p-2 border">
                            {{ number_format($batch->total_ayam, 0, ',', '.') }} pcs
                        </td>
                        <td class="p-2 border">
                            {{ $batch->jumlah_batch }} karung
                        </td>
                        <td class="p-2 border text-xs text-gray-600">
                            {{ $batch->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="p-4 text-center text-gray-500">
                            Belum ada batch marinasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SCRIPT --}}
<script>
const ratio = @json($ratio);

function formatID(num) {
    return num.toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
}

const ayamInput  = document.getElementById('jumlahAyam');
const batchInput = document.getElementById('jumlahBatch');
const btnSimpan  = document.getElementById('btnSimpan');

function hitung() {
    let ayam  = ayamInput.value.replace(/[^0-9]/g,'');
    let batch = parseInt(batchInput.value || 1);

    if (!ayam || batch < 1) {
        btnSimpan.classList.add('hidden');
        return;
    }

    ayam = parseInt(ayam);

    document.getElementById('totalAyamHidden').value = ayam;
    document.getElementById('batchHidden').value = batch;

    Object.keys(ratio).forEach(key => {
        let total = ayam * ratio[key];
        let perBatch = total / batch;

        document.getElementById('total_' + key).innerText  = formatID(total);
        document.getElementById('batch_' + key).innerText  = formatID(perBatch);
        document.getElementById('input_' + key).value = total;
    });

    btnSimpan.classList.remove('hidden');
}

ayamInput.addEventListener('input', hitung);
batchInput.addEventListener('input', hitung);
</script>

@endsection
