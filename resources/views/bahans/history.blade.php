@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6">
    <h1 class="text-2xl font-bold mb-4">📊 Riwayat Perubahan Stok</h1>

    {{-- FILTER OUTLET --}}
    @if(Auth::user()->role !== 'outlet')
    <div class="mb-6 border-t pt-6">
        <form action="{{ route('bahans.history') }}" method="GET">
            <select name="outlet"
                    onchange="this.form.submit()"
                    class="block w-full md:w-1/3 border rounded p-2 bg-white">
                <option value="">-- Tampilkan Semua Outlet --</option>
                @foreach ($outlets as $outlet)
                    <option value="{{ $outlet }}"
                        {{ $selectedOutlet == $outlet ? 'selected' : '' }}>
                        {{ $outlet }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    @php
        /**
         * FORMAT ANGKA INDONESIA (DESIMAL AMAN)
         * 12.5   -> 12,5
         * 12.00  -> 12
         * 1250.5 -> 1.250,5
         */
        function formatAngka($value) {
            if ($value === null) return '0';

            $angka = (float) $value;
            $formatted = number_format($angka, 2, ',', '.');

            return rtrim(rtrim($formatted, '0'), ',');
        }

        $fields = [
            'tepung_roti'  => 'Tepung Roti',
            'tepung_bumbu' => 'Tepung Bumbu',
            'garam'        => 'Garam',
            'bubuk_cabe'   => 'Bubuk Cabe',
            'telur'        => 'Telur',
            'gula'         => 'Gula',
            'ayam'         => 'Ayam',
        ];
    @endphp

    {{-- ================= MOBILE ================= --}}
    <div class="md:hidden space-y-4">
        @forelse($history as $record)

            @php
                $hasChange = collect($record->data)
                    ->pluck('change')
                    ->contains(fn($v) => (float)$v != 0);
            @endphp

            @if(!$hasChange) @continue @endif

            <div class="bg-white border rounded-lg shadow">
                <div class="bg-gray-50 p-3 flex justify-between items-center">
                    <p class="font-semibold">
                        {{ $record->created_at->format('d M Y, H:i') }}
                    </p>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                        {{ $record->nama_outlet }}
                    </span>
                </div>

                <div class="p-3 grid grid-cols-2 gap-y-2 text-sm">
                    @foreach($fields as $key => $label)
                        @php
                            $change = (float) $record->data[$key]->change;
                            $total  = (float) $record->data[$key]->total;
                        @endphp

                        @if($change != 0)
                            <div class="text-gray-600">{{ $label }}</div>
                            <div class="text-right font-semibold">
                                {{ formatAngka($total) }}
                                <span class="text-xs ml-1
                                    {{ $change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ({{ $change > 0 ? '+' : '' }}{{ formatAngka($change) }})
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center p-6 text-gray-500 bg-white border rounded">
                Tidak ada riwayat perubahan.
            </div>
        @endforelse
    </div>

    {{-- ================= DESKTOP ================= --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full border text-sm bg-white">
            <thead class="bg-gray-100 text-center">
                <tr>
                    <th class="p-2 border">Tanggal & Waktu</th>
                    <th class="p-2 border">Outlet</th>
                    @foreach($fields as $label)
                        <th class="p-2 border">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($history as $record)

                    @php
                        $hasChange = collect($record->data)
                            ->pluck('change')
                            ->contains(fn($v) => (float)$v != 0);
                    @endphp

                    @if(!$hasChange) @continue @endif

                    <tr class="text-center">
                        <td class="p-2 border whitespace-nowrap">
                            {{ $record->created_at->format('d M Y, H:i:s') }}
                        </td>
                        <td class="p-2 border font-semibold">
                            {{ $record->nama_outlet }}
                        </td>

                        @foreach($fields as $key => $label)
                            @php
                                $change = (float) $record->data[$key]->change;
                                $total  = (float) $record->data[$key]->total;
                            @endphp
                            <td class="p-2 border">
                                @if($change != 0)
                                    <div class="font-semibold">
                                        {{ formatAngka($total) }}
                                    </div>
                                    <div class="text-xs
                                        {{ $change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $change > 0 ? '+' : '' }}{{ formatAngka($change) }}
                                    </div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fields) + 2 }}"
                            class="p-6 text-center text-gray-500">
                            Tidak ada riwayat perubahan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
