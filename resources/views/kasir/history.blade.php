@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl p-6 shadow-sm">

    <!-- TITLE -->
    <h1 class="text-xl font-semibold text-gray-800">Riwayat Pesanan</h1>
    <p class="text-sm text-gray-500 mb-4">
        Semua transaksi penjualan dari kasir
    </p>

    <!-- FILTER -->
    <div class="flex flex-wrap items-center gap-3 mb-6">

        <!-- Outlet -->
        <select class="border rounded-lg px-3 py-2 text-sm text-gray-600">
            <option>-- Semua Outlet --</option>
        </select>

        <!-- Date -->
        <input type="date" class="border rounded-lg px-3 py-2 text-sm text-gray-600">
        <span class="text-gray-400 text-sm">s/d</span>
        <input type="date" class="border rounded-lg px-3 py-2 text-sm text-gray-600">

        <!-- Status -->
        <select class="border rounded-lg px-3 py-2 text-sm text-gray-600">
            <option>Semua Status</option>
        </select>

        <!-- Metode -->
        <select class="border rounded-lg px-3 py-2 text-sm text-gray-600">
            <option>Semua Metode</option>
        </select>

        <!-- Button -->
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Filter
        </button>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="text-gray-500 text-xs uppercase border-b">
                <tr>
                    <th class="py-3 text-left">Tanggal</th>
                    <th class="text-left">Outlet</th>
                    <th class="text-left">Order #</th>
                    <th class="text-left">Kasir</th>
                    <th class="text-left">Items</th>
                    <th class="text-left">Metode</th>
                    <th class="text-left">Total</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                @foreach($transactions as $trx)
                <tr class="border-b hover:bg-gray-50">

                    <!-- Tanggal -->
                    <td class="py-3">
                        {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}
                    </td>

                    <!-- Outlet -->
                    <td>{{ $trx->nama_outlet }}</td>

                    <!-- Order -->
                    <td class="text-blue-600 font-medium">
                        #{{ $trx->order_number }}
                    </td>

                    <!-- Kasir -->
                    <td>{{ $trx->kasir }}</td>

                    <!-- Items -->
                    <td>{{ $trx->items_count ?? 0 }} items</td>

                    <!-- Metode -->
                    <td>{{ $trx->payment_method ?? 'Cash' }}</td>

                    <!-- Total -->
                    <td class="font-semibold">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>

                    <!-- Status -->
                    <td>
                        @if($trx->status == 'paid')
                            <span class="text-gray-700">Selesai</span>
                        @else
                            <span class="text-red-500">Void</span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td>
                        <a href="#" class="text-blue-600 hover:underline text-sm">
                            Detail
                        </a>
                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>
    </div>

    <!-- FOOTER -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-500">

        <p>
            Menampilkan {{ $transactions->firstItem() ?? 0 }}-
            {{ $transactions->lastItem() ?? 0 }}
            dari {{ $transactions->total() }} transaksi
        </p>

        <div class="flex gap-2">
            {{ $transactions->links('pagination::tailwind') }}
        </div>

    </div>

</div>

@endsection