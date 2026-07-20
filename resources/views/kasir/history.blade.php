@extends('layouts.app')

@section('content')

<div class="bg-white rounded-xl p-6 shadow-sm">

    <!-- TITLE -->
    <h1 class="text-xl font-semibold text-gray-800">Riwayat Pesanan</h1>
    <p class="text-sm text-gray-500 mb-4">
        Semua transaksi penjualan dari kasir
    </p>

    <!-- FILTER -->
    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-center gap-3 mb-6">

        <!-- Outlet -->
        <select name="outlet" class="border rounded-lg px-3 py-2 text-sm text-gray-600">
            <option value="">-- Semua Outlet --</option>
            {{-- kalau ada data outlet --}}
            @foreach($outlets ?? [] as $o)
                <option value="{{ $o }}" {{ request('outlet') == $o ? 'selected' : '' }}>
                    {{ $o }}
                </option>
            @endforeach
        </select>

        <!-- Date From -->
        <input type="date"
            name="from"
            value="{{ request('from') }}"
            class="border rounded-lg px-3 py-2 text-sm text-gray-600">

        <span class="text-gray-400 text-sm">s/d</span>

        <!-- Date To -->
        <input type="date"
            name="to"
            value="{{ request('to') }}"
            class="border rounded-lg px-3 py-2 text-sm text-gray-600">

        <!-- Status -->
        <select name="status" class="border rounded-lg px-3 py-2 text-sm text-gray-600">
            <option value="">Semua Status</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Selesai</option>
            <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>Void</option>
        </select>

        <!-- Metode -->
        <select name="payment_method" class="border rounded-lg px-3 py-2 text-sm text-gray-600">
            <option value="">Semua Metode</option>
            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>

        <!-- Button -->
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Filter
        </button>

    </form>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="text-gray-500 text-xs uppercase border-b">
                <tr>
                    <th class="py-3 text-left">Tanggal</th>
                    <th class="text-left">Outlet</th>
                    <th class="text-left">Order #</th>
                    <th class="text-left">Kasir</th>
                    <th class="text-left">Items</th>
                    <th class="text-left">Metode</th>
                    <th class="text-left">Total</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">

                @foreach($transactions as $trx)
                <tr class="border-b hover:bg-gray-50">

                    <!-- Tanggal -->
                    <td class="py-3">
                        {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}
                    </td>

                    <!-- Outlet -->
                    <td>{{ $trx->nama_outlet }}</td>

                    <!-- Order -->
                    <td class="text-blue-600 font-medium">
                        #{{ $trx->order_number }}
                    </td>

                    <!-- Kasir -->
                    <td>{{ $trx->kasir }}</td>

                    <!-- Items -->
                    <td>{{ $trx->items_count ?? 0 }} items</td>

                   <!-- Metode -->
                    <td>
                        @if(($trx->payment_method ?? 'cash') === 'qris')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold">
                                QRIS
                            </span>
                        @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                CASH
                            </span>
                        @endif
                    </td>

                    <!-- Total -->
                    <td class="font-semibold">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>

                    <!-- Status -->
                    <td>
                        @if($trx->status == 'paid')
                            <span class="text-gray-700">Selesai</span>
                        @else
                            <span class="text-red-500">Void</span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td>
                        <button
                            class="btn-detail text-blue-600 hover:underline text-sm"
                            data-id="{{ $trx->id }}">
                            Detail
                        </button>
                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>
    </div>
    <!-- FOOTER -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-500">

        <p>
            Menampilkan {{ $transactions->firstItem() ?? 0 }}-
            {{ $transactions->lastItem() ?? 0 }}
            dari {{ $transactions->total() }} transaksi
        </p>

        <div class="flex gap-2">
            {{ $transactions->links('pagination::tailwind') }}
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {


    function closeModal(){
        const modal =
        document.getElementById('detail-modal');

        if(modal){
            modal.remove();
        }

    }
    function rupiah(value){

        return 'Rp ' +
        Number(value ?? 0)
        .toLocaleString('id-ID');

    }

    document.addEventListener('click', async function(e){


        const btn =
            e.target.closest('.btn-detail');


        if(!btn) return;

        const id = btn.dataset.id;
        document.body.insertAdjacentHTML(
        'beforeend',

        `

        <div id="detail-modal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">


            <div class="bg-white rounded-xl p-8 flex flex-col items-center gap-4">


                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>


                <div class="text-gray-600 font-medium">

                    Memuat detail...

                </div>


            </div>


        </div>

        `

        );



        try {


            const res =
            await fetch(`/transactions/${id}/detail`);



            const data =
            await res.json();



            let itemsHTML = '';



            data.items.forEach(item=>{


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





           document.getElementById('detail-modal').innerHTML = `


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


                    <div class="my-4 space-y-1">


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



                    <div class="my-4 space-y-2">

                        ${itemsHTML}

                    </div>



                    <hr>



                    <div class="mt-4 space-y-2">

                        <div class="flex justify-between">

                            <span>
                                BAYAR
                            </span>

                            <span>
                                ${rupiah(data.trx.payment_amount)}
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span>
                                KEMBALIAN
                            </span>

                            <span>
                                ${rupiah(data.trx.change_amount)}
                            </span>

                        </div>

                        <div class="flex justify-between font-bold text-green-600">

                            <span>
                                TOTAL
                            </span>

                            <span>
                                ${rupiah(data.trx.total)}
                            </span>

                        </div>
                    </div>


                </div>


            </div>


            `;




            document
            .getElementById('close-modal')
            .onclick = closeModal;



        }

        catch(error){

            console.error(error);

            modal.innerHTML = `

            <div class="bg-white p-6 rounded-xl">
                Gagal mengambil detail transaksi
            </div>

            `;

        }


    });





    document.addEventListener('click', e => {

        const modal = document.getElementById('detail-modal');

        if(
            modal &&
            e.target === modal
        ){

            closeModal();

        }

    });



});
</script>
@endsection