@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@php
$user = Auth::user();
$role = strtolower(trim($user->role ?? ''));

 
$isAdmin = $role === 'admin';
$isSpv = $role === 'spv';
$isOutletUser = str_contains($role, 'outlet');

$outletMap = [
    'outlet 1' => 'Outlet 1',
    'outlet 2' => 'Outlet 2',
    'outlet 3' => 'Outlet 3',
    'outlet 4' => 'Outlet 4',
    'outlet 5' => 'Outlet 5',
    'outlet 6' => 'Outlet 6',
    'outlet 7' => 'Outlet 7',
];

$userOutlet = $outletMap[$role] ?? null;

$transactionOutlet = $userOutlet ?? request('outlet');
 

@endphp

<div class="bg-gray-100 flex flex-col h-full min-h-0">

 
{{-- HEADER --}}
<div class="h-[70px] px-4 bg-white border-b flex items-center justify-between">

    <div>
        <h1 class="text-lg font-bold">
            Kasir
        </h1>

        <p class="text-xs text-gray-500">
            {{ $user->name ?? 'User' }}
        </p>
    </div>

    <a
        href="{{ route('shift.index') }}"
        class="border px-3 py-1 rounded text-xs hover:bg-gray-100"
    >
        Tutup Shift
    </a>

</div>


{{-- =========================================================
     OUTLET TRANSAKSI
     ========================================================= --}}

@if($isAdmin || $isSpv)

    <div class="px-3 pt-3 lg:px-6 lg:pt-4">

        <div class="bg-white rounded-xl shadow-sm border p-4">

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">

                <div class="flex-1">

                    <label
                        for="transaction-outlet"
                        class="block text-sm font-semibold text-gray-700 mb-1"
                    >
                        Outlet Transaksi
                    </label>

                    <p class="text-xs text-gray-500">
                        Pilih outlet yang akan menerima transaksi dan pengurangan stok.
                    </p>

                </div>

                <div class="w-full sm:w-64">

                    <select
                        id="transaction-outlet"
                        name="outlet"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                        <option value="">
                            -- Pilih Outlet --
                        </option>

                        @for($i = 1; $i <= 7; $i++)

                            @php
                                $outletName = 'Outlet ' . $i;
                            @endphp

                            <option
                                value="{{ $outletName }}"
                                {{ $transactionOutlet === $outletName ? 'selected' : '' }}
                            >
                                {{ $outletName }}
                            </option>

                        @endfor

                    </select>

                </div>

            </div>

        </div>

    </div>

@else

    {{-- =====================================================
         OUTLET USER
         ===================================================== --}}

    <input
        type="hidden"
        id="transaction-outlet"
        name="outlet"
        value="{{ $userOutlet }}"
    >

    <div class="px-3 pt-3 lg:px-6 lg:pt-4">

        <div class="bg-white rounded-xl shadow-sm border px-4 py-3">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs text-gray-500">
                        Outlet Transaksi
                    </p>

                    <p class="font-semibold text-sm text-gray-800">
                        {{ $userOutlet ?? 'Outlet tidak diketahui' }}
                    </p>

                </div>

                <div class="text-xl">
                    🏪
                </div>

            </div>

        </div>

    </div>

@endif


{{-- =========================================================
     LAYOUT
     ========================================================= --}}

