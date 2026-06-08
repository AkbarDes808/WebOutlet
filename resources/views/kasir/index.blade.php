    @extends('layouts.app')

    @section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="bg-gray-100 min-h-screen pb-28 lg:pb-0">

        {{-- HEADER --}}
        <div class="h-[70px] px-4 bg-white shadow-sm 
            sticky top-0 z-30 
            flex items-center justify-between">

            <div>
                <h1 class="text-lg font-bold">Kasir</h1>
                <p class="text-xs text-gray-500">{{ Auth::user()->name }}</p>
            </div>

            <a href="{{ route('shift.index') }}"
            class="border px-3 py-1 rounded text-xs inline-flex items-center hover:bg-gray-100 transition">
                Tutup Shift
            </a>
        </div>

        {{-- LAYOUT --}}
        <div class="p-3 lg:p-6 flex gap-3">

            {{-- =========================
                PRODUK
            ========================== --}}
            <div class="w-1/2 lg:w-full lg:flex-1 space-y-2 overflow-y-auto max-h-[calc(100vh-90px)]">

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

            {{-- =========================
                CART MOBILE
            ========================== --}}
            <div class="w-1/2 lg:hidden bg-white rounded-xl shadow flex flex-col max-h-[calc(100vh-90px)]">

                <div class="p-3 border-b font-semibold text-sm">
                    Pesanan
                </div>

                <div id="cart-items-mobile" class="flex-1 overflow-y-auto p-2 space-y-2 text-xs">
                    <div class="text-center text-gray-400">
                        Kosong
                    </div>
                </div>

                <div class="p-3 border-t text-xs">

                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="subtotal-mobile">Rp 0</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Tax (10%)</span>
                        <span id="tax-mobile">Rp 0</span>
                    </div>

                    <div class="flex justify-between font-bold mt-1">
                        <span>Total</span>
                        <span id="total-mobile">Rp 0</span>
                    </div>

                    <button onclick="checkout()"
                        class="w-full bg-blue-600 text-white py-2 rounded mt-2">
                        Bayar
                    </button>

                    <button onclick="clearCart()"
                        class="w-full border mt-1 py-1 rounded text-xs">
                        Hapus Semua
                    </button>

                </div>
            </div>

            {{-- =========================
                CART DESKTOP
            ========================== --}}
            <div class="hidden lg:flex w-80 bg-white rounded-xl shadow flex-col h-[80vh]">

                <div class="p-4 border-b font-semibold">
                    Pesanan
                </div>

                <div id="cart-items-desktop"
                    class="flex-1 overflow-y-auto p-4 space-y-3">

                    <p class="text-gray-400 text-sm text-center">
                        Kosong
                    </p>

                </div>

                <div class="p-4 border-t text-sm">

                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="subtotal-desktop">Rp 0</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Tax</span>
                        <span id="tax-desktop">Rp 0</span>
                    </div>

                    <div class="flex justify-between font-bold mt-2">
                        <span>Total</span>
                        <span id="total-desktop">Rp 0</span>
                    </div>

                    <button onclick="checkout()"
                        class="w-full bg-blue-600 text-white py-2 rounded mt-3">
                        Bayar
                    </button>

                    <button onclick="clearCart()"
                        class="w-full border mt-2 py-1 rounded text-xs">
                        Hapus Semua
                    </button>

                </div>
            </div>

        </div>

    </div>

    {{-- =========================
        LOADING OVERLAY
    ========================= --}}

        <div id="loading-overlay"
            class="hidden fixed inset-0 z-[9999]
            bg-black/40 backdrop-blur-sm
            items-center justify-center
            overflow-y-auto p-4">
        
        <div id="loading-content"
            class="bg-white rounded-2xl shadow-2xl px-8 py-6 flex flex-col items-center gap-4">

            {{-- SPINNER --}}
            <div class="w-14 h-14 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>

            <div class="text-center">

                <div class="font-semibold text-gray-700">
                    Memproses transaksi...
                </div>

                <div class="text-sm text-gray-500">
                    Mohon tunggu
                </div>

            </div>

        </div>
        <div id="paymentModal"
            class="hidden fixed inset-0 bg-black/50 z-[99999] flex items-center justify-center p-4">

            <div class="bg-white rounded-2xl w-full max-w-md mx-2 overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b">

                    <h2 class="text-xl font-bold">
                        Pembayaran
                    </h2>

                    <button onclick="closePaymentModal()"
                        class="text-gray-500 text-xl">
                        ×
                    </button>

                </div>

                <div class="p-6">

                    <div class="text-center mb-6">

                        <div class="text-gray-500">
                            Total Bayar
                        </div>

                        <div id="payment-total"
                            class="text-5xl font-bold">

                            Rp 0

                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">

                        <button
                            id="tabCash"
                            onclick="showCash()"
                            class="border-2 border-blue-500 text-blue-600 rounded-xl py-3 font-semibold">

                            Cash

                        </button>

                        <button
                            id="tabQris"
                            onclick="showQris()"
                            class="border rounded-xl py-3 font-semibold">

                            QRIS

                        </button>

                    </div>

                    {{-- CASH --}}
                    <div id="cashSection">

                        <label class="font-semibold block mb-2">
                            Jumlah Uang Diterima
                        </label>

                        <input
                            type="number"
                            id="cashReceived"
                            class="w-full border rounded-xl p-4 text-2xl font-bold"
                            oninput="calculateChange()">

                        <div class="grid grid-cols-2 gap-2 mt-3">

                            <button onclick="setCashAmount('pas')" class="border rounded-xl p-3">
                                Uang Pas
                            </button>

                            <button onclick="setCashAmount(100000)" class="border rounded-xl p-3">
                                Rp 100.000
                            </button>

                            <button onclick="setCashAmount(150000)" class="border rounded-xl p-3">
                                Rp 150.000
                            </button>

                            <button onclick="setCashAmount(200000)" class="border rounded-xl p-3">
                                Rp 200.000
                            </button>

                        </div>

                        <div class="mt-5 bg-green-50 border border-green-200 rounded-xl p-4 text-center">

                            <div class="text-green-700">
                                Kembalian
                            </div>

                            <div id="changeAmount"
                                class="text-4xl font-bold text-green-600">

                                Rp 0

                            </div>

                        </div>

                    </div>

                    {{-- QRIS --}}
                    <div id="qrisSection" class="hidden">

                        <div class="flex justify-center">

                            <img
                                src="images/qris.jpeg"
                                class="w-56 h-56">

                        </div>

                        <div class="text-center mt-4 text-gray-500">

                            Scan QR menggunakan aplikasi pembayaran

                        </div>

                        <div class="text-center text-orange-500 mt-3">

                            Menunggu pembayaran...

                        </div>

                    </div>

                    <button
                        onclick="processPayment()"
                        class="w-full mt-6 bg-blue-600 text-white py-4 rounded-xl font-bold text-lg">

                        Proses Pembayaran

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>
        window.qrisImage = "{{ asset('images/qris.jpeg') }}";
    </script>

    <script src="{{ asset('js/outlet.js') }}?v={{ time() }}"></script>

    @endsection