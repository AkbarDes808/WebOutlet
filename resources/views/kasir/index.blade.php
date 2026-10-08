@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $user = Auth::user();
    $role = strtolower(trim($user->role ?? ''));
    $isAdmin = $role === 'admin';
    $isSpv = $role === 'spv';

    $outletMap = collect(range(1, 7))
        ->mapWithKeys(fn($i) => ["outlet {$i}" => "Outlet {$i}"]);

    $userOutlet = $outletMap[$role] ?? null;
    $transactionOutlet = $userOutlet ?? request('outlet');

    $outletNames = [
        'Outlet 1' => 'Pusat',
        'Outlet 2' => 'Indomaret',
        'Outlet 3' => 'Bunderan',
        'Outlet 4' => 'Mersi',
        'Outlet 5' => 'Arca',
        'Outlet 6' => 'Larangan',
        'Outlet 7' => 'Unsoed',
    ];

    $menuGroups = $menus->groupBy(
        fn($menu) => $menu->category ?: 'Menu Utama'
    );

    $categoryOrder = [
        'Menu Utama',
        'Menu Tambahan',
        'Menu Gratis',
    ];
@endphp

<div class="h-screen bg-gray-100 flex flex-col overflow-hidden">

    {{-- Header --}}
    <header class="h-[70px] px-4 lg:px-6 bg-white border-b flex items-center justify-between shrink-0">
        <div>
            <h1 class="text-lg font-bold text-gray-800">
                Kasir
            </h1>

            <p class="text-xs text-gray-500">
                {{ $user->name ?? 'User' }}
            </p>
        </div>

        <a
            href="{{ route('shift.index') }}"
            class="border border-gray-300 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-100 transition"
        >
            Tutup Shift
        </a>
    </header>

    {{-- Outlet --}}
    @if($isAdmin || $isSpv)

        <div class="px-3 pt-3 lg:px-6 lg:pt-4 shrink-0">
            <div class="bg-white rounded-xl shadow-sm border p-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">

                    <div class="flex-1">
                        <label
                            for="transaction-outlet"
                            class="block text-sm font-semibold text-gray-700"
                        >
                            Outlet Transaksi
                        </label>

                        <p class="text-xs text-gray-500 mt-1">
                            Pilih outlet untuk transaksi dan pengurangan stok.
                        </p>
                    </div>

                    <select
                        id="transaction-outlet"
                        name="outlet"
                        class="w-full sm:w-64 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">
                            -- Pilih Outlet --
                        </option>

                        @foreach($outletNames as $value => $name)
                            <option
                                value="{{ $value }}"
                                {{ $transactionOutlet === $value ? 'selected' : '' }}
                            >
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>

    @else
        <input type="hidden" id="transaction-outlet" name="outlet" value="{{ $userOutlet }}">
        <div class="px-3 pt-3 lg:px-6 lg:pt-4 shrink-0">
            <div class="bg-white rounded-xl shadow-sm border px-4 py-3 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Outlet Transaksi</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $userOutlet ?? 'Outlet tidak diketahui' }}</p>
                </div>
                <span class="text-xl">🏪</span>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="p-3 lg:p-6 flex gap-3 flex-1 min-h-0 overflow-hidden">

        {{-- Menu --}}
        <section class="w-1/2 lg:flex-1 min-h-0 overflow-y-auto space-y-4 pr-1">

            @foreach($categoryOrder as $category)

                @if($menuGroups->has($category) && $menuGroups[$category]->isNotEmpty())

                    <div>

                        <h2 class="px-1 mb-2 text-sm font-bold text-gray-700">
                            {{ $category }}
                        </h2>

                        <div class="grid grid-cols-1 gap-2">

                            @foreach($menuGroups[$category] as $menu)

                                <div class="bg-white rounded-xl border shadow-sm p-3 flex items-center justify-between gap-3 hover:shadow transition">

                                    <div class="min-w-0">

                                        @php
                                            $displayMenuName = match (strtolower(trim($menu->name))) {
                                                'saus cabe', 'saus sambal sachet', 'saos cabe', 'cabe', 'sambel', 'sambal' => 'Kantong Sambal',
                                                default => $menu->name,
                                            };
                                        @endphp

                                        <div class="font-semibold text-sm text-gray-800 truncate">
                                            {{ $displayMenuName }}
                                        </div>

                                        <div class="text-xs text-blue-600 font-semibold mt-0.5">
                                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                                        </div>

                                    </div>

                                    <button
                                        type="button"
                                        class="btn-add shrink-0 w-9 h-9 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold flex items-center justify-center transition"
                                        data-id="{{ $menu->id }}"
                                        data-name="{{ $menu->name }}"
                                        data-price="{{ $menu->price }}"
                                    >
                                        +
                                    </button>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

            @endforeach

            @if($menuGroups->isEmpty())

                <div class="bg-white rounded-xl border p-6 text-center text-sm text-gray-400">
                    Belum ada menu.
                </div>

            @endif

        </section>

        {{-- Cart Mobile --}}
        <aside class="w-1/2 lg:hidden bg-white rounded-xl shadow-sm border flex flex-col min-h-0 overflow-hidden">

            <div class="p-3 border-b font-semibold text-sm shrink-0">
                Pesanan
            </div>

            <div
                id="cart-items-mobile"
                class="flex-1 min-h-0 overflow-y-auto p-2 space-y-2 text-xs"
            >
                <div class="text-center text-gray-400 py-4">
                    Kosong
                </div>
            </div>

            <div class="p-3 border-t bg-white shrink-0">

                <div class="flex justify-between font-semibold text-xs">
                    <span>Total</span>
                    <span id="total-mobile">
                        Rp 0
                    </span>
                </div>

                <div class="hidden">
                    <span id="subtotal-mobile">0</span>
                    <span id="tax-mobile">0</span>
                </div>

                <button
                    type="button"
                    onclick="checkout()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg mt-2 transition"
                >
                    Bayar
                </button>

                <button
                    type="button"
                    onclick="clearCart()"
                    class="w-full border border-gray-300 hover:bg-gray-50 mt-1 py-1.5 rounded-lg text-xs transition"
                >
                    Hapus Semua
                </button>

            </div>

        </aside>

        {{-- Cart Desktop --}}
        <aside class="hidden lg:flex w-80 bg-white rounded-xl shadow-sm border flex-col min-h-0 overflow-hidden">

            <div class="p-4 border-b font-semibold shrink-0">
                Pesanan
            </div>

            <div
                id="cart-items-desktop"
                class="flex-1 min-h-0 overflow-y-auto p-4 space-y-3"
            >
                <p class="text-gray-400 text-sm text-center py-4">
                    Kosong
                </p>
            </div>

            <div class="p-4 border-t bg-white shrink-0">

                <div class="flex justify-between font-semibold text-sm">
                    <span>Total</span>

                    <span id="total-desktop">
                        Rp 0
                    </span>
                </div>

                <div class="hidden">
                    <span id="subtotal-desktop">0</span>
                    <span id="tax-desktop">0</span>
                </div>

                <button
                    type="button"
                    onclick="checkout()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg mt-3 transition"
                >
                    Bayar
                </button>

                <button
                    type="button"
                    onclick="clearCart()"
                    class="w-full border border-gray-300 hover:bg-gray-50 mt-2 py-1.5 rounded-lg text-xs transition"
                >
                    Hapus Semua
                </button>

            </div>

        </aside>

    </div>

</div>

{{-- Loading Overlay --}}
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

<script>
    window.qrisImage = "{{ asset('images/qris.jpeg') }}";

    window.transactionOutlet = function () {
        const element = document.getElementById('transaction-outlet');
        return element?.value || '';
    };
</script>

<script src="{{ asset('js/outlet.js') }}?v={{ time() }}"></script>

@endsection