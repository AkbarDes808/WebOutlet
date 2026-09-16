@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F5F6F8] px-3 py-4 sm:px-6 lg:px-8">

    <div class="mb-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">
                    History Bahan
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Riwayat penambahan dan penggunaan stok
                </p>
            </div>

            @if($isAdminOrSpv)
                <form method="GET" action="{{ route('bahans.history') }}">
                    <select
                        name="outlet"
                        onchange="this.form.submit()"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 sm:w-auto"
                    >
                        @foreach($outlets as $outlet)
                            <option
                                value="{{ strtolower($outlet) }}"
                                {{ strtolower($selectedOutlet) === strtolower($outlet) ? 'selected' : '' }}
                            >
                                {{ $outlet }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @else
                <div class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm">
                    {{ $selectedOutlet }}
                </div>
            @endif
        </div>
    </div>

    @if(empty($history))
        <div class="rounded-2xl bg-white p-8 text-center shadow-sm">
            <div class="text-4xl">📦</div>
            <p class="mt-3 font-semibold text-gray-800">
                Belum ada history
            </p>
            <p class="mt-1 text-sm text-gray-500">
                Belum ada penambahan atau penggunaan stok untuk outlet ini.
            </p>
        </div>
    @else
        <div class="space-y-4">

            @foreach($history as $record)
                @php
                    $items = $record['items'] ?? [];
                    $isUsage = ($record['type'] ?? '') === 'usage';
                @endphp

                <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-4 py-4 sm:px-5">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($record['created_at'])->format('d M Y, H:i:s') }}
                                    </span>

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                        {{ $record['nama_outlet'] }}
                                    </span>

                                    @if($isUsage)
                                        <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600">
                                            Penggunaan POS
                                        </span>
                                    @else
                                        <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-600">
                                            Penambahan
                                        </span>
                                    @endif
                                </div>

                                @if(!empty($record['order_number']))
                                    <div class="mt-1 text-xs text-gray-500">
                                        Order:
                                        <span class="font-semibold text-gray-700">
                                            {{ $record['order_number'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($record['transaction_id']))
                                <div class="text-xs text-gray-400">
                                    Transaksi #{{ $record['transaction_id'] }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="hidden overflow-x-auto md:block">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold text-gray-600">
                                        Item
                                    </th>
                                    <th class="px-5 py-3 text-right font-semibold text-gray-600">
                                        Perubahan
                                    </th>
                                    <th class="px-5 py-3 text-right font-semibold text-gray-600">
                                        Total
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach($items as $item)
                                    @php
                                        $change = (float) ($item['change'] ?? 0);
                                        $total = (float) ($item['total'] ?? 0);
                                    @endphp

                                    <tr>
                                        <td class="px-5 py-3 font-medium text-gray-800">
                                            {{ $item['nama'] }}
                                        </td>

                                        <td class="px-5 py-3 text-right">
                                            @if($change > 0)
                                                <span class="font-bold text-green-600">
                                                    +{{ number_format($change, 0, ',', '.') }}
                                                </span>
                                            @elseif($change < 0)
                                                <span class="font-bold text-red-600">
                                                    {{ number_format($change, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">0</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-3 text-right font-bold text-gray-900">
                                            {{ number_format($total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="divide-y divide-gray-100 md:hidden">
                        @foreach($items as $item)
                            @php
                                $change = (float) ($item['change'] ?? 0);
                                $total = (float) ($item['total'] ?? 0);
                            @endphp

                            <div class="flex items-center justify-between gap-3 px-4 py-3">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-semibold text-gray-800">
                                        {{ $item['nama'] }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        Total:
                                        <span class="font-semibold text-gray-700">
                                            {{ number_format($total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="shrink-0 text-right">
                                    @if($change > 0)
                                        <div class="font-bold text-green-600">
                                            +{{ number_format($change, 0, ',', '.') }}
                                        </div>
                                    @elseif($change < 0)
                                        <div class="font-bold text-red-600">
                                            {{ number_format($change, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="font-bold text-gray-400">
                                            0
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach

        </div>
    @endif
</div>
@endsection