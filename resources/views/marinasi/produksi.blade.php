@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-xl sm:text-2xl font-bold mb-4">
            Gunakan Bumbu & History
        </h1>

        <div class="inline-flex rounded-xl bg-gray-200 p-1">
            <a href="{{ route('marinasi.index') }}"
               class="px-3 sm:px-4 py-1.5 text-sm rounded-lg text-gray-600 hover:bg-gray-300">
                Masukkan Bumbu
            </a>
            <a href="{{ route('marinasi.produksi') }}"
               class="px-3 sm:px-4 py-1.5 text-sm rounded-lg bg-white shadow font-medium">
                Gunakan Bumbu & History
            </a>
        </div>
    </div>

    {{-- ================= FORM PRODUKSI ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">

        {{-- MARINASI --}}
        <form method="POST"
              action="{{ route('produksi.marinasi.submit') }}"
              onsubmit="return confirmMarinasi()"
              class="border rounded-xl p-5">
            @csrf

            <h2 class="font-semibold mb-4 text-base">
                Masukkan banyak ayam
            </h2>

            <input type="hidden" name="jumlah_ayam" id="jumlahAyam">

            <div class="flex flex-wrap gap-3 mb-2">
                <button type="button"
                        onclick="selectAyam(1500, this)"
                        class="ayam-btn flex-1 px-4 py-2 border rounded-lg text-sm">
                    1500 / pcs
                </button>

                <button type="button"
                        onclick="selectAyam(3000, this)"
                        class="ayam-btn flex-1 px-4 py-2 border rounded-lg text-sm">
                    3000 / pcs
                </button>
            </div>

            <p class="text-xs text-gray-500">
                *Pilih salah satu jumlah ayam
            </p>

            <button type="submit"
                    class="mt-4 w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Marinasi
            </button>
        </form>

        {{-- LAPIS --}}
        <form method="POST"
              action="{{ route('produksi.lapis.submit') }}"
              onsubmit="return confirmLapis()"
              class="border rounded-xl p-5">
            @csrf

            <h2 class="font-semibold mb-4 text-base">
                Masukkan banyak tepung lapis
            </h2>

            <input type="hidden" name="jumlah_karung" id="jumlahKarung">

            <div class="flex flex-wrap gap-3 mb-2">
                <button type="button"
                        onclick="selectKarung(25, this)"
                        class="karung-btn flex-1 px-4 py-2 border rounded-lg text-sm">
                    25 kg
                </button>

                <button type="button"
                        onclick="selectKarung(50, this)"
                        class="karung-btn flex-1 px-4 py-2 border rounded-lg text-sm">
                    50 kg
                </button>
            </div>

            <p class="text-xs text-gray-500">
                *Pilih salah satu bobot karung
            </p>

            <button type="submit"
                    class="mt-4 w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Lapis
            </button>
        </form>
    </div>

    {{-- ================= HISTORY ================= --}}
    <div class="mt-8">
        <h3 class="font-semibold mb-4 text-base">
            History Bumbu
        </h3>

        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-[800px] w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal & Waktu</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Penggunaan</th>
                        <th class="px-4 py-3 text-left">Banyak</th>
                        <th class="px-4 py-3 text-left">Satuan</th>
                        <th class="px-4 py-3 text-left">Total Bumbu Tersisa</th>
                    </tr>
                </thead>

                <tbody>
@php
    $groupedHistory = collect($history)->groupBy('marinasi_id');
@endphp

@forelse($groupedHistory as $group)
    @php
        $row = $group->first();
        $waktu = \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i');

        if (in_array($row->penggunaan, ['Ayam', 'Simpan Karung'])) {
            $banyak = $group->first()->banyak;
        } else {
            $banyak = $group->sum('banyak');
        }

        $satuan = $group->first()->satuan ?? '-';

        $totalTersisa = collect($history)
            ->where('created_at', '<=', $row->created_at)
            ->sum('total');
    @endphp

    <tr class="border-b">
        <td class="px-4 py-3 whitespace-nowrap">{{ $waktu }}</td>
        <td class="px-4 py-3">{{ $row->Jenis }}</td>
        <td class="px-4 py-3">{{ $row->penggunaan }}</td>
        <td class="px-4 py-3">{{ number_format($banyak, 0, ',', '.') }}</td>
        <td class="px-4 py-3">{{ $satuan }}</td>
        <td class="px-4 py-3 font-medium whitespace-nowrap">
            {{ number_format($totalTersisa, 0, ',', '.') }} gr
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-6 text-gray-400">
            Belum ada data
        </td>
    </tr>
@endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= SCRIPT (TETAP) ================= --}}
<script>
function resetButtons(className) {
    document.querySelectorAll('.' + className).forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
    });
}

function selectAyam(value, btn) {
    resetButtons('ayam-btn');
    btn.classList.add('bg-blue-600', 'text-white');
    document.getElementById('jumlahAyam').value = value;
}

function selectKarung(value, btn) {
    resetButtons('karung-btn');
    btn.classList.add('bg-blue-600', 'text-white');
    document.getElementById('jumlahKarung').value = value;
}

function confirmMarinasi() {
    const jumlah = document.getElementById('jumlahAyam').value;
    if (!jumlah) {
        alert('Silakan pilih jumlah ayam terlebih dahulu.');
        return false;
    }
    return confirm(`Anda yakin akan menggunakan bumbu marinasi untuk ${jumlah} ekor ayam?`);
}

function confirmLapis() {
    const jumlah = document.getElementById('jumlahKarung').value;
    if (!jumlah) {
        alert('Silakan pilih jumlah tepung lapis terlebih dahulu.');
        return false;
    }
    return confirm(`Anda yakin akan menggunakan ${jumlah} kg tepung lapis?`);
}
</script>
@endsection