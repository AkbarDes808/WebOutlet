@if(isset($history) && $history->isNotEmpty())
    @php
        $fields = ['tepung_roti' => 'Tepung Roti', 'tepung_bumbu' => 'Tepung Bumbu', 'garam' => 'Garam', 'bubuk_cabe' => 'Bubuk Cabe', 'telur' => 'Telur', 'gula' => 'Gula', 'ayam' => 'Ayam'];
    @endphp

    <div class="md:hidden space-y-4">
        @foreach($history as $record)
            <div class="bg-white border rounded-lg shadow overflow-hidden">
                <div class="bg-gray-50 p-3 flex justify-between items-center">
                    <p class="font-bold text-gray-800">{{ $record->created_at->format('d M Y, H:i') }}</p>
                    <p class="font-semibold text-blue-600 text-sm px-2 py-1 bg-blue-100 rounded">{{ $record->nama_outlet }}</p>
                </div>
                <div class="p-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                    @foreach($fields as $key => $label)
                        <div class="text-gray-600">{{ $label }}</div>
                        <div class="font-medium text-right">
                            <span>{{ $record->data[$key]->total }}</span>
                            @php $change = $record->data[$key]->change; @endphp
                            @if($change > 0) <span class="text-green-600 text-xs ml-1">(+{{ $change }})</span>
                            @elseif($change < 0) <span class="text-red-600 text-xs ml-1">({{ $change }})</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="overflow-x-auto hidden md:block">
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Tanggal & Waktu</th> <th class="p-2 border">Outlet</th>
                    @foreach($fields as $label) <th class="p-2 border">{{ $label }}</th> @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $record)
                    <tr class="text-center bg-white">
                        <td class="p-2 border whitespace-nowrap">{{ $record->created_at->format('d M Y, H:i:s') }}</td>
                        <td class="p-2 border font-semibold">{{ $record->nama_outlet }}</td>
                        @foreach($fields as $key => $label)
                            <td class="p-2 border">
                                <span>{{ $record->data[$key]->total }}</span>
                                @php $change = $record->data[$key]->change; @endphp
                                @if($change > 0) <span class="block text-green-600 text-xs">(+{{ $change }})</span>
                                @elseif($change < 0) <span class="block text-red-600 text-xs">({{ $change }})</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center p-6 text-gray-500 bg-white border rounded-lg">
        <p>Tidak ada data riwayat untuk ditampilkan sesuai filter yang dipilih.</p>
    </div>
@endif