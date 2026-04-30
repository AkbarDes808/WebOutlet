@extends('layouts.app')

@section('content')

{{-- CSRF --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="bg-gray-100 min-h-screen pb-28 lg:pb-0">

    {{-- HEADER --}}
    <div class="p-4 bg-white shadow-sm sticky top-0 z-10">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold">Kasir</h1>
                <p class="text-xs text-gray-500">
                    {{ Auth::user()->name }}
                </p>
            </div>

            <button class="border px-3 py-1 rounded text-xs">
                Tutup Shift
            </button>
        </div>
    </div>

    <div class="p-3 lg:p-6 flex flex-col lg:flex-row gap-4">

        {{-- =========================
            PRODUK LIST
        ========================== --}}
        <div class="w-full lg:flex-1 space-y-2">

            @forelse($menus as $menu)
            <div class="bg-white rounded-lg shadow-sm p-3 flex justify-between items-center">

                <div>
                    <div class="font-semibold text-sm">
                        {{ $menu->name }}
                    </div>
                    <div class="text-xs text-gray-500">
                        Rp {{ number_format($menu->price,0,',','.') }}
                    </div>
                </div>

                <button 
                    class="btn-add text-white text-xs px-3 py-1 rounded"
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
            CART DESKTOP
        ========================== --}}
        <div class="hidden lg:flex w-80 bg-white rounded-xl shadow flex-col h-[80vh]">

            <div class="p-4 border-b font-semibold">
                Pesanan
            </div>

            <div id="cart-items-desktop" class="flex-1 overflow-y-auto p-4 space-y-3">
                <p class="text-gray-400 text-sm text-center">Kosong</p>
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

                <button onclick="checkout()" class="w-full bg-blue-600 text-white py-2 rounded mt-3">
                    Bayar
                </button>

                <button onclick="clearCart()" class="w-full border mt-2 py-1 rounded text-xs">
                    Hapus Semua
                </button>

            </div>
        </div>

    </div>

    {{-- =========================
        MOBILE CART (STICKY)
    ========================== --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-3 lg:hidden">

        <div id="cart-items-mobile" class="max-h-40 overflow-y-auto text-xs space-y-2 mb-2">
            <div class="text-center text-gray-400">Kosong</div>
        </div>

        <div class="flex justify-between text-sm">
            <span>Total</span>
            <span id="total-mobile" class="font-bold">Rp 0</span>
        </div>

        <button onclick="checkout()" class="w-full bg-blue-600 text-white py-2 rounded mt-2 text-sm">
            Bayar
        </button>

    </div>

</div>

{{-- =========================
    JS CART + CHECKOUT
========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    let cart = {};

    function rupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function renderCart() {
        let subtotal = 0;

        let mobileHTML = '';
        let desktopHTML = '';

        Object.values(cart).forEach(item => {
            subtotal += item.qty * item.price;

            let html = `
                <div class="flex justify-between items-center border-b pb-1">
                    <div>
                        <div class="font-semibold">${item.name}</div>
                        <div class="text-gray-500 text-xs">@ ${rupiah(item.price)}</div>
                    </div>

                    <div class="flex items-center gap-1">
                        <button onclick="decrease(${item.id})">-</button>
                        <span>${item.qty}</span>
                        <button onclick="increase(${item.id})">+</button>
                    </div>
                </div>
            `;

            mobileHTML += html;
            desktopHTML += html;
        });

        let tax = Math.round(subtotal * 0.10);
        let total = subtotal + tax;

        // MOBILE
        document.getElementById('cart-items-mobile').innerHTML = mobileHTML || '<div class="text-center text-gray-400">Kosong</div>';
        document.getElementById('total-mobile').innerText = rupiah(total);

        // DESKTOP
        document.getElementById('cart-items-desktop').innerHTML = desktopHTML || '<p class="text-gray-400 text-sm text-center">Kosong</p>';
        document.getElementById('subtotal-desktop').innerText = rupiah(subtotal);
        document.getElementById('tax-desktop').innerText = rupiah(tax);
        document.getElementById('total-desktop').innerText = rupiah(total);
    }

    // TAMBAH PRODUK
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add')) {

            let id = e.target.dataset.id;
            let name = e.target.dataset.name;
            let price = parseInt(e.target.dataset.price);

            if (!cart[id]) {
                cart[id] = { id, name, price, qty: 1 };
            } else {
                cart[id].qty++;
            }

            renderCart();
        }
    });

    // GLOBAL FUNCTIONS
    window.increase = function(id) {
        cart[id].qty++;
        renderCart();
    }

    window.decrease = function(id) {
        cart[id].qty--;
        if (cart[id].qty <= 0) delete cart[id];
        renderCart();
    }

    window.clearCart = function() {
        cart = {};
        renderCart();
    }

    // 🔥 CHECKOUT
    window.checkout = async function() {

    if (Object.keys(cart).length === 0) {
        alert("Cart kosong");
        return;
    }

    try {
        const res = await fetch("/transactions", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                cart: Object.values(cart)
            })
        });

        const text = await res.text(); // 🔥 ambil raw response

        console.log("RAW RESPONSE:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            throw new Error("Response bukan JSON (lihat console)");
        }

        if (!res.ok) throw data;

        alert("Transaksi berhasil! Kode: " + data.kode);

        cart = {};
        renderCart();

    } catch (err) {
        console.error("ERROR:", err);
        alert("Gagal transaksi: " + (err.message || JSON.stringify(err)));
    }
    }

});
</script>

@endsection