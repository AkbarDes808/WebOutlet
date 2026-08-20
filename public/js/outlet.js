
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
<div class="
    bg-white
    rounded-2xl
    w-full
    max-w-5xl
    mx-auto
    p-4
    sm:p-5
    md:p-6
    flex
    flex-col
    max-h-[calc(100dvh-20px)]
    overflow-hidden
    relative
    shadow-2xl
">

    <!-- HEADER -->
    <div class="
        flex
        items-center
        justify-between
        shrink-0
        mb-4
    ">

        <h2 class="
            text-xl
            sm:text-2xl
            md:text-3xl
            font-bold
            text-gray-800
        ">
            Pembayaran
        </h2>

        <button
            id="close-payment"
            type="button"
            class="
                w-10
                h-10
                flex
                items-center
                justify-center
                rounded-full
                text-gray-500
                hover:bg-gray-100
                hover:text-red-600
                text-2xl
                font-bold
                transition
            "
        >
            ×
        </button>

    </div>


    <!-- TAB -->
    <div class="
        grid
        grid-cols-2
        gap-3
        mb-4
        shrink-0
    ">

        <button
            id="cash-tab"
            type="button"
            class="
                border
                rounded-xl
                py-3
                sm:py-4
                font-semibold
                text-lg
                sm:text-xl
                bg-blue-600
                text-white
                transition
            "
        >
            Cash
        </button>

        <button
            id="qris-tab"
            type="button"
            class="
                border
                rounded-xl
                py-3
                sm:py-4
                font-semibold
                text-lg
                sm:text-xl
                text-gray-700
                bg-white
                transition
            "
        >
            QRIS
        </button>

    </div>


    <!-- CONTENT -->
    <div class="
        flex-1
        min-h-0
        overflow-hidden
    ">

        <div
            id="payment-body"
            class="
                h-full
                overflow-y-auto
                pr-1
            "
        ></div>

        <div
            id="payment-qris"
            class="
                hidden
                h-full
                w-full
            "
        ></div>

    </div>


    <!-- FOOTER -->
    <div class="
        mt-4
        pt-3
        border-t
        shrink-0
    ">

        <button
            id="process-payment"
            type="button"
            class="
                w-full
                bg-blue-600
                hover:bg-blue-700
                active:scale-[0.99]
                text-white
                py-3
                sm:py-4
                rounded-xl
                text-lg
                sm:text-xl
                font-semibold
                transition
            "
        >
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

// ======================
// FUNCTION CASH VIEW
// ======================
function initCashView() {

    const paymentBody =
        document.getElementById('payment-body');

    const paymentQris =
        document.getElementById('payment-qris');

    paymentQris.classList.add('hidden');
    paymentBody.classList.remove('hidden');

    paymentBody.innerHTML = `
        <div class="
            h-full
            flex
            items-center
            justify-center
        ">

            <div class="
                w-full
                grid
                grid-cols-2
                gap-4
                md:gap-6
                items-stretch
            ">

                <!-- KIRI -->
                <div class="
                    flex
                    flex-col
                    justify-center
                    space-y-4
                ">

                    <!-- TOTAL -->
                    <div class="
                        rounded-xl
                        bg-gray-50
                        border
                        p-4
                        md:p-5
                        text-center
                    ">

                        <div class="text-sm text-gray-500">
                            Total Pembayaran
                        </div>

                        <div class="
                            text-2xl
                            md:text-3xl
                            font-bold
                            text-blue-600
                            mt-1
                        ">
                            ${rupiah(total)}
                        </div>

                    </div>


                    <!-- INPUT -->
                    <div>

                        <label
                            for="cash-input"
                            class="
                                block
                                font-semibold
                                mb-2
                            "
                        >
                            Jumlah Uang Diterima
                        </label>

                        <input
                            id="cash-input"
                            type="number"
                            inputmode="numeric"
                            min="${total}"
                            value="${total}"
                            class="
                                w-full
                                border-2
                                border-gray-200
                                focus:border-blue-500
                                focus:ring-2
                                focus:ring-blue-100
                                rounded-xl
                                p-3
                                md:p-4
                                text-xl
                                md:text-2xl
                                font-bold
                                outline-none
                            "
                        >

                    </div>


                    <!-- NOMINAL -->
                    <div>

                        <div class="
                            text-sm
                            text-gray-500
                            mb-2
                        ">
                            Nominal cepat
                        </div>

                        <div class="
                            grid
                            grid-cols-4
                            gap-2
                        ">

                            <button
                                type="button"
                                class="
                                    cash-btn
                                    border-2
                                    border-gray-200
                                    rounded-xl
                                    py-3
                                    font-semibold
                                "
                                data-value="${total}"
                            >
                                Pas
                            </button>

                            <button
                                type="button"
                                class="
                                    cash-btn
                                    border-2
                                    border-gray-200
                                    rounded-xl
                                    py-3
                                    font-semibold
                                "
                                data-value="10000"
                            >
                                10K
                            </button>

                            <button
                                type="button"
                                class="
                                    cash-btn
                                    border-2
                                    border-gray-200
                                    rounded-xl
                                    py-3
                                    font-semibold
                                "
                                data-value="20000"
                            >
                                20K
                            </button>

                            <button
                                type="button"
                                class="
                                    cash-btn
                                    border-2
                                    border-gray-200
                                    rounded-xl
                                    py-3
                                    font-semibold
                                "
                                data-value="50000"
                            >
                                50K
                            </button>

                        </div>

                    </div>

                </div>


                <!-- KANAN -->
                <div class="
                    flex
                    items-center
                    justify-center
                ">

                    <div class="
                        w-full
                        h-full
                        rounded-xl
                        bg-green-50
                        border
                        border-green-100
                        flex
                        flex-col
                        items-center
                        justify-center
                        p-4
                    ">

                        <div class="
                            text-sm
                            md:text-base
                            text-green-700
                            font-medium
                        ">
                            Kembalian
                        </div>

                        <div
                            id="change-text"
                            class="
                                text-4xl
                                md:text-5xl
                                font-bold
                                text-green-600
                                mt-2
                            "
                        >
                            ${rupiah(0)}
                        </div>

                    </div>

                </div>

            </div>

        </div>
    `;


    const cashInput =
        document.getElementById('cash-input');

    const changeText =
        document.getElementById('change-text');


    function updateChange() {

        const bayar =
            parseInt(
                cashInput.value || 0,
                10
            );

        const change =
            Math.max(
                bayar - total,
                0
            );

        changeText.innerText =
            rupiah(change);

        if (bayar < total) {

            changeText.classList.remove(
                'text-green-600'
            );

            changeText.classList.add(
                'text-red-600'
            );

        } else {

            changeText.classList.remove(
                'text-red-600'
            );

            changeText.classList.add(
                'text-green-600'
            );
        }
    }


    cashInput.addEventListener(
        'input',
        updateChange
    );


    paymentBody
        .querySelectorAll('.cash-btn')
        .forEach(btn => {

            btn.onclick = () => {

                const value =
                    parseInt(
                        btn.dataset.value,
                        10
                    );

                cashInput.value =
                    Math.max(
                        value,
                        total
                    );

                updateChange();
            };

        });


    updateChange();
}

