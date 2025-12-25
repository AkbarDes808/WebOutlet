@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ===================== NOTIFIKASI ===================== --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===================== HEADER ===================== --}}
    <div class="mb-8">
        <h1 class="text-xl sm:text-2xl font-bold mb-4">
            Produksi Tepung Marinasi & Tepung Lapis
        </h1>

        {{-- TAB NAVIGATION --}}
        <div class="inline-flex rounded-xl bg-gray-200 p-1">
            <a
                href="{{ route('marinasi.index') }}"
                class="px-3 sm:px-4 py-1.5 text-sm rounded-lg transition-all duration-200
                {{ request()->routeIs('marinasi.index')
                    ? 'bg-white text-black shadow-md font-medium'
                    : 'text-gray-600 hover:bg-gray-300' }}"
            >
                Masukkan Bumbu
            </a>

            <a
                href="{{ route('marinasi.produksi') }}"
                class="px-3 sm:px-4 py-1.5 text-sm rounded-lg transition-all duration-200
                {{ request()->routeIs('marinasi.produksi')
                    ? 'bg-white text-black shadow-md font-medium'
                    : 'text-gray-600 hover:bg-gray-300' }}"
            >
                Gunakan Bumbu & History
            </a>
        </div>
    </div>

    @php
        $berulang = ['C','J','L','M','N'];
    @endphp

    {{-- ================= GRID FORM ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        {{-- ================= FORM MARINASI ================= --}}
        <form method="POST"
              action="{{ route('produksi.marinasi.use') }}"
              onsubmit="return confirmBahan(this, 'Marinasi')"
              class="border rounded-xl p-5">
            @csrf

            <input type="hidden" name="jenis_form" value="marinasi">

            <h2 class="text-base sm:text-lg font-semibold mb-6 border-b pb-2">
                Tepung Marinasi (A – O)
            </h2>

            <div class="space-y-4">
                @foreach(range('A','O') as $huruf)
                    <div class="p-4 rounded-lg bg-gray-50 border">
                        <div class="mb-2 font-medium text-gray-800 text-sm">
                            {{ in_array($huruf, $berulang)
                                ? "Marinasi & Lapis $huruf"
                                : "Marinasi $huruf" }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Jumlah (gr)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="marinasi[{{ $huruf }}]"
                                    class="bahan-input w-full border rounded-md px-3 py-2
                                    focus:outline-none focus:ring-1 focus:ring-gray-400"
                                >
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Sisa Stok (gr)
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ number_format($stok[$huruf] ?? 0, 2, ',', '.') }}"
                                    class="w-full text-center bg-gray-100 border rounded-md
                                    px-3 py-2 text-gray-600"
                                >
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="w-full bg-black text-white py-3 rounded-lg
                    hover:bg-gray-800 transition"
                >
                    Simpan Marinasi
                </button>
            </div>
        </form>

        {{-- ================= FORM LAPIS ================= --}}
        <form method="POST"
              action="{{ route('produksi.marinasi.use') }}"
              onsubmit="return confirmBahan(this, 'Lapis')"
              class="border rounded-xl p-5">
            @csrf

            <input type="hidden" name="jenis_form" value="lapis">

            <h2 class="text-base sm:text-lg font-semibold mb-6 border-b pb-2">
                Tepung Lapis (C, J, L, M, N, P – S)
            </h2>

            <div class="space-y-4">
                @foreach(range('P','S') as $huruf)
                    <div class="p-4 rounded-lg bg-gray-50 border">
                        <div class="mb-2 font-medium text-gray-800 text-sm">
                            {{ in_array($huruf, $berulang)
                                ? "Marinasi & Lapis $huruf"
                                : "Lapis $huruf" }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Jumlah (gr)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="lapis[{{ $huruf }}]"
                                    class="bahan-input w-full border rounded-md px-3 py-2
                                    focus:outline-none focus:ring-1 focus:ring-gray-400"
                                >
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">
                                    Sisa Stok (gr)
                                </label>
                                <input
                                    type="text"
                                    readonly
                                    value="{{ number_format($stok[$huruf] ?? 0, 2, ',', '.') }}"
                                    class="w-full text-center bg-gray-100 border rounded-md
                                    px-3 py-2 text-gray-600"
                                >
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="w-full bg-black text-white py-3 rounded-lg
                    hover:bg-gray-800 transition"
                >
                    Simpan Lapisan
                </button>
            </div>
        </form>

    </div>
</div>

{{-- ================= SCRIPT KONFIRMASI (TETAP) ================= --}}
<script>
function confirmBahan(form, jenis) {
    const inputs = form.querySelectorAll('.bahan-input');
    let list = [];
    let total = 0;

    inputs.forEach(input => {
        const val = parseFloat(input.value);
        if (val > 0) {
            const name = input.name.match(/\[(.*?)\]/)[1];
            list.push(`• ${name} : ${val} gr`);
            total += val;
        }
    });

    if (list.length === 0) {
        alert('Tidak ada bahan yang diisi.');
        return false;
    }

    const message =
        `Konfirmasi ${jenis}\n\n` +
        `Bahan yang dimasukkan:\n` +
        list.join('\n') +
        `\n\nTotal: ${total.toLocaleString('id-ID')} gr\n\n` +
        `Lanjutkan penyimpanan?`;

    return confirm(message);
}
</script>
@endsection