@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="bg-gray-100 flex flex-col h-full min-h-0">

    {{-- HEADER --}}
    <div class="h-[70px] px-4 bg-white border-b flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold">Kasir</h1>
            <p class="text-xs text-gray-500">{{ Auth::user()->name }}</p>
        </div>

        <a href="{{ route('shift.index') }}"
           class="border px-3 py-1 rounded text-xs hover:bg-gray-100">
            Tutup Shift
        </a>
    </div>

    {{-- LAYOUT --}}
        <div class="p-3 lg:p-6 flex gap-3">

            {{-- PRODUK --}}
            <div class="w-1/2 lg:flex-1 space-y-2">
            @forelse($menus as $menu)

            <div class="bg-white rounded-lg shadow-sm p-3 flex justify-between items-center">

                <div>
                    <div class="font-semibold text-sm">
                        {{ $menu->name }}
                    </div>

                    <div class="text-xs text-blue-600 font-semibold">
                        Rp {{ number_format($menu->price,0,',','.') }}
                    </div>

                    <div class="text-xs text-gray-400">
                        Stok: {{ $menu->stock ?? '-' }}
                    </div>
                </div>

                <button 
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

        {{-- CART MOBILE --}}
        <div class="w-1/2 lg:hidden bg-white rounded-xl shadow flex flex-col flex-1 min-h-0 overflow-hidden">

            <div class="p-3 border-b font-semibold text-sm">
                Pesanan
            </div>

            <div id="cart-items-mobile" class="flex-1 overflow-y-auto p-2 space-y-2 text-xs">
                <div class="text-center text-gray-400">Kosong</div>
            </div>

            <div class="p-3 border-t text-xs">

                <div class="flex justify-between">
                    <span>Total</span>
                    <span id="total-mobile">Rp 0</span>
                </div>

                {{-- =========================
                    DUMMY ELEMENT (WAJIB JS)
                ========================= --}}
                <div class="hidden">
                    <span id="subtotal-mobile">0</span>
                    <span id="tax-mobile">0</span>
                </div>

                <button onclick="checkout()" class="w-full bg-blue-600 text-white py-2 rounded mt-2">
                    Bayar
                </button>

                <button onclick="clearCart()" class="w-full border mt-1 py-1 rounded text-xs">
                    Hapus Semua
                </button>

            </div>
        </div>

        {{-- CART DESKTOP --}}
        <div class="hidden lg:flex w-80 bg-white rounded-xl shadow flex-col flex-1 min-h-0 overflow-hidden">

            <div class="p-4 border-b font-semibold">
                Pesanan
            </div>

            <div id="cart-items-desktop" class="flex-1 overflow-y-auto p-4 space-y-3">
                <p class="text-gray-400 text-sm text-center">Kosong</p>
            </div>

            <div class="p-4 border-t text-sm">

                <div class="flex justify-between">
                    <span>Total</span>
                    <span id="total-desktop">Rp 0</span>
                </div>

                {{-- =========================
                    DUMMY ELEMENT (WAJIB JS)
                ========================= --}}
                <div class="hidden">
                    <span id="subtotal-desktop">0</span>
                    <span id="tax-desktop">0</span>
                </div>

                <button onclick="checkout()" class="w-full bg-blue-600 text-white py-2 rounded mt-3">
                    Bayar
                </button>

                <button onclick="clearCart()" class="w-full border mt-2 py-1 rounded text-xs">
                    Hapus Semua
                </button>

            </div>
        </div>

    </div>
</div>

{{-- LOADING --}}
<div id="loading-overlay"
    class="hidden fixed inset-0 z-[9999] bg-black/40 items-center justify-center">

    <div id="loading-content"
        class="bg-white p-6 rounded-xl shadow-xl max-w-lg w-full mx-4">

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
</script>

<script src="{{ asset('js/outlet.js') }}?v={{ time() }}"></script>

@endsection