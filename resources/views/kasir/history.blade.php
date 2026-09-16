@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-[#F5F6F8] px-3 py-4 sm:px-6 lg:px-8">
    <div class="mb-5">
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Riwayat Pesanan</h1>
            <p class="mt-1 text-sm text-gray-500">Semua transaksi penjualan dari kasir</p>
        </div>
    <form method="GET" action="{{ url()->current() }}" class="rounded-2xl bg-white p-4 shadow-sm">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <select name="outlet" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 outline-none focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-100">
                <option value="">Semua Outlet</option>
                @foreach($outlets ?? [] as $outlet)
                    <option value="{{ $outlet }}" {{ request('outlet') == $outlet ? 'selected' : '' }}>
                        {{ $outlet }}
                    </option>
                @endforeach
            </select>

            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-100"
            >

            <input
                type="date"
                name="to"
                value="{{ request('to') }}"
                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-100"
            >

            <select name="status" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 outline-none focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-100">
                <option value="">Semua Status</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Selesai</option>
                <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>Void</option>
            </select>

            <select name="payment_method" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 outline-none focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-100">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>

            <button
                type="submit"
                class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
            >
                Filter
            </button>
        </div>
    </form>
</div>

@if($transactions->count() === 0)
    <div class="rounded-2xl bg-white p-10 text-center shadow-sm">
        <div class="text-4xl">🧾</div>
        <p class="mt-3 font-semibold text-gray-800">Belum ada transaksi</p>
        <p class="mt-1 text-sm text-gray-500">
            Belum ada transaksi yang sesuai dengan filter yang dipilih.
        </p>
    </div>
@else
    <div class="space-y-3">
        @foreach($transactions as $trx)
            @php
                $paymentMethod = strtolower($trx->payment_method ?? 'cash');
                $status = strtolower($trx->status ?? '');
                $createdAt = \Carbon\Carbon::parse($trx->created_at);
            @endphp

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:shadow-md">
                <div class="px-4 py-4 sm:px-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-bold text-gray-900">
                                    {{ $createdAt->format('d M Y') }}
                                </span>

                                <span class="text-sm text-gray-400">•</span>

                                <span class="text-sm font-medium text-gray-500">
                                    {{ $createdAt->format('H:i:s') }}
                                </span>

                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                    {{ $trx->nama_outlet }}
                                </span>

                                @if($status === 'paid')
                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-600">
                                        Selesai
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600">
                                        Void
                                    </span>
                                @endif

                                @if($paymentMethod === 'qris')
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                        QRIS
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                        CASH
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-3">
                                <div class="rounded-xl bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] font-medium uppercase tracking-wide text-gray-400">
                                        Order
                                    </div>
                                    <div class="mt-0.5 truncate text-sm font-semibold text-gray-800">
                                        #{{ $trx->order_number }}
                                    </div>
                                </div>

                                <div class="rounded-xl bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] font-medium uppercase tracking-wide text-gray-400">
                                        Kasir
                                    </div>
                                    <div class="mt-0.5 truncate text-sm font-semibold text-gray-800">
                                        {{ $trx->kasir }}
                                    </div>
                                </div>

                                <div class="rounded-xl bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] font-medium uppercase tracking-wide text-gray-400">
                                        Items
                                    </div>
                                    <div class="mt-0.5 text-sm font-semibold text-gray-800">
                                        {{ $trx->items_count ?? 0 }} item
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-4 lg:min-w-[240px] lg:border-l lg:border-t-0 lg:pl-5 lg:pt-0">
                            <div>
                                <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Total
                                </div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </div>
                                <div class="mt-1 text-xs text-gray-400">
                                    Transaksi #{{ $trx->id }}
                                </div>
                            </div>

                            <button
                                type="button"
                                class="btn-detail rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600"
                                data-id="{{ $trx->id }}"
                            >
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @php
        $currentPage = $transactions->currentPage();
        $lastPage = $transactions->lastPage();
        $startPage = max(1, min($currentPage - 2, $lastPage - 4));
        $endPage = min($lastPage, $startPage + 4);
    @endphp

    <div class="mt-4 rounded-2xl bg-white px-4 py-4 shadow-sm sm:px-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-gray-500">
                Menampilkan
                <span class="font-semibold text-gray-800">{{ $transactions->firstItem() ?? 0 }}</span>
                -
                <span class="font-semibold text-gray-800">{{ $transactions->lastItem() ?? 0 }}</span>
                dari
                <span class="font-semibold text-gray-800">{{ $transactions->total() }}</span>
                transaksi
            </div>

            @if($lastPage > 1)
                <div class="flex items-center gap-1">
                    @if($transactions->onFirstPage())
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-300">
                            ‹
                        </span>
                    @else
                        <a
                            href="{{ $transactions->appends(request()->query())->previousPageUrl() }}"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50"
                        >
                            ‹
                        </a>
                    @endif

                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if($page == $currentPage)
                            <span class="flex h-9 min-w-9 items-center justify-center rounded-lg bg-red-600 px-3 text-sm font-semibold text-white">
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $transactions->appends(request()->query())->url($page) }}"
                                class="flex h-9 min-w-9 items-center justify-center rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-gray-600 transition hover:bg-red-50 hover:text-red-600"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if($transactions->hasMorePages())
                        <a
                            href="{{ $transactions->appends(request()->query())->nextPageUrl() }}"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50"
                        >
                            ›
                        </a>
                    @else
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-300">
                            ›
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function closeModal() {
        const modal = document.getElementById('detail-modal');
        if (modal) modal.remove();
    }

    function rupiah(value) {
        return 'Rp ' + Number(value ?? 0).toLocaleString('id-ID');
    }

    document.addEventListener('click', async e => {
        const btn = e.target.closest('.btn-detail');
        if (!btn) return;

        const id = btn.dataset.id;

        closeModal();

        document.body.insertAdjacentHTML('beforeend', `
            <div id="detail-modal" class="fixed inset-0 z-[9999] bg-black/50 flex items-center justify-center p-4">
                <div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-xl">
                    <div class="flex flex-col items-center gap-4">
                        <div class="h-10 w-10 animate-spin rounded-full border-4 border-red-600 border-t-transparent"></div>
                        <div class="text-sm text-gray-600">Memuat detail...</div>
                    </div>
                </div>
            </div>
        `);

        try {
            const res = await fetch('/transactions/' + id + '/detail');

            if (!res.ok) {
                throw new Error('Gagal mengambil detail transaksi');
            }

            const data = await res.json();
            let itemsHTML = '';

            (data.items ?? []).forEach(item => {
                itemsHTML += `
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 py-3 last:border-0">
                        <div class="min-w-0 flex-1">
                            <div class="break-words text-sm font-semibold text-gray-800">
                                ${item.menu_name}
                            </div>
                            <div class="mt-0.5 text-xs text-gray-500">
                                ${item.qty}x
                            </div>
                        </div>
                        <div class="shrink-0 text-sm font-semibold text-gray-800">
                            ${rupiah(item.subtotal)}
                        </div>
                    </div>
                `;
            });

            document.getElementById('detail-modal').innerHTML = `
                <div class="flex h-full w-full items-center justify-center p-4">
                    <div class="w-full max-w-md max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Detail Transaksi</h2>
                                <p class="mt-0.5 text-xs text-gray-500">#${data.trx.order_number}</p>
                            </div>

                            <button
                                id="close-modal"
                                type="button"
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-100 text-xl text-gray-500 transition hover:bg-gray-200"
                            >
                                &times;
                            </button>
                        </div>

                        <div class="max-h-[calc(90vh-80px)] overflow-y-auto p-5">
                            <div class="rounded-xl bg-gray-50 p-4">
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <div class="text-xs text-gray-400">Kasir</div>
                                        <div class="mt-1 font-semibold text-gray-800">${data.trx.kasir_name}</div>
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-400">Outlet</div>
                                        <div class="mt-1 font-semibold text-gray-800">${data.trx.nama_outlet}</div>
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-400">Metode</div>
                                        <div class="mt-1 font-semibold uppercase text-gray-800">${data.trx.payment_method ?? 'cash'}</div>
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-400">Status</div>
                                        <div class="mt-1 font-semibold text-gray-800">${data.trx.status ?? 'paid'}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="mb-2 text-xs font-bold uppercase tracking-wide text-gray-400">
                                    Pesanan
                                </div>

                                <div class="rounded-xl border border-gray-100 px-4">
                                    ${itemsHTML}
                                </div>
                            </div>

                            <div class="mt-5 rounded-xl bg-gray-50 p-4">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between gap-4 text-gray-600">
                                        <span>Bayar</span>
                                        <span class="font-medium">${rupiah(data.trx.payment_amount)}</span>
                                    </div>

                                    <div class="flex justify-between gap-4 text-gray-600">
                                        <span>Kembalian</span>
                                        <span class="font-medium">${rupiah(data.trx.change_amount)}</span>
                                    </div>

                                    <div class="mt-3 flex justify-between gap-4 border-t border-gray-200 pt-3">
                                        <span class="font-bold text-gray-900">TOTAL</span>
                                        <span class="font-bold text-red-600">${rupiah(data.trx.total)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('close-modal').onclick = closeModal;
        } catch (error) {
            console.error(error);

            document.getElementById('detail-modal').innerHTML = `
                <div class="flex h-full w-full items-center justify-center p-4">
                    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
                        <div class="text-4xl">⚠️</div>
                        <div class="mt-3 font-semibold text-gray-800">
                            Gagal mengambil detail
                        </div>
                        <div class="mt-1 text-sm text-gray-500">
                            Detail transaksi tidak dapat dimuat.
                        </div>

                        <button
                            type="button"
                            id="close-modal"
                            class="mt-5 rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('close-modal').onclick = closeModal;
        }
    });

    document.addEventListener('click', e => {
        const modal = document.getElementById('detail-modal');

        if (modal && e.target === modal) {
            closeModal();
        }
    });
});
</script>

@endsection
