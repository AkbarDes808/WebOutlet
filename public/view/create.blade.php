@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Card Form -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <!-- Header -->
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-indigo-600"></i>
            Tambah Bahan Baru
        </h2>

        <!-- Form -->
        <form action="{{ route('bahans.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Outlet -->
            <div>
                <label for="nama_outlet" class="block text-sm font-medium text-gray-700 mb-1">Pilih Outlet</label>
                <select name="nama_outlet" id="nama_outlet"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled selected>-- Pilih Outlet --</option>
                    <option value="Outlet 1">Outlet 1</option>
                    <option value="Outlet 2">Outlet 2</option>
                    <option value="Outlet 3">Outlet 3</option>
                    <option value="Outlet 4">Outlet 4</option>
                    <option value="Outlet 5">Outlet 5</option>
                </select>
                @error('nama_outlet')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid Input -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tepung Bumbu -->
                <div>
                    <label for="tepung_bumbu" class="block text-sm font-medium text-gray-700">Tepung Bumbu</label>
                    <input type="number" name="tepung_bumbu" id="tepung_bumbu" min="0" value="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('tepung_bumbu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Garam -->
                <div>
                    <label for="garam" class="block text-sm font-medium text-gray-700">Garam</label>
                    <input type="number" name="garam" id="garam" min="0" value="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('garam')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bubuk Cabe -->
                <div>
                    <label for="bubuk_cabe" class="block text-sm font-medium text-gray-700">Bubuk Cabe</label>
                    <input type="number" name="bubuk_cabe" id="bubuk_cabe" min="0" value="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('bubuk_cabe')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telur -->
                <div>
                    <label for="telur" class="block text-sm font-medium text-gray-700">Telur</label>
                    <input type="number" name="telur" id="telur" min="0" value="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('telur')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gula -->
                <div>
                    <label for="gula" class="block text-sm font-medium text-gray-700">Gula</label>
                    <input type="number" name="gula" id="gula" min="0" value="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('gula')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ayam -->
                <div>
                    <label for="ayam" class="block text-sm font-medium text-gray-700">Ayam</label>
                    <input type="number" name="ayam" id="ayam" min="0" value="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('ayam')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <a href="{{ route('dashboard') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
