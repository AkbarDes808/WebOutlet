document.addEventListener('DOMContentLoaded', function () {

    let cart = {};
    let isCheckoutProcessing = false;

    const loadingOverlay = document.getElementById('loading-overlay');
    const loadingContent = document.getElementById('loading-content');

    // =========================================================
    // LOADING
    // =========================================================

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

    // =========================================================
    // RUPIAH
    // =========================================================

    function rupiah(angka) {
        return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
    }

    // =========================================================
    // RENDER CART
    // =========================================================

    function renderCart() {

        let subtotal = 0;
        let mobileHTML = '';
        let desktopHTML = '';

        Object.values(cart).forEach(item => {

            subtotal += Number(item.qty) * Number(item.price);

            const html = `
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

                        <button
                            onclick="decrease(${item.id})"
                            class="w-7 h-7 flex items-center justify-center rounded-full border text-gray-600 hover:bg-gray-200 active:scale-90">
                            -
                        </button>

                        <span class="min-w-[20px] text-center font-semibold">
                            ${item.qty}
                        </span>

                        <button
                            onclick="increase(${item.id})"
                            class="w-7 h-7 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 active:scale-90">
                            +
                        </button>

                    </div>

                </div>
            `;

            mobileHTML += html;
            desktopHTML += html;
        });

        const tax = 0;
        const total = subtotal;

        const cartMobile = document.getElementById('cart-items-mobile');
        const subtotalMobile = document.getElementById('subtotal-mobile');
        const taxMobile = document.getElementById('tax-mobile');
        const totalMobile = document.getElementById('total-mobile');

        const cartDesktop = document.getElementById('cart-items-desktop');
        const subtotalDesktop = document.getElementById('subtotal-desktop');
        const taxDesktop = document.getElementById('tax-desktop');
        const totalDesktop = document.getElementById('total-desktop');

        if (cartMobile) {
            cartMobile.innerHTML =
                mobileHTML ||
                '<div class="text-center text-gray-400">Kosong</div>';
        }

        if (subtotalMobile) {
            subtotalMobile.innerText = rupiah(subtotal);
        }

        if (taxMobile) {
            taxMobile.innerText = rupiah(tax);
        }

        if (totalMobile) {
            totalMobile.innerText = rupiah(total);
        }

        if (cartDesktop) {
            cartDesktop.innerHTML =
                desktopHTML ||
                '<p class="text-gray-400 text-sm text-center">Kosong</p>';
        }

        if (subtotalDesktop) {
            subtotalDesktop.innerText = rupiah(subtotal);
        }

        if (taxDesktop) {
            taxDesktop.innerText = rupiah(tax);
        }

        if (totalDesktop) {
            totalDesktop.innerText = rupiah(total);
        }
    }

    // =========================================================
    // ADD MENU
    // =========================================================

    document.addEventListener('click', function (e) {

        const button = e.target.closest('.btn-add');

        if (!button) return;

        const id = button.dataset.id;
        const name = button.dataset.name;
        const price = parseInt(button.dataset.price, 10);

        if (!cart[id]) {

            cart[id] = {
                id: id,
                name: name,
                price: price,
                qty: 1
            };

        } else {

            cart[id].qty++;
        }

        renderCart();
    });

    // =========================================================
    // INCREASE
    // =========================================================

    window.increase = function (id) {

        if (!cart[id]) return;

        cart[id].qty++;

        renderCart();
    };

    // =========================================================
    // DECREASE
    // =========================================================

    window.decrease = function (id) {

        if (!cart[id]) return;

        cart[id].qty--;

        if (cart[id].qty <= 0) {
            delete cart[id];
        }

        renderCart();
    };

    // =========================================================
    // CLEAR CART
    // =========================================================

    window.clearCart = function () {

        cart = {};

        renderCart();
    };

    // =========================================================
    // CHECKOUT
    // =========================================================

    window.checkout = async function () {

        if (isCheckoutProcessing) return;

        if (Object.keys(cart).length === 0) {
            alert('Cart kosong');
            return;
        }

        let subtotal = 0;
        let totalItem = 0;

        Object.values(cart).forEach(item => {

            subtotal +=
                Number(item.qty) *
                Number(item.price);

            totalItem += Number(item.qty);
        });

        const tax = 0;
        const total = subtotal;

        // =====================================================
        // PAYMENT METHOD
        // =====================================================

        let paymentMethod = 'cash';

        // =====================================================
        // PAYMENT MODAL
        // =====================================================

        loadingContent.innerHTML = `

            <div
                id="payment-modal-box"
                style="
                    position:relative;
                    display:flex;
                    flex-direction:column;

                    width:100%;
                    max-width:900px;

                    height:min(760px, calc(100dvh - 30px));
                    max-height:calc(100dvh - 30px);

                    min-width:0;
                    min-height:0;

                    margin:0;
                    padding:16px;

                    box-sizing:border-box;

                    background:#ffffff;
                    border-radius:16px;

                    overflow:hidden;

                    box-shadow:0 20px 50px rgba(0,0,0,.25);
                "
            >

                <!-- HEADER -->

                <div
                    style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;

                        width:100%;
                        min-width:0;

                        flex-shrink:0;

                        margin-bottom:12px;
                        box-sizing:border-box;
                    "
                >

                    <h2
                        style="
                            margin:0;
                            padding:0;

                            min-width:0;

                            flex:1;

                            font-size:24px;
                            font-weight:700;
                            line-height:1.2;

                            color:#1f2937;

                            white-space:nowrap;
                            overflow:hidden;
                            text-overflow:ellipsis;
                        "
                    >
                        Pembayaran
                    </h2>

                    <button
                        id="close-payment"
                        type="button"
                        style="
                            flex-shrink:0;

                            width:40px;
                            height:40px;

                            min-width:40px;
                            min-height:40px;

                            margin:0 0 0 8px;
                            padding:0;

                            border:0;
                            border-radius:50%;

                            background:transparent;

                            display:flex;
                            align-items:center;
                            justify-content:center;

                            color:#6b7280;

                            font-size:28px;
                            font-weight:700;
                            line-height:1;

                            cursor:pointer;
                        "
                    >
                        ×
                    </button>

                </div>

                <!-- TAB -->

                <div
                    style="
                        display:flex;

                        width:100%;
                        min-width:0;

                        gap:8px;

                        flex-shrink:0;

                        margin-bottom:12px;

                        box-sizing:border-box;
                    "
                >

                    <button
                        id="cash-tab"
                        type="button"
                        style="
                            flex:1 1 0;

                            width:0;
                            min-width:0;

                            box-sizing:border-box;

                            padding:10px 6px;

                            border:1px solid #2563eb;
                            border-radius:10px;

                            background:#2563eb;
                            color:white;

                            font-size:17px;
                            font-weight:600;

                            cursor:pointer;
                        "
                    >
                        Cash
                    </button>

                    <button
                        id="qris-tab"
                        type="button"
                        style="
                            flex:1 1 0;

                            width:0;
                            min-width:0;

                            box-sizing:border-box;

                            padding:10px 6px;

                            border:1px solid #d1d5db;
                            border-radius:10px;

                            background:white;
                            color:#374151;

                            font-size:17px;
                            font-weight:600;

                            cursor:pointer;
                        "
                    >
                        QRIS
                    </button>

                </div>

                <!-- CONTENT -->

                <div
                    id="payment-content-wrapper"
                    style="
                        width:100%;
                        min-width:0;
                        min-height:0;

                        flex:1 1 auto;

                        overflow-y:auto;
                        overflow-x:hidden;

                        box-sizing:border-box;

                        -webkit-overflow-scrolling:touch;

                        scrollbar-width:thin;
                    "
                >

                    <!-- CASH -->

                    <div
                        id="payment-body"
                        style="
                            width:100%;
                            max-width:100%;
                            min-width:0;

                            box-sizing:border-box;

                            overflow:visible;
                        "
                    ></div>

                    <!-- QRIS -->

                    <div
                        id="payment-qris"
                        style="
                            width:100%;
                            max-width:100%;
                            min-width:0;

                            box-sizing:border-box;

                            overflow:visible;

                            display:none;
                        "
                    ></div>

                </div>

                <!-- FOOTER -->

                <div
                    style="
                        width:100%;
                        min-width:0;

                        flex-shrink:0;

                        margin-top:12px;
                        padding-top:10px;

                        border-top:1px solid #e5e7eb;

                        box-sizing:border-box;
                    "
                >

                    <button
                        id="process-payment"
                        type="button"
                        style="
                            display:block;

                            width:100%;
                            min-width:0;
                            max-width:100%;

                            box-sizing:border-box;

                            padding:12px 8px;

                            border:0;
                            border-radius:10px;

                            background:#2563eb;
                            color:white;

                            font-size:18px;
                            font-weight:600;

                            cursor:pointer;
                        "
                    >
                        Proses Pembayaran
                    </button>

                </div>

            </div>

            <style>

                @media (max-width: 640px) {

                    #payment-modal-box {
                        width:100% !important;
                        max-width:100% !important;

                        height:calc(100dvh - 16px) !important;
                        max-height:calc(100dvh - 16px) !important;

                        padding:12px !important;

                        border-radius:14px !important;
                    }

                    #payment-modal-box h2 {
                        font-size:20px !important;
                    }

                    #payment-content-wrapper {
                        overflow-y:auto !important;
                        overflow-x:hidden !important;

                        -webkit-overflow-scrolling:touch !important;
                    }

                    #payment-body,
                    #payment-qris {
                        width:100% !important;
                        min-height:auto !important;
                        height:auto !important;
                    }

                    #cash-content-grid,
                    #qris-content-grid {
                        grid-template-columns:1fr !important;
                    }

                    #cash-change-box {
                        min-height:120px !important;
                    }

                    #qris-image-container img {
                        max-height:50vh !important;
                    }
                }

            </style>
        `;

        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');

        // =====================================================
        // CLOSE PAYMENT
        // =====================================================

        document
            .getElementById('close-payment')
            .onclick = function () {

                hideLoading();
            };

        // =====================================================
        // ELEMENT TAB
        // =====================================================

        const cashTab =
            document.getElementById('cash-tab');

        const qrisTab =
            document.getElementById('qris-tab');

        const paymentBody =
            document.getElementById('payment-body');

        const paymentQris =
            document.getElementById('payment-qris');

        // =====================================================
        // UPDATE TAB STYLE
        // =====================================================

        function updatePaymentTab() {

            if (paymentMethod === 'cash') {

                cashTab.style.background = '#2563eb';
                cashTab.style.color = '#ffffff';
                cashTab.style.borderColor = '#2563eb';

                qrisTab.style.background = '#ffffff';
                qrisTab.style.color = '#374151';
                qrisTab.style.borderColor = '#d1d5db';

            } else {

                qrisTab.style.background = '#2563eb';
                qrisTab.style.color = '#ffffff';
                qrisTab.style.borderColor = '#2563eb';

                cashTab.style.background = '#ffffff';
                cashTab.style.color = '#374151';
                cashTab.style.borderColor = '#d1d5db';
            }
        }

        // =====================================================
        // CASH VIEW
        // =====================================================

        function initCashView() {

            paymentMethod = 'cash';

            paymentBody.style.display = 'block';
            paymentQris.style.display = 'none';

            paymentBody.innerHTML = `

                <div
                    id="cash-content-grid"
                    style="
                        width:100%;
                        max-width:100%;

                        display:grid;

                        grid-template-columns:
                            minmax(0,1fr)
                            minmax(0,1fr);

                        gap:12px;

                        box-sizing:border-box;

                        padding:4px;
                    "
                >

                    <!-- KIRI -->

                    <div
                        style="
                            min-width:0;

                            display:flex;
                            flex-direction:column;

                            justify-content:center;

                            gap:12px;

                            box-sizing:border-box;
                        "
                    >

                        <!-- TOTAL -->

                        <div
                            style="
                                width:100%;

                                box-sizing:border-box;

                                border-radius:12px;

                                background:#f9fafb;
                                border:1px solid #e5e7eb;

                                padding:14px;

                                text-align:center;
                            "
                        >

                            <div
                                style="
                                    font-size:14px;
                                    color:#6b7280;
                                "
                            >
                                Total Pembayaran
                            </div>

                            <div
                                style="
                                    margin-top:4px;

                                    font-size:clamp(24px,3vw,34px);
                                    line-height:1.2;

                                    font-weight:700;
                                    color:#2563eb;

                                    word-break:break-word;
                                "
                            >
                                ${rupiah(total)}
                            </div>

                        </div>

                        <!-- INPUT -->

                        <div
                            style="
                                width:100%;
                                min-width:0;
                            "
                        >

                            <label
                                for="cash-input"
                                style="
                                    display:block;

                                    margin-bottom:6px;

                                    font-size:14px;
                                    font-weight:600;

                                    color:#374151;
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

                                style="
                                    display:block;

                                    width:100%;
                                    max-width:100%;
                                    min-width:0;

                                    box-sizing:border-box;

                                    padding:11px;

                                    border:2px solid #e5e7eb;
                                    border-radius:10px;

                                    outline:none;

                                    font-size:clamp(18px,2vw,25px);
                                    font-weight:700;

                                    color:#111827;

                                    background:white;
                                "
                            >

                        </div>

                        <!-- NOMINAL CEPAT -->

                        <div
                            style="
                                width:100%;
                                min-width:0;
                            "
                        >

                            <div
                                style="
                                    margin-bottom:6px;

                                    font-size:13px;
                                    color:#6b7280;
                                "
                            >
                                Nominal cepat
                            </div>

                            <div
                                style="
                                    width:100%;

                                    display:grid;

                                    grid-template-columns:
                                        repeat(4,minmax(0,1fr));

                                    gap:6px;

                                    box-sizing:border-box;
                                "
                            >

                                <button
                                    type="button"
                                    class="cash-btn"
                                    data-value="${total}"

                                    style="
                                        min-width:0;

                                        padding:10px 4px;

                                        box-sizing:border-box;

                                        border:2px solid #e5e7eb;
                                        border-radius:10px;

                                        background:white;

                                        font-size:14px;
                                        font-weight:600;

                                        color:#374151;
                                    "
                                >
                                    Pas
                                </button>

                                <button
                                    type="button"
                                    class="cash-btn"
                                    data-value="10000"

                                    style="
                                        min-width:0;

                                        padding:10px 4px;

                                        box-sizing:border-box;

                                        border:2px solid #e5e7eb;
                                        border-radius:10px;

                                        background:white;

                                        font-size:14px;
                                        font-weight:600;

                                        color:#374151;
                                    "
                                >
                                    10K
                                </button>

                                <button
                                    type="button"
                                    class="cash-btn"
                                    data-value="20000"

                                    style="
                                        min-width:0;

                                        padding:10px 4px;

                                        box-sizing:border-box;

                                        border:2px solid #e5e7eb;
                                        border-radius:10px;

                                        background:white;

                                        font-size:14px;
                                        font-weight:600;

                                        color:#374151;
                                    "
                                >
                                    20K
                                </button>

                                <button
                                    type="button"
                                    class="cash-btn"
                                    data-value="50000"

                                    style="
                                        min-width:0;

                                        padding:10px 4px;

                                        box-sizing:border-box;

                                        border:2px solid #e5e7eb;
                                        border-radius:10px;

                                        background:white;

                                        font-size:14px;
                                        font-weight:600;

                                        color:#374151;
                                    "
                                >
                                    50K
                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- KANAN -->

                    <div
                        style="
                            min-width:0;

                            display:flex;

                            align-items:stretch;
                            justify-content:center;

                            box-sizing:border-box;
                        "
                    >

                        <div
                            id="cash-change-box"
                            style="
                                width:100%;

                                min-width:0;
                                min-height:100%;

                                box-sizing:border-box;

                                border-radius:12px;

                                background:#f0fdf4;
                                border:1px solid #dcfce7;

                                display:flex;

                                flex-direction:column;

                                align-items:center;
                                justify-content:center;

                                padding:14px;

                                text-align:center;

                                overflow:hidden;
                            "
                        >

                            <div
                                style="
                                    font-size:14px;
                                    color:#15803d;
                                    font-weight:500;
                                "
                            >
                                Kembalian
                            </div>

                            <div
                                id="change-text"

                                style="
                                    margin-top:6px;

                                    max-width:100%;

                                    font-size:clamp(28px,4vw,48px);
                                    line-height:1.1;

                                    font-weight:700;

                                    color:#16a34a;

                                    word-break:break-word;
                                "
                            >
                                ${rupiah(0)}
                            </div>

                        </div>

                    </div>

                </div>
            `;

            updatePaymentTab();

            const cashInput =
                document.getElementById('cash-input');

            const changeText =
                document.getElementById('change-text');

            if (!cashInput || !changeText) return;

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

                    changeText.style.color =
                        '#dc2626';

                } else {

                    changeText.style.color =
                        '#16a34a';
                }
            }

            cashInput.addEventListener(
                'input',
                updateChange
            );

            paymentBody
                .querySelectorAll('.cash-btn')
                .forEach(btn => {

                    btn.addEventListener(
                        'click',
                        function () {

                            const value =
                                parseInt(
                                    this.dataset.value,
                                    10
                                );

                            cashInput.value =
                                Math.max(
                                    value,
                                    total
                                );

                            updateChange();
                        }
                    );
                });

            updateChange();
        }

        // =====================================================
        // QRIS VIEW
        // =====================================================

        function initQrisView() {

            paymentMethod = 'qris';

            paymentBody.style.display = 'none';
            paymentQris.style.display = 'block';

            updatePaymentTab();

            const qrisUrl =
                `${window.qrisImage}?v=${Date.now()}`;

            paymentQris.innerHTML = `

                <div
                    id="qris-content-grid"

                    style="
                        width:100%;
                        max-width:100%;

                        display:grid;

                        grid-template-columns:
                            minmax(0,1fr)
                            minmax(0,1fr);

                        gap:16px;

                        box-sizing:border-box;

                        padding:8px 4px 20px;
                    "
                >

                    <!-- KIRI -->

                    <div
                        style="
                            min-width:0;

                            display:flex;
                            flex-direction:column;

                            align-items:center;
                            justify-content:center;

                            text-align:center;

                            gap:10px;

                            box-sizing:border-box;
                        "
                    >

                        <div
                            style="
                                font-size:14px;
                                color:#6b7280;
                            "
                        >
                            Total Pembayaran
                        </div>

                        <div
                            style="
                                max-width:100%;

                                font-size:clamp(24px,3vw,38px);
                                line-height:1.2;

                                font-weight:700;

                                color:#2563eb;

                                word-break:break-word;
                            "
                        >
                            ${rupiah(total)}
                        </div>

                        <div
                            style="
                                max-width:280px;

                                font-size:13px;
                                line-height:1.5;

                                color:#6b7280;
                            "
                        >
                            Scan QRIS menggunakan aplikasi
                            pembayaran Anda
                        </div>

                        <div
                            style="
                                padding:7px 14px;

                                border-radius:999px;

                                background:#eff6ff;
                                color:#2563eb;

                                font-size:13px;
                                font-weight:600;
                            "
                        >
                            QRIS
                        </div>

                    </div>

                    <!-- KANAN -->

                    <div
                        id="qris-image-container"

                        style="
                            min-width:0;

                            display:flex;

                            align-items:center;
                            justify-content:center;

                            box-sizing:border-box;

                            padding:4px;
                        "
                    >

                        <button
                            id="qris-preview-button"
                            type="button"

                            title="Klik untuk memperbesar QRIS"

                            style="
                                position:relative;

                                display:flex;

                                align-items:center;
                                justify-content:center;

                                width:auto;
                                max-width:100%;

                                padding:6px;

                                box-sizing:border-box;

                                border:2px solid #e5e7eb;
                                border-radius:14px;

                                background:white;

                                box-shadow:
                                    0 4px 12px
                                    rgba(0,0,0,.08);

                                overflow:hidden;

                                cursor:pointer;
                            "
                        >

                            <img
                                src="${qrisUrl}"
                                alt="QRIS Pembayaran"

                                style="
                                    display:block;

                                    width:auto;
                                    height:auto;

                                    max-width:100%;
                                    max-height:38vh;

                                    object-fit:contain;

                                    box-sizing:border-box;
                                "
                            >

                            <div
                                style="
                                    position:absolute;

                                    right:7px;
                                    bottom:7px;

                                    width:34px;
                                    height:34px;

                                    display:flex;

                                    align-items:center;
                                    justify-content:center;

                                    border-radius:50%;

                                    background:rgba(0,0,0,.60);

                                    color:white;

                                    font-size:16px;
                                "
                            >
                                🔍
                            </div>

                        </button>

                    </div>

                </div>
            `;

            // =================================================
            // QRIS PREVIEW MODAL
            // =================================================

            const qrisModal = document.createElement('div');

            qrisModal.id = 'qris-image-modal';

            qrisModal.style.cssText = `
                position:fixed;
                inset:0;    

                width:100vw;
                height:100dvh;

                box-sizing:border-box;

                display:none;

                align-items:center;
                justify-content:center;

                padding:16px;

                background:rgba(0,0,0,.85);

                overflow:hidden;

                z-index:9999999;

                -webkit-overflow-scrolling:touch;

                touch-action:none;
            `;

            qrisModal.innerHTML = `

                <!-- CLOSE BUTTON -->

                <button
                    id="close-qris-preview"
                    type="button"
                    aria-label="Tutup preview QRIS"

                    style="
                        position:absolute;

                        top:16px;
                        right:16px;

                        z-index:99999999;

                        width:48px;
                        height:48px;

                        min-width:48px;
                        min-height:48px;

                        padding:0;
                        margin:0;

                        border:0;
                        border-radius:50%;

                        background:#ffffff;

                        color:#374151;

                        font-size:32px;
                        font-weight:700;

                        line-height:1;

                        display:flex;
                        align-items:center;
                        justify-content:center;

                        cursor:pointer;

                        pointer-events:auto;

                        user-select:none;
                        -webkit-user-select:none;

                        box-shadow:
                            0 4px 18px
                            rgba(0,0,0,.35);
                    "
                >
                    ×
                </button>


                <!-- IMAGE AREA -->

                <div
                    id="qris-preview-stage"

                    style="
                        position:relative;

                        width:100%;
                        height:100%;

                        display:flex;

                        align-items:center;
                        justify-content:center;

                        overflow:hidden;

                        touch-action:none;
                    "
                >

                    <img
                        id="qris-preview-image"

                        src="${qrisUrl}"

                        alt="QRIS Preview"

                        draggable="false"

                        style="
                            display:block;

                            width:auto;
                            height:auto;

                            max-width:90vw;
                            max-height:85dvh;

                            object-fit:contain;

                            box-sizing:border-box;

                            padding:8px;

                            background:white;

                            border-radius:12px;

                            box-shadow:
                                0 10px 40px
                                rgba(0,0,0,.30);

                            transform:
                                translate3d(0,0,0)
                                scale(1);

                            transform-origin:center center;

                            transition:
                                transform .15s ease;

                            user-select:none;
                            -webkit-user-select:none;

                            touch-action:none;

                            cursor:zoom-in;

                            will-change:transform;
                        "
                    >

                </div>


                <!-- ZOOM INFO -->

                <div
                    id="qris-zoom-info"

                    style="
                        position:absolute;

                        left:50%;
                        bottom:18px;

                        transform:translateX(-50%);

                        z-index:99999998;

                        padding:7px 12px;

                        border-radius:999px;

                        background:rgba(0,0,0,.65);

                        color:white;

                        font-size:13px;

                        white-space:nowrap;

                        pointer-events:none;

                        user-select:none;
                    "
                >
                    Pinch / scroll untuk zoom
                </div>
            `;

            document.body.appendChild(qrisModal);


            // =================================================
            // ELEMENT PREVIEW
            // =================================================

            const qrisButton =
                document.getElementById('qris-preview-button');

            const closeQris =
                qrisModal.querySelector('#close-qris-preview');

            const qrisImage =
                qrisModal.querySelector('#qris-preview-image');

            const qrisStage =
                qrisModal.querySelector('#qris-preview-stage');


            // =================================================
            // ZOOM STATE
            // =================================================

            let qrisScale = 1;

            let qrisTranslateX = 0;
            let qrisTranslateY = 0;

            let lastTapTime = 0;

            let isDragging = false;

            let dragStartX = 0;
            let dragStartY = 0;

            let startTranslateX = 0;
            let startTranslateY = 0;

            let pinchStartDistance = 0;
            let pinchStartScale = 1;


            // =================================================
            // APPLY TRANSFORM
            // =================================================

            function applyQrisTransform() {

                qrisScale = Math.max(
                    1,
                    Math.min(4, qrisScale)
                );

                qrisImage.style.transform = `
                    translate3d(
                        ${qrisTranslateX}px,
                        ${qrisTranslateY}px,
                        0
                    )
                    scale(${qrisScale})
                `;

                if (qrisScale > 1) {

                    qrisImage.style.cursor = 'grab';

                } else {

                    qrisImage.style.cursor = 'zoom-in';

                    qrisTranslateX = 0;
                    qrisTranslateY = 0;

                    qrisImage.style.transform = `
                        translate3d(0,0,0)
                        scale(1)
                    `;
                }
            }


            // =================================================
            // RESET ZOOM
            // =================================================

            function resetQrisZoom() {

                qrisScale = 1;

                qrisTranslateX = 0;
                qrisTranslateY = 0;

                qrisImage.style.transition =
                    'transform .15s ease';

                applyQrisTransform();
            }


            // =================================================
            // OPEN QRIS
            // =================================================

            qrisButton.onclick = function (e) {

                e.preventDefault();
                e.stopPropagation();

                qrisModal.style.display = 'flex';

                document.body.style.overflow = 'hidden';

                resetQrisZoom();
            };


            // =================================================
            // CLOSE QRIS
            // =================================================

            function closeQrisModal() {

                qrisModal.style.display = 'none';

                document.body.style.overflow = '';

                resetQrisZoom();

                isDragging = false;
            }


            // =================================================
            // CLOSE BUTTON
            // =================================================

            closeQris.onclick = function (e) {

                e.preventDefault();
                e.stopPropagation();

                closeQrisModal();
            };


            // =================================================
            // CLICK BACKDROP
            // =================================================

            qrisModal.addEventListener(
                'click',
                function (e) {

                    if (e.target === qrisModal) {

                        closeQrisModal();
                    }
                }
            );


            // =================================================
            // ESC
            // =================================================

            function qrisEscHandler(e) {

                if (
                    e.key === 'Escape' &&
                    qrisModal.style.display !== 'none'
                ) {

                    closeQrisModal();
                }
            }

            document.addEventListener(
                'keydown',
                qrisEscHandler
            );


            // =================================================
            // MOUSE WHEEL ZOOM
            // =================================================

            qrisStage.addEventListener(
                'wheel',
                function (e) {

                    e.preventDefault();

                    const delta =
                        e.deltaY > 0
                            ? -0.2
                            : 0.2;

                    qrisScale += delta;

                    qrisScale = Math.max(
                        1,
                        Math.min(4, qrisScale)
                    );

                    qrisImage.style.transition =
                        'transform .1s ease';

                    applyQrisTransform();
                },
                {
                    passive:false
                }
            );


            // =================================================
            // DOUBLE CLICK / DOUBLE TAP
            // =================================================

            qrisImage.addEventListener(
                'click',
                function (e) {

                    e.stopPropagation();

                    const now =
                        Date.now();

                    if (
                        now - lastTapTime <
                        300
                    ) {

                        if (qrisScale > 1) {

                            resetQrisZoom();

                        } else {

                            qrisScale = 2;

                            applyQrisTransform();
                        }
                    }

                    lastTapTime = now;
                }
            );


            // =================================================
            // MOUSE DRAG
            // =================================================

            qrisImage.addEventListener(
                'pointerdown',
                function (e) {

                    if (qrisScale <= 1) {
                        return;
                    }

                    e.preventDefault();

                    isDragging = true;

                    qrisImage.setPointerCapture(
                        e.pointerId
                    );

                    dragStartX = e.clientX;
                    dragStartY = e.clientY;

                    startTranslateX =
                        qrisTranslateX;

                    startTranslateY =
                        qrisTranslateY;

                    qrisImage.style.cursor =
                        'grabbing';
                }
            );


            qrisImage.addEventListener(
                'pointermove',
                function (e) {

                    if (!isDragging) {
                        return;
                    }

                    e.preventDefault();

                    qrisTranslateX =
                        startTranslateX +
                        (e.clientX - dragStartX);

                    qrisTranslateY =
                        startTranslateY +
                        (e.clientY - dragStartY);

                    qrisImage.style.transition =
                        'none';

                    applyQrisTransform();
                }
            );


            qrisImage.addEventListener(
                'pointerup',
                function () {

                    isDragging = false;

                    qrisImage.style.cursor =
                        qrisScale > 1
                            ? 'grab'
                            : 'zoom-in';
                }
            );


            qrisImage.addEventListener(
                'pointercancel',
                function () {

                    isDragging = false;
                }
            );


            // =================================================
            // TOUCH PINCH ZOOM
            // =================================================

            function getTouchDistance(
                touch1,
                touch2
            ) {

                const dx =
                    touch1.clientX -
                    touch2.clientX;

                const dy =
                    touch1.clientY -
                    touch2.clientY;

                return Math.sqrt(
                    dx * dx +
                    dy * dy
                );
            }


            qrisStage.addEventListener(
                'touchstart',
                function (e) {

                    if (e.touches.length === 2) {

                        e.preventDefault();

                        pinchStartDistance =
                            getTouchDistance(
                                e.touches[0],
                                e.touches[1]
                            );

                        pinchStartScale =
                            qrisScale;
                    }
                },
                {
                    passive:false
                }
            );


            qrisStage.addEventListener(
                'touchmove',
                function (e) {

                    if (e.touches.length !== 2) {
                        return;
                    }

                    e.preventDefault();

                    const currentDistance =
                        getTouchDistance(
                            e.touches[0],
                            e.touches[1]
                        );

                    if (
                        pinchStartDistance <= 0
                    ) {
                        return;
                    }

                    const ratio =
                        currentDistance /
                        pinchStartDistance;

                    qrisScale =
                        pinchStartScale *
                        ratio;

                    qrisScale =
                        Math.max(
                            1,
                            Math.min(
                                4,
                                qrisScale
                            )
                        );

                    qrisImage.style.transition =
                        'none';

                    applyQrisTransform();
                },
                {
                    passive:false
                }
            );


            qrisStage.addEventListener(
                'touchend',
                function () {

                    if (qrisScale <= 1) {

                        qrisTranslateX = 0;
                        qrisTranslateY = 0;
                    }

                    pinchStartDistance = 0;
                }
            );
        }

        // =====================================================
        // TAB CASH
        // =====================================================

        cashTab.onclick = function () {

            if (isCheckoutProcessing) return;

            initCashView();
        };

        // =====================================================
        // TAB QRIS
        // =====================================================

        qrisTab.onclick = function () {

            if (isCheckoutProcessing) return;

            initQrisView();
        };

        // =====================================================
        // DEFAULT CASH
        // =====================================================

        initCashView();

        // =====================================================
        // PROCESS PAYMENT
        // =====================================================

        document
            .getElementById('process-payment')
            .onclick = async function () {

                if (isCheckoutProcessing) return;

                const btn =
                    document.getElementById(
                        'process-payment'
                    );

                try {

                    isCheckoutProcessing = true;

                    btn.disabled = true;

                    btn.innerHTML =
                        'Memproses...';

                    // =========================================
                    // DEFAULT PAYMENT
                    // =========================================

                    let receivedAmount = total;

                    // =========================================
                    // VALIDASI CASH
                    // =========================================

                    if (paymentMethod === 'cash') {

                        const cashInput =
                            document.getElementById(
                                'cash-input'
                            );

                        receivedAmount =
                            parseInt(
                                cashInput?.value || 0,
                                10
                            );

                        if (
                            isNaN(receivedAmount) ||
                            receivedAmount <= 0
                        ) {

                            alert(
                                'Masukkan jumlah uang yang diterima.'
                            );

                            isCheckoutProcessing = false;

                            btn.disabled = false;

                            btn.innerHTML =
                                'Proses Pembayaran';

                            return;
                        }

                        if (
                            receivedAmount < total
                        ) {

                            alert(
                                `Uang diterima kurang.\n\n` +
                                `Total: ${rupiah(total)}\n` +
                                `Diterima: ${rupiah(receivedAmount)}\n` +
                                `Kurang: ${rupiah(total - receivedAmount)}`
                            );

                            isCheckoutProcessing = false;

                            btn.disabled = false;

                            btn.innerHTML =
                                'Proses Pembayaran';

                            return;
                        }
                    }

                    // =========================================
                    // SIMPAN ITEM UNTUK PRINT
                    // =========================================

                    const printItems =
                        Object.values(cart).map(
                            item => ({
                                id: item.id,
                                name: item.name,
                                price: Number(item.price),
                                qty: Number(item.qty)
                            })
                        );

                    console.log(
                        'PRINT ITEMS:',
                        printItems
                    );

                    // =========================================
                    // TRANSAKSI
                    // =========================================

                    const res =
                        await fetch(
                            '/transactions',
                            {
                                method: 'POST',

                                credentials:
                                    'same-origin',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        document.querySelector(
                                            'meta[name="csrf-token"]'
                                        ).content
                                },

                                body:
                                    JSON.stringify({

                                        cart:
                                            Object.values(cart),

                                        outlet:
                                            window.transactionOutlet(),

                                        payment_method:
                                            paymentMethod,

                                        payment_amount:
                                            Number(
                                                receivedAmount
                                            ),

                                        received_amount:
                                            Number(
                                                receivedAmount
                                            ),

                                        change_amount:
                                            paymentMethod === 'cash'
                                                ? Math.max(
                                                    Number(
                                                        receivedAmount
                                                    ) -
                                                    Number(total),
                                                    0
                                                )
                                                : 0
                                    })
                            }
                        );

                    const data =
                        await res.json();

                    console.log(
                        'TRANSACTION RESPONSE:',
                        data
                    );

                    if (!res.ok) {
                        throw data;
                    }

                    // =========================================
                    // PRINT
                    // =========================================

                    if (
                        window.AndroidPrinter &&
                        typeof window.AndroidPrinter.printReceipt ===
                            'function'
                    ) {

                        try {

                            const isCash =
                                paymentMethod ===
                                'cash';

                            const printData = {

                                order_number:
                                    data.order_number ||
                                    '',

                                kasir:
                                    data.kasir ||
                                    data.user?.name ||
                                    '',

                                payment_method:
                                    paymentMethod,

                                created_at:
                                    data.created_at ||
                                    new Date()
                                        .toLocaleString(
                                            'id-ID'
                                        ),

                                items:
                                    printItems,

                                total:
                                    Number(
                                        data.total ??
                                        total
                                    ),

                                payment_amount:
                                    isCash
                                        ? Number(
                                            receivedAmount
                                        )
                                        : Number(total),

                                received_amount:
                                    isCash
                                        ? Number(
                                            receivedAmount
                                        )
                                        : Number(total),

                                change_amount:
                                    isCash
                                        ? Math.max(
                                            Number(
                                                receivedAmount
                                            ) -
                                            Number(total),
                                            0
                                        )
                                        : 0
                            };

                            console.log(
                                '=== DATA PRINT ==='
                            );

                            console.log(
                                JSON.stringify(
                                    printData,
                                    null,
                                    2
                                )
                            );

                            window.AndroidPrinter.printReceipt(
                                JSON.stringify(
                                    printData
                                )
                            );

                        } catch (
                            printError
                        ) {

                            console.error(
                                'Gagal mengirim ke printer:',
                                printError
                            );
                        }

                    } else {

                        console.warn(
                            'AndroidPrinter tidak tersedia'
                        );
                    }

                    // =========================================
                    // TRANSAKSI BERHASIL
                    // =========================================

                    loadingContent.innerHTML = `

                        <div
                            class="
                                bg-white
                                rounded-2xl
                                p-10
                                w-full
                                max-w-lg
                            "
                        >

                            <div class="text-center">

                                <div
                                    class="
                                        text-3xl
                                        font-bold
                                        text-green-600
                                    "
                                >
                                    ✓
                                </div>

                                <h2
                                    class="
                                        text-2xl
                                        font-bold
                                        mt-4
                                    "
                                >
                                    Transaksi Berhasil
                                </h2>

                                <div class="mt-2">
                                    ${data.order_number}
                                </div>

                                <div
                                    class="
                                        text-xl
                                        font-bold
                                        mt-2
                                    "
                                >
                                    ${rupiah(data.total)}
                                </div>

                                <button
                                    id="finish-payment"
                                    class="
                                        w-full
                                        mt-6
                                        bg-blue-600
                                        text-white
                                        py-3
                                        rounded-lg
                                    "
                                >
                                    Pesanan Baru
                                </button>

                            </div>

                        </div>
                    `;

                    // =========================================
                    // CLEAR CART
                    // =========================================

                    cart = {};

                    renderCart();

                    document
                        .getElementById(
                            'finish-payment'
                        )
                        .onclick =
                        function () {

                            hideLoading();
                        };

                } catch (err) {

                    console.error(
                        'TRANSACTION ERROR:',
                        err
                    );

                    hideLoading();

                    alert(
                        err?.error ||
                        err?.message ||
                        'Gagal transaksi'
                    );

                } finally {

                    const currentBtn =
                        document.getElementById(
                            'process-payment'
                        );

                    if (currentBtn) {

                        currentBtn.disabled =
                            false;

                        currentBtn.innerHTML =
                            'Proses Pembayaran';
                    }

                    isCheckoutProcessing =
                        false;
                }
            };
    };

    // =========================================================
    // INITIAL CART
    // =========================================================

    renderCart();

});