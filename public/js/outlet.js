
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

        let tax = Math.round(0);
        let total = subtotal;

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

        let tax = Math.round(0);

        let total = subtotal;

    loadingContent.innerHTML = `
    <div class="bg-white rounded-xl w-full max-w-4xl mx-2 md:mx-auto p-6 flex flex-col h-[90vh] relative">

    <div class="flex justify-between items-center mb-4">
        <!-- HEADER -->
        <h2 class="text-xl md:text-2xl font-bold mb-4">
            Pembayaran
        </h2>

        <button 
            id="close-payment"
            class="text-gray-500 hover:text-red-600 text-2xl font-bold">
            x
        </button>

        </div>
        <!-- TAB -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <button id="cash-tab"
                class="border rounded-lg py-2 font-medium bg-blue-600 text-white">
                Cash
            </button>

            <button id="qris-tab"
                class="border rounded-lg py-2 font-medium">
                QRIS
            </button>
        </div>

 <!-- QQQQ-->

        <!-- AREA CONTENT -->
        <div class="flex-1 flex gap-4 overflow-hidden">


            <!-- CASH BODY -->
            <div id="payment-body"
                class="flex-1 overflow-y-auto pr-2">
            </div>



            <!-- QRIS BODY -->
            <div id="payment-qris"
                class="hidden w-[420px] overflow-y-auto">

            </div>


        </div>
        <!-- FOOTER (FIXED BOTTOM AREA) -->
        <div class="mt-4 pt-3 border-t bg-white">

            <button id="process-payment"
                class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold">
                Proses Pembayaran
            </button>

        </div>

    </div>
    `;

    loadingOverlay.classList.remove('hidden');
    loadingOverlay.classList.add('flex');

    document
        .getElementById('close-payment')
        .onclick = function(){

            hideLoading();

        };
let paymentMethod = 'cash';

// ======================
// FUNCTION CASH VIEW
// ======================

function initCashView() {

    document
    .getElementById('payment-qris')
    .classList.add('hidden');


    document
    .getElementById('payment-body')
    .classList.remove('hidden');

    // kode cash lama

    document.getElementById('payment-body').innerHTML = `

        <!-- ISI CASH KAMU TETAP DISINI -->

        <div class="space-y-5">

            <div>

                <div class="font-semibold mb-2">
                    Jumlah Uang Diterima
                </div>


                <input
                id="cash-input"
                type="number"
                value="${total}"
                class="w-full border rounded-xl p-3 text-xl font-bold">

            </div>



            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">


                <button class="cash-btn border rounded-xl py-3"
                data-value="${total}">
                    Uang Pas
                </button>


                <button class="cash-btn border rounded-xl py-3"
                data-value="10000">
                    10K
                </button>


                <button class="cash-btn border rounded-xl py-3"
                data-value="20000">
                    20K
                </button>


                <button class="cash-btn border rounded-xl py-3"
                data-value="50000">
                    50K
                </button>


            </div>



            <div class="bg-green-50 rounded-xl p-4 text-center">


                <div class="text-green-700">
                    Kembalian
                </div>


                <div id="change-text"
                class="text-5xl font-bold text-green-600">

                    ${rupiah(0)}

                </div>


            </div>


        </div>

    `;



    const cashInput =
    document.getElementById('cash-input');


    const changeText =
    document.getElementById('change-text');



    function updateChange(){

        let bayar =
        parseInt(cashInput.value || 0);


        changeText.innerText =
        rupiah(Math.max(bayar-total,0));

    }



    cashInput.addEventListener(
        'input',
        updateChange
    );



    document
    .querySelectorAll('.cash-btn')
    .forEach(btn=>{


        btn.onclick=()=>{

            cashInput.value =
            btn.dataset.value;


            updateChange();

        };


    });


}

// ======================
// FUNCTION QRIS VIEW
// ======================

