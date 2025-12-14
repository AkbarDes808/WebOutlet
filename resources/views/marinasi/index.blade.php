@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Marinasi</h1>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stok Saat Ini -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Stok Saat Ini</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Bahan</th>
                        <th class="p-2 border">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="p-2 border">Daging Ayam</td><td class="p-2 border">{{ $marinasi->daging_ayam ?? 0 }}</td></tr>
                    <tr><td class="p-2 border">Saus Teriyaki</td><td class="p-2 border">{{ $marinasi->saus_teriyaki ?? 0 }}</td></tr>
                    <tr><td class="p-2 border">Bawang Putih</td><td class="p-2 border">{{ $marinasi->bawang_putih ?? 0 }}</td></tr>
                    <tr><td class="p-2 border">Lada</td><td class="p-2 border">{{ $marinasi->lada ?? 0 }}</td></tr>
                    <tr><td class="p-2 border">Garam</td><td class="p-2 border">{{ $marinasi->garam ?? 0 }}</td></tr>
                    <tr><td class="p-2 border">Ketumbar</td><td class="p-2 border">{{ $marinasi->ketumbar ?? 0 }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Input Marinasi -->
    <form action="{{ route('marinasi.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach(['daging_ayam','saus_teriyaki','bawang_putih','lada','garam','ketumbar'] as $bahan)
                <div>
                    <label class="block mb-1 font-medium">{{ ucwords(str_replace('_', ' ', $bahan)) }}</label>
                    <input type="number" name="{{ $bahan }}" class="w-full border rounded p-2" required>
                </div>
            @endforeach
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
