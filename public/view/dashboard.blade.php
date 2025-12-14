@extends('layouts.app')

@section('content')
<div>
    <!-- Banner -->
    <div class="w-full h-48 md:h-64">
        <img src="https://images.unsplash.com/photo-1509390636458-9933f6da03b4?q=80&w=2070&auto=format&fit=crop" 
             alt="Solar panels being installed" 
             class="w-full h-full object-cover">
    </div>

    <div class="p-6 md:p-8">
        
        <!-- Welcome -->
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">
            Welcome back, {{ $user['name'] ?? 'User' }}!
        </h1>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300">
                <a href="{{ route('bahans.create') }}" class="flex flex-col items-start space-y-3">
                    <i class="bi bi-list-task text-2xl text-gray-500"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Inventory</h2>
                    <p class="text-sm text-gray-500">View all of your inventory</p>
                </a>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300">
                <a href="#" class="flex flex-col items-start space-y-3">
                    <i class="bi bi-bar-chart-line text-2xl text-gray-500"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Sales</h2>
                    <p class="text-sm text-gray-500">View all of your recent sales</p>
                </a>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300">
                <a href="#" class="flex flex-col items-start space-y-3">
                    <i class="bi bi-journal-text text-2xl text-gray-500"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Purchase Orders</h2>
                    <p class="text-sm text-gray-500">View your recent purchase oders</p>
                </a>
            </div>
            
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300">
                <a href="#" class="flex flex-col items-start space-y-3">
                    <i class="bi bi-building text-2xl text-gray-500"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Suppliers</h2>
                    <p class="text-sm text-gray-500">Vendors we order from</p>
                </a>
            </div>

        </div>

        <!-- Inventory Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Inventory (Bahans)</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Nama Outlet</th>
                            <th class="px-6 py-3">Tepung Bumbu</th>
                            <th class="px-6 py-3">Garam</th>
                            <th class="px-6 py-3">Bubuk Cabe</th>
                            <th class="px-6 py-3">Telur</th>
                            <th class="px-6 py-3">Gula</th>
                            <th class="px-6 py-3">Ayam</th>
                            <th class="px-6 py-3">Created At</th>
                            <th class="px-6 py-3">Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahans as $bahan)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $bahan->id }}</td>
                            <td class="px-6 py-3">{{ $bahan->nama_outlet }}</td>
                            <td class="px-6 py-3">{{ $bahan->tepung_bumbu }}</td>
                            <td class="px-6 py-3">{{ $bahan->garam }}</td>
                            <td class="px-6 py-3">{{ $bahan->bubuk_cabe }}</td>
                            <td class="px-6 py-3">{{ $bahan->telur }}</td>
                            <td class="px-6 py-3">{{ $bahan->gula }}</td>
                            <td class="px-6 py-3">{{ $bahan->ayam }}</td>
                            <td class="px-6 py-3 text-xs text-gray-500">{{ $bahan->created_at }}</td>
                            <td class="px-6 py-3 text-xs text-gray-500">{{ $bahan->updated_at }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
