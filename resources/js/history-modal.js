document.addEventListener('DOMContentLoaded', () => {

    const overlay = document.getElementById('history-modal-overlay');
    const content = document.getElementById('history-modal-content');

    function rupiah(value) {

        return 'Rp ' + Number(value ?? 0)
            .toLocaleString('id-ID');

    }


    function closeModal(){

        overlay.classList.add('hidden');
        overlay.classList.remove('flex');

        content.innerHTML = '';

    }


    document.addEventListener('click', async function(e){

        const btn = e.target.closest('.btn-detail');

        if(!btn) return;


        const id = btn.dataset.id;


        overlay.classList.remove('hidden');
        overlay.classList.add('flex');


        content.innerHTML = `

            <div class="bg-white rounded-xl p-10">
                Memuat detail...
            </div>

        `;


        const res = await fetch(
            `/transactions/${id}/detail`
        );


        const data = await res.json();


        console.log(data.trx);



        let itemsHTML = '';


        data.items.forEach(item => {

            itemsHTML += `

            <div class="flex justify-between">

                <span>
                    ${item.qty}x ${item.menu_name}
                </span>

                <span>
                    ${rupiah(item.subtotal)}
                </span>

            </div>

            `;

        });
console.log(
    "TOTAL",
    data.trx.total
);

console.log(
    "BAYAR",
    data.trx.payment_amount
);

console.log(
    "KEMBALIAN",
    data.trx.change_amount
);


        content.innerHTML = `


        <div class="bg-white rounded-xl p-6 w-full max-w-xl">


            <div class="flex justify-between mb-5">

                <h2 class="text-xl font-bold">
                    Detail Transaksi
                </h2>


                <button id="close-modal"
                    class="text-xl">

                    ×

                </button>


            </div>



            <div class="font-mono border rounded-lg p-5">


                <div class="text-center mb-4">

                    <div class="font-bold text-xl">
                        WISH CHICKEN
                    </div>

                    <div>
                        ${data.trx.order_number}
                    </div>

                </div>



                <hr>



                <div class="space-y-1 my-4">


                    <div>
                        Kasir :
                        ${data.trx.kasir_name}
                    </div>


                    <div>
                        Outlet :
                        ${data.trx.nama_outlet}
                    </div>


                    <div>
                        Metode :
                        ${data.trx.payment_method.toUpperCase()}
                    </div>


                </div>



                <hr>



                <div class="space-y-2 my-4">

                    ${itemsHTML}

                </div>



                <hr>



                <div class="space-y-2 mt-4">


                    <div class="flex justify-between">

                        <span>
                            TOTAL
                        </span>

                        <span>
                            ${rupiah(data.trx.total)}
                        </span>

                    </div>



                    <div class="flex justify-between">

                        <span>
                            BAYAR
                        </span>

                        <span>
                            ${rupiah(
                                data.trx.payment_amount
                            )}
                        </span>

                    </div>



                    <div class="flex justify-between font-bold text-green-600">

                        <span>
                            KEMBALIAN
                        </span>

                        <span>
                            ${rupiah(
                                data.trx.change_amount
                            )}
                        </span>

                    </div>


                </div>



            </div>


        </div>


        `;


        document
        .getElementById('close-modal')
        .onclick = closeModal;


    });


    overlay.addEventListener('click', e=>{

        if(e.target === overlay){

            closeModal();

        }

    });


});