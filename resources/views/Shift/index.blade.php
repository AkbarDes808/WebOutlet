@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <div class="bg-white rounded-2xl shadow p-6 border">

        {{-- HEADER --}}
        <div class="mb-6">

            <h1 class="text-3xl font-bold text-gray-800">
                Tutup Shift
            </h1>

            <p class="text-gray-500 mt-1">
                Akhiri shift dan simpan laporan kas
            </p>

        </div>

        <form id="shiftForm" action="{{ route('shift.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- OUTLET --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Outlet
                    </label>

                    <input type="text"
                        value="{{ $user->role }}"
                        readonly
                        class="w-full rounded-lg border bg-gray-50 px-4 py-3">
                </div>

                {{-- KASIR --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Kasir
                    </label>

                    <input type="text"
                        value="{{ $user->name }}"
                        readonly
                        class="w-full rounded-lg border bg-gray-50 px-4 py-3">
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Tanggal
                    </label>

                    <input type="date"
                        name="tanggal"
                        value="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- WAKTU MULAI --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Waktu Mulai
                    </label>

                    <input type="time"
                        name="waktu_mulai"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- WAKTU SELESAI --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Waktu Selesai
                    </label>

                    <input type="time"
                        name="waktu_selesai"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- TOTAL TRANSAKSI --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Total Transaksi
                    </label>

                    <input type="number"
                        name="total_transaksi"
                        placeholder="24"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- TOTAL PENJUALAN --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Total Penjualan
                    </label>

                    <input type="number"
                        name="total_penjualan"
                        placeholder="1800000"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- CASH --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Total Cash
                    </label>

                    <input type="number"
                        name="cash_total"
                        placeholder="1200000"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- CASH ORDERS --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Jumlah Order Cash
                    </label>

                    <input type="number"
                        name="cash_orders"
                        placeholder="16"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- QRIS --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Total QRIS
                    </label>

                    <input type="number"
                        name="qris_total"
                        placeholder="600000"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- QRIS ORDERS --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Jumlah Order QRIS
                    </label>

                    <input type="number"
                        name="qris_orders"
                        placeholder="8"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- EXPECTED CASH --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Expected Cash di Laci
                    </label>

                    <input type="number"
                        name="expected_cash"
                        placeholder="1200000"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

                {{-- ACTUAL CASH --}}
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Uang Aktual di Laci
                    </label>

                    <input type="number"
                        name="actual_cash"
                        placeholder="Masukkan jumlah"
                        class="w-full rounded-lg border px-4 py-3">
                </div>

            </div>

            {{-- CATATAN --}}
            <div class="mt-6">

                <label class="block text-sm font-semibold mb-2">
                    Catatan (opsional)
                </label>

                <textarea
                    name="catatan"
                    rows="4"
                    placeholder="Tulis catatan shift jika ada..."
                    class="w-full rounded-lg border px-4 py-3"></textarea>

            </div>

            {{-- BUTTON --}}
            <div class="mt-8">

                <button
                    id="submitBtn"
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-semibold text-lg transition">

                    Tutup Shift

                </button>

            </div>

        </form>

    </div>

</div>

{{-- =========================
    LOADING OVERLAY
========================= --}}
<div id="loadingOverlay"
    class="hidden fixed inset-0 z-[9999] bg-black/40 backdrop-blur-sm items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl px-10 py-8 flex flex-col items-center gap-5 min-w-[320px]">

        {{-- SPINNER --}}
        <div id="loadingSpinner"
            class="w-16 h-16 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin">
        </div>

        {{-- ICON SUCCESS --}}
        <div id="successIcon"
            class="hidden w-16 h-16 rounded-full bg-green-100 text-green-600 items-center justify-center text-3xl font-bold">
            ✓
        </div>

        {{-- TEXT --}}
        <div class="text-center">

            <div id="loadingTitle"
                class="font-bold text-lg text-gray-800">
                Memproses tutup shift...
            </div>

            <div id="loadingText"
                class="text-sm text-gray-500 mt-1">
                Mohon tunggu sebentar
            </div>

        </div>

    </div>

</div>

{{-- =========================
    SCRIPT
========================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('shiftForm');

    const overlay = document.getElementById('loadingOverlay');

    const spinner = document.getElementById('loadingSpinner');

    const successIcon = document.getElementById('successIcon');

    const loadingTitle = document.getElementById('loadingTitle');

    const loadingText = document.getElementById('loadingText');

    const submitBtn = document.getElementById('submitBtn');

    let isSubmitting = false;

    form.addEventListener('submit', function () {

        if (isSubmitting) {

            event.preventDefault();

            return;
        }

        isSubmitting = true;

        submitBtn.disabled = true;

        submitBtn.classList.add('opacity-70');

        overlay.classList.remove('hidden');

        overlay.classList.add('flex');

        spinner.classList.remove('hidden');

        successIcon.classList.add('hidden');

        loadingTitle.innerText = 'Memproses tutup shift...';

        loadingText.innerText = 'Mohon tunggu sebentar';
    });

    @if(session('success'))

        overlay.classList.remove('hidden');

        overlay.classList.add('flex');

        spinner.classList.add('hidden');

        successIcon.classList.remove('hidden');

        successIcon.classList.add('flex');

        loadingTitle.innerText = 'Shift berhasil ditutup';

        loadingText.innerText = '{{ session('success') }}';

        setTimeout(() => {

            overlay.classList.add('hidden');

            overlay.classList.remove('flex');

        }, 2200);

    @endif

    @if(session('error'))

        overlay.classList.remove('hidden');

        overlay.classList.add('flex');

        spinner.classList.add('hidden');

        successIcon.classList.remove('flex');

        successIcon.classList.add('hidden');

        loadingTitle.innerText = 'Gagal menutup shift';

        loadingText.innerText = '{{ session('error') }}';

        setTimeout(() => {

            overlay.classList.add('hidden');

            overlay.classList.remove('flex');

        }, 2500);

    @endif

});

</script>

@endsection