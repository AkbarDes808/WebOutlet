@if(!empty($history))
    @php
        $historyCollection = collect($history);
        $outletNames = [
            'outlet 1' => 'Pusat',
            'outlet 2' => 'Indomaret',
            'outlet 3' => 'Bunderan',
            'outlet 4' => 'Mersi',
            'outlet 5' => 'Arca',
            'outlet 6' => 'Larangan',
            'outlet 7' => 'Unsoed',
        ];
    @endphp

    @if($historyCollection->isNotEmpty())
        <div class="md:hidden space-y-4">
            @foreach($historyCollection as $record)
                @php
                    $type = $record['type'] ?? '';
                    $outletRaw = trim((string) ($record['nama_outlet'] ?? ''));
                    $outletKey = strtolower($outletRaw);
                    $namaOutlet = $outletNames[$outletKey] ?? ($outletRaw ?: '-');
                    $namaKasir = $record['nama_kasir'] ?? '-';
                    $typeLabel = $record['type_label'] ?? '-';
                @endphp

                <div class="bg-white border rounded-lg shadow overflow-hidden">
                    <div class="bg-gray-50 p-3">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <p class="font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($record['created_at'])->format('d M Y, H:i:s') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $typeLabel }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="text-xs text-gray-500">Nama Outlet</p>
                            <p class="font-semibold text-blue-600">{{ $namaOutlet }}</p>
                        </div>

                        @if($type === 'usage')
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Nama Kasir</p>
                                <p class="font-semibold text-gray-800">{{ $namaKasir }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="p-3">
                        @if(!empty($record['order_number']))
                            <div class="mb-3 text-sm">
                                <span class="text-gray-500">Order:</span>
                                <span class="font-semibold">{{ $record['order_number'] }}</span>
                            </div>
                        @endif

                        <div class="space-y-2">
                            @foreach($record['items'] ?? [] as $item)
                                <div class="flex justify-between items-center border-b last:border-b-0 pb-2 last:pb-0">
                                    <div class="text-gray-600">
                                        {{ $item['nama'] ?? '-' }}
                                    </div>
                                    <div class="text-right">
                                        @if($type === 'input')
                                            <span class="font-medium">
                                                Total: {{ $item['total'] ?? 0 }}
                                            </span>

                                            @if(($item['change'] ?? 0) > 0)
                                                <span class="text-green-600 text-xs ml-1">
                                                    (+{{ $item['change'] }})
                                                </span>
                                            @elseif(($item['change'] ?? 0) < 0)
                                                <span class="text-red-600 text-xs ml-1">
                                                    ({{ $item['change'] }})
                                                </span>
                                            @endif
                                        @else
                                            <span class="font-medium text-red-600">
                                                {{ $item['change'] ?? 0 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="overflow-x-auto hidden md:block">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Tanggal & Waktu</th>
                        <th class="p-2 border">Jenis</th>
                        <th class="p-2 border">Nama Outlet</th>
                        <th class="p-2 border">Nama Kasir</th>
                        <th class="p-2 border">Order</th>
                        <th class="p-2 border">Item</th>
                        <th class="p-2 border">Perubahan</th>
                        <th class="p-2 border">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyCollection as $record)
                        @php
                            $type = $record['type'] ?? '';
                            $outletRaw = trim((string) ($record['nama_outlet'] ?? ''));
                            $outletKey = strtolower($outletRaw);
                            $namaOutlet = $outletNames[$outletKey] ?? ($outletRaw ?: '-');
                            $namaKasir = $record['nama_kasir'] ?? '-';
                        @endphp

                        @foreach($record['items'] ?? [] as $item)
                            <tr class="text-center bg-white">
                                <td class="p-2 border whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($record['created_at'])->format('d M Y, H:i:s') }}
                                </td>
                                <td class="p-2 border font-semibold">
                                    {{ $record['type_label'] ?? '-' }}
                                </td>
                                <td class="p-2 border">
                                    {{ $namaOutlet }}
                                </td>
                                <td class="p-2 border">
                                    @if($type === 'usage')
                                        {{ $namaKasir }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-2 border">
                                    {{ $record['order_number'] ?? '-' }}
                                </td>
                                <td class="p-2 border font-medium">
                                    {{ $item['nama'] ?? '-' }}
                                </td>
                                <td class="p-2 border">
                                    @if(($item['change'] ?? 0) > 0)
                                        <span class="text-green-600 font-semibold">
                                            +{{ $item['change'] }}
                                        </span>
                                    @elseif(($item['change'] ?? 0) < 0)
                                        <span class="text-red-600 font-semibold">
                                            {{ $item['change'] }}
                                        </span>
                                    @else
                                        <span>0</span>
                                    @endif
                                </td>
                                <td class="p-2 border">
                                    @if($type === 'input')
                                        {{ $item['total'] ?? 0 }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center p-6 text-gray-500 bg-white border rounded-lg">
            <p>Tidak ada data riwayat untuk ditampilkan sesuai filter yang dipilih.</p>
        </div>
    @endif
@else
    <div class="text-center p-6 text-gray-500 bg-white border rounded-lg">
        <p>Tidak ada data riwayat untuk ditampilkan sesuai filter yang dipilih.</p>
    </div>
@endif