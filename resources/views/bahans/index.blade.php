@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Stok Bahan</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(Auth::user()->role !== 'outlet')
    <div class="flex justify-between items-center mb-6">
        {{-- Form untuk memilih outlet --}}
        <form action="{{ route('bahans.index') }}" method="GET" class="flex-grow">
            <label for="outlet" class="block text-xl font-semibold mb-2">Pilih Outlet</label>
            <div class="flex">
                <select name="outlet" id="outlet" onchange="this.form.submit()" class="block w-full md:w-1/2 border rounded p-2 bg-white shadow-sm">
                    <option value="">-- Tampilkan Total Stok Semua Outlet --</option>
                    @foreach ($outlets as $outlet)
                        <option value="{{ $outlet }}" {{ ($selectedOutlet ?? '') == $outlet ? 'selected' : '' }}>
                            {{ $outlet }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- Tombol Tambah Data Baru --}}
        <div class="mt-8 ml-4">
            <a href="{{ route('bahans.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow-sm hover:bg-indigo-700 whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Data Baru
            </a>
        </div>
    </div>
    @endif
    @if($selectedOutlet)
        {{-- Bagian ini akan tampil JIKA ada outlet yang dipilih --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Stok Saat Ini (Outlet: {{ $selectedOutlet }})</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border text-sm text-center">
                    {{-- ... isi tabel stok ... --}}
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Bahan</th>
                            <th class="p-2 border">Jumlah (unit)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($bahan))
                            <tr><td class="p-2 border text-left">Tepung Roti</td><td class="p-2 border">{{ $bahan->tepung_roti ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Tepung Bumbu</td><td class="p-2 border">{{ $bahan->tepung_bumbu ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Garam</td><td class="p-2 border">{{ $bahan->garam ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Bubuk Cabe</td><td class="p-2 border">{{ $bahan->bubuk_cabe ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Telur</td><td class="p-2 border">{{ $bahan->telur ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Gula</td><td class="p-2 border">{{ $bahan->gula ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Ayam</td><td class="p-2 border">{{ $bahan->ayam ?? 0 }}</td></tr>
                        @else
                            <tr>
                                <td colspan="2" class="p-4 border text-center text-gray-500">
                                    Belum ada data stok untuk outlet ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t pt-6">
    @if(Auth::user()->role === 'outlet')
        {{-- TAMPILAN FORM KHUSUS UNTUK OUTLET --}}
        <h2 class="text-xl font-semibold mb-2">Input Bahan Terpakai</h2>
        <p class="text-sm text-gray-600 mb-4">Isi jumlah bahan yang digunakan (hanya bisa mengurangi stok).</p>
        
        <form action="{{ route('bahans.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="nama_outlet" value="{{ $selectedOutlet }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $fields = ['tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe', 'telur', 'gula', 'ayam'];
                @endphp
                @foreach($fields as $field)
                    <div>
                        <label for="{{ $field }}" class="block mb-1 font-medium">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                        {{-- 'max="0"' mencegah input angka positif --}}
                        <input type="number" id="{{ $field }}" name="{{ $field }}" max="0" class="w-full border rounded p-2" value="0" required>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Penggunaan Stok
            </button>
        </form>
    @else
        {{-- TAMPILAN FORM UNTUK ADMIN/SPV (TIDAK BERUBAH) --}}
        <h2 class="text-xl font-semibold mb-2">Input Perubahan Stok</h2>
        <p class="text-sm text-gray-600 mb-4">Isi angka positif (+) untuk menambah atau negatif (-) untuk mengurangi.</p>
        
        <form action="{{ route('bahans.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="nama_outlet" value="{{ $selectedOutlet }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $fields = ['tepung_roti', 'tepung_bumbu', 'garam', 'bubuk_cabe', 'telur', 'gula', 'ayam'];
                @endphp
                @foreach($fields as $field)
                    <div>
                        <label for="{{ $field }}" class="block mb-1 font-medium">{{ ucwords(str_replace('_', ' ', $field)) }}</label>
                        <input type="number" id="{{ $field }}" name="{{ $field }}" class="w-full border rounded p-2" value="0" required>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </form>
    @endif
</div>

    @else
        {{-- Bagian ini akan tampil JIKA TIDAK ada outlet yang dipilih --}}
        <div class="mb-6 p-4 border rounded-lg bg-gray-50">
            <h2 class="text-xl font-semibold mb-2 text-gray-800">📊 Total Stok (Semua Outlet)</h2>
            {{-- ... isi tabel total stok ... --}}
            @if(isset($totalStok))
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm text-center">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-2 border">Bahan</th>
                                <th class="p-2 border">Total Jumlah (unit)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr><td class="p-2 border text-left">Tepung Roti</td><td class="p-2 border font-medium">{{ $totalStok->tepung_roti ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Tepung Bumbu</td><td class="p-2 border font-medium">{{ $totalStok->tepung_bumbu ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Garam</td><td class="p-2 border font-medium">{{ $totalStok->garam ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Bubuk Cabe</td><td class="p-2 border font-medium">{{ $totalStok->bubuk_cabe ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Telur</td><td class="p-2 border font-medium">{{ $totalStok->telur ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Gula</td><td class="p-2 border font-medium">{{ $totalStok->gula ?? 0 }}</td></tr>
                            <tr><td class="p-2 border text-left">Ayam</td><td class="p-2 border font-medium">{{ $totalStok->ayam ?? 0 }}</td></tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 py-4">Belum ada data bahan untuk ditampilkan.</p>
            @endif
        </div>
    @endif
</div>
@endsection