function initQrisView(){


    document
    .getElementById('payment-body')
    .classList.add('hidden');


    document
    .getElementById('payment-qris')
    .classList.remove('hidden');



    document.getElementById('payment-qris').innerHTML = `


    <div class="h-full flex flex-col overflow-hidden">


        <!-- NOMINAL -->
        <div class="text-center py-2">


            <div class="text-gray-500 text-sm">
                Total Pembayaran
            </div>


            <div class="text-3xl font-bold text-blue-600">
                ${rupiah(total)}
            </div>


        </div>



        <!-- QR FULL POPUP -->
        <div class="flex-1 overflow-hidden">


            <img
            src="${window.qrisImage}?v=${Date.now()}"
            class="w-full h-full object-contain">


        </div>



    </div>


    `;


}

// ======================
// DEFAULT CASH
// ======================

initCashView();

// ======================
// TAB QRIS
// ======================

const qrisTab =
    document.getElementById('qris-tab');

if (qrisTab) {

    qrisTab.onclick = () => {

        paymentMethod = 'qris';

        document.getElementById('cash-tab')
            .classList.remove(
                'bg-blue-600',
                'text-white'
            );

        document.getElementById('qris-tab')
            .classList.add(
                'bg-blue-600',
                'text-white'
            );

        initQrisView();
    };
}

// ======================
// TAB CASH
// ======================

const cashTab =
    document.getElementById('cash-tab');

if (cashTab) {

    cashTab.onclick = () => {

        paymentMethod = 'cash';

        document.getElementById('qris-tab')
            .classList.remove(
                'bg-blue-600',
                'text-white'
            );

        document.getElementById('cash-tab')
            .classList.add(
                'bg-blue-600',
                'text-white'
            );

        initCashView();
    };
}
// ======================
// PROCESS PAYMENT
// ======================

document.getElementById("process-payment").onclick = async function () {

    if (isCheckoutProcessing) return;

    isCheckoutProcessing = true;

    const btn = document.getElementById("process-payment");

    btn.disabled = true;
    btn.innerHTML = "Memproses...";

    try {

        let receivedAmount = total;

        if (paymentMethod === "cash") {
            receivedAmount = parseInt(
                document.getElementById("cash-input")?.value || total
            );
        }

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
                payment_method: paymentMethod,
                payment_amount: receivedAmount,
                received_amount: receivedAmount,
                change_amount: Math.max(receivedAmount - total, 0)
            })
        });

        const data = await res.json();

        if (!res.ok) {
            throw data;
        }

        // ===========================
        // PRINT VIA ANDROID WEBVIEW
        // ===========================

        if (
            window.AndroidPrinter &&
            typeof window.AndroidPrinter.printReceipt === "function"
        ) {

            window.AndroidPrinter.printReceipt(
                JSON.stringify(data)
            );
        }

        loadingContent.innerHTML = `
            <div class="bg-white rounded-2xl p-10 w-full max-w-lg">

                <div class="text-center">

                    <div class="text-3xl font-bold text-green-600">
                        ✓
                    </div>

                    <h2 class="text-2xl font-bold mt-4">
                        Transaksi Berhasil
                    </h2>

                    <div class="mt-2">
                        ${data.order_number}
                    </div>

                    <div class="text-xl font-bold mt-2">
                        ${rupiah(data.total)}
                    </div>

                    <button
                        id="finish-payment"
                        class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg">

                        Pesanan Baru

                    </button>

                </div>

            </div>
        `;

        cart = {};

        renderCart();

        document.getElementById("finish-payment").onclick = function () {
            hideLoading();
        };

    } catch (err) {

        hideLoading();

        alert(
            err.error ||
            err.message ||
            "Gagal transaksi"
        );

    } finally {

        const btn = document.getElementById("process-payment");

        if (btn) {
            btn.disabled = false;
            btn.innerHTML = "Proses Pembayaran";
        }

        isCheckoutProcessing = false;
    }
};
};
});