<div class="p-3 lg:p-6 flex gap-3">


    {{-- =====================================================
         PRODUK
         ===================================================== --}}

    <div class="w-1/2 lg:flex-1 space-y-2">

        @forelse($menus as $menu)

            <div class="bg-white rounded-lg shadow-sm p-3 flex justify-between items-center">

                <div>

                    <div class="font-semibold text-sm">
                        {{ $menu->name }}
                    </div>

                    <div class="text-xs text-blue-600 font-semibold">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </div>

                    <div class="text-xs text-gray-400">
                        Stok: {{ $menu->stock ?? '-' }}
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-add text-white text-sm w-9 h-9 rounded-lg flex items-center justify-center"
                    style="background:#3b82f6;"
                    data-id="{{ $menu->id }}"
                    data-name="{{ $menu->name }}"
                    data-price="{{ $menu->price }}"
                >
                    +
                </button>

            </div>

        @empty

            <div class="text-center text-gray-500 text-sm">
                Belum ada menu
            </div>

        @endforelse

    </div>


    {{-- =====================================================
         CART MOBILE
         ===================================================== --}}

    <div class="w-1/2 lg:hidden bg-white rounded-xl shadow flex flex-col flex-1 min-h-0 overflow-hidden">

        <div class="p-3 border-b font-semibold text-sm">
            Pesanan
        </div>

        <div
            id="cart-items-mobile"
            class="flex-1 overflow-y-auto p-2 space-y-2 text-xs"
        >

            <div class="text-center text-gray-400">
                Kosong
            </div>

        </div>

        <div class="p-3 border-t text-xs">

            <div class="flex justify-between">

                <span>
                    Total
                </span>

                <span id="total-mobile">
                    Rp 0
                </span>

            </div>


            {{-- DUMMY ELEMENT WAJIB JS --}}

            <div class="hidden">

                <span id="subtotal-mobile">
                    0
                </span>

                <span id="tax-mobile">
                    0
                </span>

            </div>


            <button
                type="button"
                onclick="checkout()"
                class="w-full bg-blue-600 text-white py-2 rounded mt-2"
            >
                Bayar
            </button>


            <button
                type="button"
                onclick="clearCart()"
                class="w-full border mt-1 py-1 rounded text-xs"
            >
                Hapus Semua
            </button>

        </div>

    </div>


    {{-- =====================================================
         CART DESKTOP
         ===================================================== --}}

    <div class="hidden lg:flex w-80 bg-white rounded-xl shadow flex-col flex-1 min-h-0 overflow-hidden">

        <div class="p-4 border-b font-semibold">
            Pesanan
        </div>

        <div
            id="cart-items-desktop"
            class="flex-1 overflow-y-auto p-4 space-y-3"
        >

            <p class="text-gray-400 text-sm text-center">
                Kosong
            </p>

        </div>

        <div class="p-4 border-t text-sm">

            <div class="flex justify-between">

                <span>
                    Total
                </span>

                <span id="total-desktop">
                    Rp 0
                </span>

            </div>


            {{-- DUMMY ELEMENT WAJIB JS --}}

            <div class="hidden">

                <span id="subtotal-desktop">
                    0
                </span>

                <span id="tax-desktop">
                    0
                </span>

            </div>


            <button
                type="button"
                onclick="checkout()"
                class="w-full bg-blue-600 text-white py-2 rounded mt-3"
            >
                Bayar
            </button>


            <button
                type="button"
                onclick="clearCart()"
                class="w-full border mt-2 py-1 rounded text-xs"
            >
                Hapus Semua
            </button>

        </div>

    </div>

</div>
 

</div>

{{-- =========================================================
LOADING
========================================================= --}}

<div
    id="loading-overlay"
    class="hidden fixed inset-0 z-[9999] bg-black/40 items-center justify-center"
>

<div
    id="loading-content"
    class="bg-white p-6 rounded-xl shadow-xl max-w-lg w-full mx-4"
>

    <div class="flex flex-col items-center gap-4">

        <div class="w-10 h-10 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>

        <div class="text-gray-700 font-medium">
            Loading...
        </div>

    </div>

</div>

</div>

{{-- =========================================================
QRIS
========================================================= --}}

<script>
    window.qrisImage = "{{ asset('images/qris.jpeg') }}";
</script>

{{-- =========================================================
OUTLET TRANSAKSI
========================================================= --}}

<script>
    window.transactionOutlet = function () {

        const element = document.getElementById('transaction-outlet');

        if (!element) {
            return '';
        }

        return element.value || '';
    };
</script>

{{-- =========================================================
OUTLET JS
========================================================= --}}

<script src="{{ asset('js/outlet.js') }}?v={{ time() }}"></script>

@endsection
