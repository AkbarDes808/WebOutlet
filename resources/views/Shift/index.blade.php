@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <div class="bg-white rounded-2xl shadow p-6 border">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Tutup Shift</h1>
            <p class="text-gray-500 mt-1">Data otomatis dari transaksi hari ini</p>
        </div>

        <form action="{{ route('shift.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <label class="text-sm font-semibold">Outlet</label>
                    <input type="text" value="{{ $user->role }}" readonly
                        class="w-full border bg-gray-50 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">Kasir</label>
                    <input type="text" value="{{ $user->name }}" readonly
                        class="w-full border bg-gray-50 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">Tanggal</label>

                    <div class="w-full border bg-gray-50 px-4 py-3 rounded-lg text-gray-700">
                        {{ now()->format('d M Y') }}
                    </div>

                    <input type="hidden" name="tanggal"
                        value="{{ now()->format('Y-m-d') }}">
                </div>

                <div>
                    <label class="text-sm font-semibold">Total Transaksi</label>
                    <input type="number" name="total_transaksi"
                        value="{{ $totalTransaksi }}"
                        readonly class="w-full border bg-gray-100 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">Total Penjualan</label>
                    <input type="number" name="total_penjualan"
                        value="{{ $totalPenjualan }}"
                        readonly class="w-full border bg-gray-100 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">Cash</label>
                    <input type="number" name="cash_total"
                        value="{{ $cashTotal }}"
                        readonly class="w-full border bg-gray-100 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">QRIS</label>
                    <input type="number" name="qris_total"
                        value="{{ $qrisTotal }}"
                        readonly class="w-full border bg-gray-100 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">Order Cash</label>
                    <input type="number" name="cash_orders"
                        value="{{ $cashOrders }}"
                        readonly class="w-full border bg-gray-100 px-4 py-3 rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-semibold">Order QRIS</label>
                    <input type="number" name="qris_orders"
                        value="{{ $qrisOrders }}"
                        readonly class="w-full border bg-gray-100 px-4 py-3 rounded-lg">
                </div>

                <div>
    <label class="text-sm font-semibold">Uang Modal</label>

    <input
        type="number"
        id="uang_modal"
        name="uang_modal"
        value="0"
        class="w-full border px-4 py-3 rounded-lg">
            </div>

                <div>
                    <label class="text-sm font-semibold">
                        Actual Cash di Laci
                    </label>

                    <input
                        type="text"
                        id="actual_cash"
                        readonly
                        class="w-full border bg-blue-50 font-bold text-blue-700 px-4 py-3 rounded-lg">


                    <input
                        type="hidden"
                        name="actual_cash"
                        id="actual_cash_value">
                   </div>
                <div>
                    <label class="text-sm font-semibold">Pengeluaran Lainnya</label>
                    <input type="number" name="pengeluaran_lainnya"
                        value="0"
                        class="w-full border px-4 py-3 rounded-lg"
                        placeholder="Contoh: 50000">
                </div>

            </div>

            <div class="mt-6">
                <label class="text-sm font-semibold">Catatan</label>
                <textarea name="catatan"
                    class="w-full border px-4 py-3 rounded-lg"></textarea>
            </div>

            <div class="mt-8">
                <button class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold">
                    Tutup Shift
                </button>
            </div>

        </form>

    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerText = 'Memproses...';

    const res = await fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: new FormData(this)
    });

    const data = await res.json();

    if (data.success) {

        alert(data.message);

        // logout otomatis
        window.location.href = '/logout';

    } else {

        btn.disabled = false;
        btn.innerText = 'Tutup Shift';

        alert(data.message);
    }
});

function formatRupiah(angka) {

    return 'Rp ' +
        Number(angka).toLocaleString('id-ID');
}

const uangModal =
    document.getElementById('uang_modal');

const pengeluaran =
    document.querySelector(
        'input[name="pengeluaran_lainnya"]'
    );

const actualCash =
    document.getElementById('actual_cash');

const cashTotal =
    {{ $cashTotal }};

const totalKembalian =
    {{ \App\Models\Transaction::whereDate('created_at', now()->toDateString())
        ->whereRaw("LOWER(payment_method) = 'cash'")
        ->sum('change_amount') }};

function hitungActualCash() {

    const modal =
        parseFloat(
            uangModal.value || 0
        );


    const keluar =
        parseFloat(
            pengeluaran.value || 0
        );


    const hasil =
        modal +
        {{ $totalPenjualan }} -
        {{ $qrisTotal }} -
        keluar;


    actualCash.value =
        formatRupiah(hasil);


    document.getElementById(
        'actual_cash_value'
    ).value = hasil;

}

uangModal.addEventListener(
    'input',
    hitungActualCash
);

pengeluaran.addEventListener(
    'input',
    hitungActualCash
);

hitungActualCash();

</script>
@endsection