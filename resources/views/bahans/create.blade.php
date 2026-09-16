@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6">
            ➕ Tambah Stok Bahan
        </h2>

        <form action="{{ route('bahans.store') }}" method="POST">
            @csrf

            {{-- OUTLET --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Outlet</label>
                <select name="nama_outlet" class="w-full border rounded-lg p-2">
                    <option value="" disabled selected>-- Pilih Outlet --</option>
                    <option>Outlet 1</option>
                    <option>Outlet 2</option>
                    <option>Outlet 3</option>
                </select>
            </div>

            @php
                $inputs = [
                    'ayam' => 'Ayam',
                    'tepung' => 'Tepung',
                    'gula' => 'Gula',
                    'teh' => 'Teh',
                    'beras' => 'Beras',
                    'cup' => 'Cup',
                    'kertas_chicken_kecil' => 'Kertas Chicken Kecil',
                    'kertas_chicken_sedang' => 'Kertas Chicken Sedang',
                    'kertas_chicken_besar' => 'Kertas Chicken Besar',
                    'dus_chicken' => 'Dus Chicken',
                    'dus_chicken_jumbo' => 'Dus Chicken Jumbo',
                    'plastik_cup_1' => 'Plastik Cup Isi 1',
                    'plastik_cup_2' => 'Plastik Cup Isi 2',
                    'plastik_ayam_kecil' => 'Plastik Ayam Kecil',
                    'plastik_sedang' => 'Plastik Sedang',
                    'plastik_tanggung' => 'Plastik Tanggung',
                    'plastik_besar' => 'Plastik Besar',
                    'plastik_jumbo' => 'Plastik Jumbo',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($inputs as $name => $label)
                <div>
                    <label class="block text-sm font-medium">{{ $label }}</label>
                    <input type="number" name="{{ $name }}" min="0" value="0"
                        class="w-full border rounded-lg p-2">
                </div>
                @endforeach
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('dashboard') }}"
                   class="mr-4 px-4 py-2 bg-gray-200 rounded-lg">
                    Kembali
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
