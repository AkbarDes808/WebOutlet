@extends('layouts.app')

@section('content')

@php
    $isAdminOrSpv = in_array(
        strtolower(auth()->user()->role),
        ['admin', 'spv']
    );
@endphp

<div class="p-4 md:p-6">

    <div class="bg-white rounded-2xl shadow-sm border p-4 md:p-6">

        {{-- HEADER --}}
        <div class="mb-6">

            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Riwayat Shift
            </h1>

            <p class="text-gray-500 mt-1 text-sm md:text-base">
                {{ $isAdminOrSpv
                    ? 'Laporan tutup shift dari seluruh outlet'
                    : 'Laporan tutup shift outlet Anda'
                }}
            </p>

        </div>

        {{-- FILTER --}}
        <form
            method="GET"
            action="{{ route('shift.history') }}"
            class="grid grid-cols-1 md:flex gap-3 mb-6">

            {{-- OUTLET --}}
            @if($isAdminOrSpv)

            <select
                name="outlet"
                class="border rounded-lg px-4 py-2 text-sm">

                <option value="">
                    -- Semua Outlet --
                </option>

                @foreach(($outlets ?? []) as $outlet)

                <option
                    value="{{ $outlet }}"
                    {{ request('outlet') == $outlet ? 'selected' : '' }}>

                    {{ ucfirst($outlet) }}

                </option>

                @endforeach

            </select>

            @endif

            {{-- DATE FROM --}}
            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="border rounded-lg px-4 py-2 text-sm">

            {{-- DATE TO --}}
            <input
                type="date"
                name="to"
                value="{{ request('to') }}"
                class="border rounded-lg px-4 py-2 text-sm">

            {{-- KASIR --}}
            <select
                name="kasir"
                class="border rounded-lg px-4 py-2 text-sm">

                <option value="">
                    Semua Kasir
                </option>

                @foreach(($kasirs ?? []) as $kasir)

                <option
                    value="{{ $kasir }}"
                    {{ request('kasir') == $kasir ? 'selected' : '' }}>

                    {{ $kasir }}

                </option>

                @endforeach

            </select>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">

                Filter

            </button>

        </form>

        {{-- ========================= --}}
        {{-- MOBILE VIEW --}}
        {{-- ========================= --}}
        <div class="md:hidden space-y-4">

            @forelse($shiftClosings as $shift)

            @php

                $selisih =
                    $shift->actual_cash -
                    $shift->expected_cash;

                if($shift->waktu_mulai < '12:00'){
                    $shiftLabel = 'Pagi';
                }elseif($shift->waktu_mulai < '18:00'){
                    $shiftLabel = 'Siang';
                }else{
                    $shiftLabel = 'Malam';
                }

            @endphp

            <div class="border rounded-xl p-4 shadow-sm bg-white">

                <div class="flex justify-between items-start mb-3">

                    <div>

                        <h3 class="font-semibold text-gray-800">
                            {{ ucfirst($shift->outlet) }}
                        </h3>

                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($shift->tanggal)->format('d M Y') }}
                        </p>

                    </div>

                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        {{ $shiftLabel }}
                    </span>

                </div>

                <div class="space-y-2 text-sm">

                    <div class="flex justify-between">
                        <span class="text-gray-500">Kasir</span>
                        <span>{{ $shift->kasir }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Pesanan</span>
                        <span>{{ $shift->total_transaksi }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Cash</span>
                        <span>
                            Rp {{ number_format($shift->cash_total,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">QRIS</span>
                        <span>
                            Rp {{ number_format($shift->qris_total,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between font-semibold">
                        <span>Total</span>
                        <span>
                            Rp {{ number_format($shift->total_penjualan,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Expected</span>
                        <span>
                            Rp {{ number_format($shift->expected_cash,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Aktual</span>
                        <span>
                            Rp {{ number_format($shift->actual_cash,0,',','.') }}
                        </span>
                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Selisih
                        </span>

                        @if($selisih == 0)

                            <span class="text-green-600 font-semibold">
                                Rp 0
                            </span>

                        @elseif($selisih < 0)

                            <span class="text-red-600 font-semibold">
                                -Rp {{ number_format(abs($selisih),0,',','.') }}
                            </span>

                        @else

                            <span class="text-blue-600 font-semibold">
                                +Rp {{ number_format($selisih,0,',','.') }}
                            </span>

                        @endif

                    </div>

                </div>

                {{-- DETAIL BUTTON --}}
                <div class="mt-4 pt-3 border-t">

                    <button
                        type="button"
                        onclick="showDetail(
                            '{{ \Carbon\Carbon::parse($shift->tanggal)->format('d M Y') }}',
                            '{{ $shift->kasir }}',
                            '{{ ucfirst($shift->outlet) }}',
                            '{{ $shiftLabel }}',
                            '{{ $shift->waktu_mulai }}',
                            '{{ $shift->waktu_selesai }}',
                            '{{ $shift->total_transaksi }}',
                            '{{ number_format($shift->cash_total,0,',','.') }}',
                            '{{ number_format($shift->qris_total,0,',','.') }}',
                            '{{ number_format($shift->total_penjualan,0,',','.') }}',
                            '{{ number_format($shift->expected_cash,0,',','.') }}',
                            '{{ number_format($shift->actual_cash,0,',','.') }}',
                            '{{ $shift->catatan ?? '-' }}',
                            '{{ $selisih }}'
                        )"
                        class="text-blue-600 hover:text-blue-800 font-medium">
                        Detail
                    </button>

                </div>

            </div>

            @empty

            <div class="text-center py-10 text-gray-400">
                Belum ada data history shift
            </div>

            @endforelse

        </div>

        {{-- ========================= --}}
        {{-- DESKTOP TABLE --}}
        {{-- ========================= --}}
        <div class="hidden md:block overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="border-b text-gray-500 uppercase text-xs">

                    <tr>

                        <th class="py-4 text-left">Tanggal</th>
                        <th class="text-left">Outlet</th>
                        <th class="text-left">Kasir</th>
                        <th class="text-left">Shift</th>
                        <th class="text-left">Pesanan</th>
                        <th class="text-left">Cash</th>
                        <th class="text-left">QRIS</th>
                        <th class="text-left">Total</th>
                        <th class="text-left">Expected</th>
                        <th class="text-left">Aktual</th>
                        <th class="text-left">Selisih</th>
                        <th class="text-left">Aksi</th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($shiftClosings as $shift)

                    @php

                        $selisih =
                            $shift->actual_cash -
                            $shift->expected_cash;

                        if($shift->waktu_mulai < '12:00'){
                            $shiftLabel = 'Pagi';
                        }elseif($shift->waktu_mulai < '18:00'){
                            $shiftLabel = 'Siang';
                        }else{
                            $shiftLabel = 'Malam';
                        }

                    @endphp

                    <tr class="hover:bg-gray-50">

                        <td class="py-4">
                            {{ \Carbon\Carbon::parse($shift->tanggal)->format('d M Y') }}
                        </td>

                        <td>{{ ucfirst($shift->outlet) }}</td>

                        <td>{{ $shift->kasir }}</td>

                        <td>{{ $shiftLabel }}</td>

                        <td>{{ $shift->total_transaksi }}</td>

                        <td>
                            Rp {{ number_format($shift->cash_total,0,',','.') }}
                        </td>

                        <td>
                            Rp {{ number_format($shift->qris_total,0,',','.') }}
                        </td>

                        <td class="font-semibold">
                            Rp {{ number_format($shift->total_penjualan,0,',','.') }}
                        </td>

                        <td>
                            Rp {{ number_format($shift->expected_cash,0,',','.') }}
                        </td>

                        <td>
                            Rp {{ number_format($shift->actual_cash,0,',','.') }}
                        </td>

                        <td>

                            @if($selisih == 0)

                                <span class="text-green-600 font-semibold">
                                    Rp 0
                                </span>

                            @elseif($selisih < 0)

                                <span class="text-red-600 font-semibold">
                                    -Rp {{ number_format(abs($selisih),0,',','.') }}
                                </span>

                            @else

                                <span class="text-blue-600 font-semibold">
                                    +Rp {{ number_format($selisih,0,',','.') }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <button
                                type="button"
                                onclick="showDetail(
                                    '{{ \Carbon\Carbon::parse($shift->tanggal)->format('d M Y') }}',
                                    '{{ $shift->kasir }}',
                                    '{{ ucfirst($shift->outlet) }}',
                                    '{{ $shiftLabel }}',
                                    '{{ $shift->waktu_mulai }}',
                                    '{{ $shift->waktu_selesai }}',
                                    '{{ $shift->total_transaksi }}',
                                    '{{ number_format($shift->cash_total,0,',','.') }}',
                                    '{{ number_format($shift->qris_total,0,',','.') }}',
                                    '{{ number_format($shift->total_penjualan,0,',','.') }}',
                                    '{{ number_format($shift->expected_cash,0,',','.') }}',
                                    '{{ number_format($shift->actual_cash,0,',','.') }}',
                                    '{{ $shift->catatan ?? '-' }}',
                                    '{{ $selisih }}'
                                )"
                                class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                Lihat Detail
                            </button>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="12"
                            class="text-center py-12 text-gray-400">

                            Belum ada data history shift

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">

            <div class="text-sm text-gray-500 mb-3">

                Menampilkan
                {{ $shiftClosings->firstItem() ?? 0 }}
                -
                {{ $shiftClosings->lastItem() ?? 0 }}

                dari
                {{ $shiftClosings->total() }}
                shift

            </div>

            {{ $shiftClosings->withQueryString()->links('pagination::tailwind') }}

        </div>

    </div>

</div>
{{-- MODAL DETAIL SHIFT --}}
<div id="detailModal"
    class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">

    <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-3xl font-bold text-gray-900">
                Detail Shift
            </h2>

            <button onclick="closeDetail()"
                class="text-gray-400 hover:text-gray-600 text-2xl">
                ×
            </button>

        </div>

        <div class="border rounded-xl p-5 mb-5 bg-gray-50">

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <p class="text-gray-500">
                        Tanggal:
                        <span id="dTanggal" class="font-bold text-black"></span>
                    </p>

                    <p class="text-gray-500 mt-2">
                        Kasir:
                        <span id="dKasir" class="font-bold text-black"></span>
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">
                        Shift:
                        <span id="dShift" class="font-bold text-black"></span>
                    </p>

                    <p class="text-gray-500 mt-2">
                        Outlet:
                        <span id="dOutlet" class="font-bold text-black"></span>
                    </p>
                </div>

            </div>

        </div>

        <div class="border rounded-xl p-5 mb-5">

            <h3 class="font-semibold text-gray-600 mb-4">
                Ringkasan Penjualan
            </h3>

            <div class="space-y-3">

                <div class="flex justify-between border-b pb-2">
                    <span>Total Pesanan</span>
                    <span id="dPesanan" class="font-semibold"></span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span>Cash</span>
                    <span id="dCash" class="font-semibold"></span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span>QRIS</span>
                    <span id="dQris" class="font-semibold"></span>
                </div>

                <div class="flex justify-between">
                    <span class="font-bold">
                        Total Penjualan
                    </span>

                    <span id="dTotal"
                        class="font-bold text-green-600">
                    </span>
                </div>

            </div>

        </div>

        <div class="border rounded-xl p-5 mb-5">

            <h3 class="font-semibold text-gray-600 mb-4">
                Kas / Cash Drawer
            </h3>

            <div class="space-y-3">

                <div class="flex justify-between border-b pb-2">
                    <span>Expected Cash</span>
                    <span id="dExpected" class="font-semibold"></span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span>Actual Cash</span>
                    <span id="dActual" class="font-semibold"></span>
                </div>

                <div class="flex justify-between">
                    <span>Selisih</span>
                    <span id="dSelisih" class="font-semibold"></span>
                </div>

            </div>

        </div>

        <div class="border rounded-xl p-5">

            <h3 class="font-semibold text-gray-600 mb-3">
                Catatan Kasir
            </h3>

            <p id="dCatatan"
                class="italic text-gray-700">
            </p>

        </div>

    </div>

</div>

<script>

function showDetail(
    tanggal,
    kasir,
    outlet,
    shift,
    mulai,
    selesai,
    pesanan,
    cash,
    qris,
    total,
    expected,
    actual,
    catatan,
    selisih
){

    document.getElementById('dTanggal').innerText = tanggal;
    document.getElementById('dKasir').innerText = kasir;
    document.getElementById('dOutlet').innerText = outlet;

    document.getElementById('dShift').innerText =
        shift + ' (' + mulai + ' - ' + selesai + ')';

    document.getElementById('dPesanan').innerText =
        pesanan + ' pesanan';

    document.getElementById('dCash').innerText =
        'Rp ' + cash;

    document.getElementById('dQris').innerText =
        'Rp ' + qris;

    document.getElementById('dTotal').innerText =
        'Rp ' + total;

    document.getElementById('dExpected').innerText =
        'Rp ' + expected;

    document.getElementById('dActual').innerText =
        'Rp ' + actual;

    let selisihEl = document.getElementById('dSelisih');

    if(parseInt(selisih) < 0){

        selisihEl.className =
            'font-semibold text-orange-500';

        selisihEl.innerText =
            '-Rp ' + Math.abs(parseInt(selisih))
            .toLocaleString('id-ID');

    }else if(parseInt(selisih) > 0){

        selisihEl.className =
            'font-semibold text-blue-600';

        selisihEl.innerText =
            '+Rp ' + parseInt(selisih)
            .toLocaleString('id-ID');

    }else{

        selisihEl.className =
            'font-semibold text-green-600';

        selisihEl.innerText = 'Rp 0';
    }

    document.getElementById('dCatatan').innerText =
        catatan || '-';

    document.getElementById('detailModal')
        .classList.remove('hidden');

    document.getElementById('detailModal')
        .classList.add('flex');
}

function closeDetail(){

    document.getElementById('detailModal')
        .classList.add('hidden');

    document.getElementById('detailModal')
        .classList.remove('flex');
}

</script>

@endsection