// ======================
// FUNCTION QRIS VIEW
// ======================
function initQrisView() {

    const paymentBody =
        document.getElementById('payment-body');

    const paymentQris =
        document.getElementById('payment-qris');

    paymentBody.classList.add('hidden');
    paymentQris.classList.remove('hidden');

    const qrisUrl =
        `${window.qrisImage}?v=${Date.now()}`;


    paymentQris.innerHTML = `
        <div class="
            h-full
            w-full
            grid
            grid-cols-2
            gap-4
            md:gap-6
        ">

            <!-- KIRI: INFORMASI -->
            <div class="
                flex
                flex-col
                justify-center
                items-center
                text-center
                space-y-4
            ">

                <div class="text-gray-500">
                    Total Pembayaran
                </div>

                <div class="
                    text-3xl
                    md:text-4xl
                    font-bold
                    text-blue-600
                ">
                    ${rupiah(total)}
                </div>

                <div class="
                    text-sm
                    md:text-base
                    text-gray-500
                    max-w-xs
                ">
                    Scan QRIS menggunakan aplikasi
                    pembayaran Anda
                </div>

                <div class="
                    px-4
                    py-2
                    rounded-full
                    bg-blue-50
                    text-blue-600
                    text-sm
                    font-semibold
                ">
                    QRIS
                </div>

            </div>


            <!-- KANAN: QR -->
            <div class="
                flex
                items-center
                justify-center
                min-h-0
            ">

                <button
                    id="qris-preview-button"
                    type="button"
                    class="
                        relative
                        flex
                        items-center
                        justify-center
                        cursor-pointer
                        rounded-2xl
                        overflow-hidden
                        bg-white
                        border-2
                        border-gray-200
                        shadow-md
                        active:scale-[0.98]
                        transition
                    "
                    title="Klik untuk memperbesar QRIS"
                >

                    <img
                        src="${qrisUrl}"
                        alt="QRIS Pembayaran"
                        class="
                            block
                            max-h-[45vh]
                            max-w-[40vw]
                            md:max-h-[48vh]
                            md:max-w-[380px]
                            w-auto
                            h-auto
                            object-contain
                        "
                    >

                    <div class="
                        absolute
                        bottom-2
                        right-2
                        bg-black/60
                        text-white
                        rounded-full
                        w-9
                        h-9
                        flex
                        items-center
                        justify-center
                    ">
                        🔍
                    </div>

                </button>

            </div>

        </div>
    `;


    // =========================
    // MODAL QRIS FULLSCREEN
    // =========================

    const qrisModal =
        document.createElement('div');

    qrisModal.id =
        'qris-image-modal';

    qrisModal.className = `
        fixed
        inset-0
        z-[999999]
        hidden
        items-center
        justify-center
        bg-black/80
        p-4
    `;

    qrisModal.innerHTML = `

        <button
            id="close-qris-preview"
            type="button"
            class="
                absolute
                top-4
                right-4
                z-10
                w-11
                h-11
                rounded-full
                bg-white
                text-gray-700
                text-3xl
                font-bold
                shadow-xl
                flex
                items-center
                justify-center
            "
        >
            ×
        </button>

        <img
            src="${qrisUrl}"
            alt="QRIS Preview"
            class="
                max-w-[90vw]
                max-h-[85vh]
                w-auto
                h-auto
                object-contain
                rounded-xl
                bg-white
                p-2
                shadow-2xl
            "
        >

    `;

    document.body.appendChild(qrisModal);


    const qrisButton =
        document.getElementById(
            'qris-preview-button'
        );

    const closeQris =
        qrisModal.querySelector(
            '#close-qris-preview'
        );


    qrisButton.onclick = function () {

        qrisModal.classList.remove(
            'hidden'
        );

        qrisModal.classList.add(
            'flex'
        );
    };


    closeQris.onclick = function (e) {

        e.stopPropagation();

        qrisModal.classList.add(
            'hidden'
        );

        qrisModal.classList.remove(
            'flex'
        );

        qrisModal.remove();
    };


    qrisModal.onclick = function (e) {

        if (e.target === qrisModal) {

            qrisModal.classList.add(
                'hidden'
            );

            qrisModal.classList.remove(
                'flex'
            );

            qrisModal.remove();
        }
    };
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