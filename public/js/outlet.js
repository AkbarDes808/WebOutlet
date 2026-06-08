
document.addEventListener('DOMContentLoaded', function () {

    let cart = {};

    let isCheckoutProcessing = false;

    // =========================
    // LOADING OVERLAY
    // =========================
    const loadingOverlay = document.getElementById('loading-overlay');

    const loadingContent = document.getElementById('loading-content');

    function showLoading() {

        loadingContent.innerHTML = `

            <div class="w-14 h-14 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>

            <div class="text-center">

                <div class="font-semibold text-gray-700">
                    Memproses transaksi...
                </div>

                <div class="text-sm text-gray-500">
                    Mohon tunggu
                </div>

            </div>
        `;

        loadingOverlay.classList.remove('hidden');

        loadingOverlay.classList.add('flex');
    }

    function hideLoading() {

        loadingOverlay.classList.add('hidden');

        loadingOverlay.classList.remove('flex');
    }

    // =========================
    // FORMAT RUPIAH
    // =========================
    function rupiah(angka) {

        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    // =========================
    // RENDER CART
    // =========================
    function renderCart() {

        let subtotal = 0;

        let mobileHTML = '';

        let desktopHTML = '';

        Object.values(cart).forEach(item => {

            subtotal += item.qty * item.price;

            let html = `
                <div class="flex justify-between items-center border-b pb-2">

                    <div>

                        <div class="font-semibold">
                            ${item.name}
                        </div>

                        <div class="text-gray-500 text-xs">
                            @ ${rupiah(item.price)}
                        </div>

                    </div>

                    <div class="flex items-center gap-2">

                        <button onclick="decrease(${item.id})"
                            class="w-7 h-7 flex items-center justify-center rounded-full border text-gray-600 hover:bg-gray-200 active:scale-90">
                            -
                        </button>

                        <span class="min-w-[20px] text-center font-semibold">
                            ${item.qty}
                        </span>

                        <button onclick="increase(${item.id})"
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 active:scale-90">
                            +
                        </button>

                    </div>

                </div>
            `;

            mobileHTML += html;

            desktopHTML += html;
        });

        let tax = Math.round(subtotal * 0.10);

        let total = subtotal + tax;

        // MOBILE
        document.getElementById('cart-items-mobile').innerHTML =
            mobileHTML || '<div class="text-center text-gray-400">Kosong</div>';

        document.getElementById('subtotal-mobile').innerText = rupiah(subtotal);

        document.getElementById('tax-mobile').innerText = rupiah(tax);

        document.getElementById('total-mobile').innerText = rupiah(total);

        // DESKTOP
        document.getElementById('cart-items-desktop').innerHTML =
            desktopHTML || '<p class="text-gray-400 text-sm text-center">Kosong</p>';

        document.getElementById('subtotal-desktop').innerText = rupiah(subtotal);

        document.getElementById('tax-desktop').innerText = rupiah(tax);

        document.getElementById('total-desktop').innerText = rupiah(total);
    }

    // =========================
    // ADD MENU
    // =========================
    document.addEventListener('click', function(e) {

        const button = e.target.closest('.btn-add');

        if (!button) return;

        let id = button.dataset.id;

        let name = button.dataset.name;

        let price = parseInt(button.dataset.price);

        if (!cart[id]) {

            cart[id] = {
                id,
                name,
                price,
                qty: 1
            };

        } else {

            cart[id].qty++;
        }

        renderCart();
    });

    // =========================
    // INCREASE
    // =========================
    window.increase = function(id) {

        cart[id].qty++;

        renderCart();
    }

    // =========================
    // DECREASE
    // =========================
    window.decrease = function(id) {

        cart[id].qty--;

        if (cart[id].qty <= 0) {

            delete cart[id];
        }

        renderCart();
    }

    // =========================
    // CLEAR CART
    // =========================
    window.clearCart = function() {

        cart = {};

        renderCart();
    }

    // =========================
    // CHECKOUT
    // =========================
window.checkout = async function () {

    if (isCheckoutProcessing) return;

    if (Object.keys(cart).length === 0) {
        alert("Cart kosong");
        return;
    }

    let subtotal = 0;
    let totalItem = 0;

    Object.values(cart).forEach(item => {
        subtotal += item.qty * item.price;
        totalItem += item.qty;
    });

    let tax = Math.round(subtotal * 0.10);
    let total = subtotal + tax;

    loadingContent.innerHTML = `

    <div class="bg-white rounded-2xl
    w-full max-w-xl
    max-h-[90vh]
    overflow-y-auto">

        <div class="flex justify-between items-center p-5 border-b">

           <h2 class="text-xl font-bold">
                Pembayaran Cash
            </h2>

            <button id="close-payment"
                class="text-gray-400 text-2xl">
                ×
            </button>

        </div>

        <div class="p-6">

            <div class="text-center mb-5">

                <div class="text-gray-400 text-lg">
                    Total Bayar
                </div>

                <div class="text-3xl md:text-5xl font-bold text-slate-900 break-words">
                    ${rupiah(total)}
                </div>

                <div class="text-gray-400 mt-2">
                    ${totalItem} items
                </div>

            </div>

            <div class="grid grid-cols-2 gap-3 mb-5">

                <button id="cash-tab"
                    class="border-2 border-blue-500 text-blue-600 font-semibold rounded-xl py-3">

                    Cash

                </button>

                <button id="qris-tab"
                    class="border rounded-xl py-3">

                    QRIS

                </button>

            </div>

            <div id="payment-body"
                class="h-[430px] overflow-y-auto">

                <div class="font-semibold mb-2">
                    Jumlah Uang Diterima
                </div>

                <input
                    id="cash-input"
                    type="number"
                    value="${total}"
                    class="w-full border rounded-xl p-3 text-3xl font-bold mb-4">

                <div class="grid grid-cols-2 gap-3 mb-5">

                    <button class="cash-btn border rounded-xl py-3"
                        data-value="${total}">
                        Uang Pas
                    </button>

                    <button class="cash-btn border rounded-xl py-3"
                        data-value="100000">
                        Rp 100.000
                    </button>

                    <button class="cash-btn border rounded-xl py-3"
                        data-value="200000">
                        Rp 200.000
                    </button>

                    <button class="cash-btn border rounded-xl py-3"
                        data-value="500000">
                        Rp 500.000
                    </button>

                    <button class="cash-btn border rounded-xl py-3"
                        data-value="1000000">
                        Rp 1.000.000
                    </button>

                </div>

                <div
                    class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">

                    <div class="text-green-700 text-lg">
                        Kembalian
                    </div>

                    <div id="change-text"
                        class="text-5xl font-bold text-green-600">

                        ${rupiah(0)}

                    </div>

                </div>

            </div>

        </div>

       <div class="border-t p-5 sticky bottom-0 bg-white">

            <button id="process-payment"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-xl font-bold text-xl">

                Proses Pembayaran

            </button>

        </div>

    </div>

    `;

    loadingOverlay.classList.remove('hidden');
    loadingOverlay.classList.add('flex');

    document.getElementById('close-payment').onclick = hideLoading;

    let paymentMethod = 'cash';

    // ======================
    // CASH
    // ======================

    const cashInput = () =>
        document.getElementById('cash-input');

    const changeText = () =>
        document.getElementById('change-text');

    function updateChange() {

        if (!cashInput()) return;

        let bayar = parseInt(cashInput().value || 0);

        let kembali = bayar - total;

        changeText().innerText =
            rupiah(Math.max(kembali, 0));
    }

    setTimeout(() => {

        updateChange();

        document.querySelectorAll('.cash-btn')
        .forEach(btn => {

            btn.onclick = () => {

                cashInput().value =
                    btn.dataset.value;

                updateChange();
            };

        });

        cashInput().addEventListener(
            'input',
            updateChange
        );

    }, 50);

    // ======================
    // QRIS TAB
    // ======================

    document.getElementById('qris-tab').onclick = () => {

        paymentMethod = 'qris';
        
        document.getElementById('qris-tab')
        .classList.add(
            'border-2',
            'border-blue-500',
            'text-blue-600'
        );

        document.getElementById('cash-tab')
        .classList.remove(
            'border-2',
            'border-blue-500',
            'text-blue-600'
        );

        document.getElementById('payment-body').innerHTML = `

            <div class="h-full flex flex-col justify-between">

            <img
                src="${window.qrisImage}"
                class="mx-auto w-48 h-48 object-contain">

                <div class="mt-4 text-lg">
                    Scan QR dengan aplikasi pembayaran
                </div>

                <div class="text-gray-400 text-sm mt-2">
                    GoPay • OVO • DANA • ShopeePay
                </div>

                <div class="mt-4 text-orange-500 font-semibold">
                    Menunggu pembayaran...
                </div>

            </div>

        `;
    };

    // ======================
    // CASH TAB
    // ======================

    // ======================
// CASH TAB
// ======================

    document.getElementById('cash-tab').onclick = () => {

        paymentMethod = 'cash';

        document.getElementById('payment-body').innerHTML = `

            <div class="font-semibold mb-2">
                Jumlah Uang Diterima
            </div>

            <input
                id="cash-input"
                type="number"
                value="${total}"
                class="w-full border rounded-xl p-3 text-3xl font-bold mb-4">

            <div class="grid grid-cols-2 gap-3 mb-5">

                <button class="cash-btn border rounded-xl py-3"
                    data-value="${total}">
                    Uang Pas
                </button>

                <button class="cash-btn border rounded-xl py-3"
                    data-value="100000">
                    Rp 100.000
                </button>

                <button class="cash-btn border rounded-xl py-3"
                    data-value="200000">
                    Rp 200.000
                </button>

                <button class="cash-btn border rounded-xl py-3"
                    data-value="500000">
                    Rp 500.000
                </button>

                <button class="cash-btn border rounded-xl py-3"
                    data-value="1000000">
                    Rp 1.000.000
                </button>

            </div>

            <div
                class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">

                <div class="text-green-700 text-lg">
                    Kembalian
                </div>

                <div id="change-text"
                    class="text-5xl font-bold text-green-600">

                    ${rupiah(0)}

                </div>

            </div>

        `;

        document.getElementById('cash-tab')
            .classList.add(
                'border-2',
                'border-blue-500',
                'text-blue-600'
            );

        document.getElementById('qris-tab')
            .classList.remove(
                'border-2',
                'border-blue-500',
                'text-blue-600'
            );

        const cashInput =
            document.getElementById('cash-input');

        const changeText =
            document.getElementById('change-text');

        function updateChange() {

            let bayar = parseInt(
                cashInput.value || 0
            );

            let kembali = bayar - total;

            changeText.innerText =
                rupiah(Math.max(kembali, 0));
        }

        updateChange();

        document.querySelectorAll('.cash-btn')
            .forEach(btn => {

                btn.onclick = () => {

                    cashInput.value =
                        btn.dataset.value;

                    updateChange();
                };

            });

        cashInput.addEventListener(
            'input',
            updateChange
        );
    };

    // ======================
    // PROCESS PAYMENT
    // ======================

    document.getElementById('process-payment').onclick =
    async function () {

        isCheckoutProcessing = true;

        loadingContent.innerHTML = `

            <div class="bg-white rounded-2xl p-10">

                <div class="w-16 h-16 mx-auto border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>

                <div class="mt-4 text-center font-semibold">
                    Memproses Pembayaran...
                </div>

            </div>

        `;

        try {

            const res = await fetch("/transactions", {

                method: "POST",

                credentials: "same-origin",

                headers: {

                    "Content-Type": "application/json",

                    "Accept": "application/json",

                    "X-CSRF-TOKEN":
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
                },

                body: JSON.stringify({

                    cart: Object.values(cart),

                    payment_method: paymentMethod

                })
            });

            const data = await res.json();

            if (!res.ok) {
                throw data;
            }

            loadingContent.innerHTML = `

                <div class="bg-white rounded-2xl p-10 w-full max-w-lg">

                    <div class="w-24 h-24 mx-auto rounded-full bg-green-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-12 h-12 text-green-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"/>

                        </svg>

                    </div>

                    <h2 class="text-4xl font-bold text-center mt-5">
                        Transaksi Berhasil
                    </h2>

                    <div class="text-center text-gray-500 mt-2">
                        ${data.order_number}
                    </div>

                    <div class="text-center text-2xl font-bold mt-2">
                        ${rupiah(data.total)}
                    </div>

                    <button
                        id="finish-payment"
                        class="w-full mt-6 bg-blue-600 text-white py-4 rounded-xl font-bold">

                        Pesanan Baru

                    </button>

                </div>

            `;

            cart = {};
            renderCart();

            document.getElementById('finish-payment')
            .onclick = function() {

                hideLoading();
            };

        } catch (err) {

            hideLoading();

            alert(
                err.error ||
                err.message ||
                'Gagal transaksi'
            );

        } finally {

            isCheckoutProcessing = false;
        }
    };
    }

});