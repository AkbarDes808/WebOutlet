@extends('layouts.app')

@section('content')
    <div>
        <div class="w-full h-52">
            <img src="https://picsum.photos/1600/900" alt="Random Banner From Picsum" class="object-cover w-full h-full">
        </div>

        <div class="p-4 sm:p-8">
            <h1 class="text-2xl font-semibold mb-6">Welcome back, {{ Auth::user()->name ?? 'User' }}!</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('bahans.index') }}" class="p-6 bg-white shadow rounded-xl flex flex-col items-start hover:shadow-md transition">
                    <div class="text-3xl mb-3">📋</div> <h2 class="font-semibold">Inventory</h2> <p class="text-sm text-gray-500">Ubah stok bahan</p>
                </a>

                {{-- Tombol ini disembunyikan untuk role 'spv' dan 'outlet', tidak peduli huruf besar/kecil --}}
                @if (strtolower(Auth::user()->role) !== 'spv' && strtolower(Auth::user()->role) !== 'outlet')
                    <a href="{{ route('marinasi.index') }}" class="p-6 bg-white shadow rounded-xl flex flex-col items-start hover:shadow-md transition">
                        <div class="text-3xl mb-3">🍗</div> <h2 class="font-semibold">Marinasi</h2> <p class="text-sm text-gray-500">Halaman untuk marinasi</p>
                    </a>
                @endif
                
                <a href="{{ route('dashboard', ['tab' => 'history']) }}" class="p-6 bg-white shadow rounded-xl flex flex-col items-start hover:shadow-md transition">
                    <div class="text-3xl mb-3">📜</div> <h2 class="font-semibold">History</h2> <p class="text-sm text-gray-500">Lihat riwayat perubahan</p>
                </a>
                
                {{-- Tombol ini disembunyikan jika role adalah 'outlet', tidak peduli huruf besar/kecil --}}
                @if(strtolower(Auth::user()->role) !== 'outlet')
                    <a href="{{ route('outlets.index') }}" class="p-6 bg-white shadow rounded-xl flex flex-col items-start hover:shadow-md transition">
                        <div class="text-3xl mb-3">🏪</div> <h2 class="font-semibold">Outlets</h2> <p class="text-sm text-gray-500">Lihat semua outlet</p>
                    </a>
                @endif
            </div>
            {{-- ... (Bagian Kartu Menu Anda) ... --}}

            <div class="p-4 sm:p-6 mt-10 bg-white rounded-xl shadow">
                {{-- ... (Bagian Tabs dan Filter Anda) ... --}}
                @if(Auth::user()->role !== 'outlet')
    <div class="bg-gray-50 p-4 rounded-lg border mb-6">
        <form method="GET" action="{{ route('dashboard') }}">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'inventory' }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                {{-- Dropdown untuk memilih outlet --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Outlet</label>
                    <select name="outlet" class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">Semua Outlet</option>
                        @foreach($outlets as $outlet)
                            <option value="{{ $outlet }}" {{ request('outlet') == $outlet ? 'selected' : '' }}>{{ $outlet }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Filter tanggal (hanya untuk tab history) --}}
                @if(($activeTab ?? 'inventory') == 'history')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                @endif
                
                {{-- Tombol Filter --}}
                <div class="{{ ($activeTab ?? 'inventory') == 'inventory' ? 'md:col-start-4' : '' }}">
                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
                </div>
            </div>
        </form>
    </div>
@endif
                @if(($activeTab ?? 'inventory') == 'inventory')
    {{-- TABEL INVENTORY --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-3">Bahan</th>
                    <th class="px-4 py-3">Total Stok Terkini</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $fields = ['tepung_roti' => 'Tepung Roti', 'tepung_bumbu' => 'Tepung Bumbu', 'garam' => 'Garam', 'bubuk_cabe' => 'Bubuk Cabe', 'telur' => 'Telur', 'gula' => 'Gula', 'ayam' => 'Ayam']; @endphp
                
                {{-- Gunakan $totalStok, bukan $totals --}}
                @if(isset($totalStok))
                    @foreach($fields as $key => $label)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $label }}</td>
                        {{-- Tampilkan langsung nilainya --}}
                        <td class="px-4 py-3">{{ $totalStok->$key ?? 0 }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="p-4 text-center text-gray-500">Tidak ada data stok untuk ditampilkan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@else
    {{-- TABEL HISTORY --}}
     @include('bahans._history_table', ['history' => $history]) 
@endif
            </div>
        </div>
    </div>
@endsection