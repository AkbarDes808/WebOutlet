@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Menu Outlet</h1>
        <p class="text-gray-600 mt-2">Silakan pilih outlet untuk mengelola stok bahan.</p>
    </div>

    @if($outlets->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            {{-- Loop untuk setiap outlet yang ada --}}
            @foreach($outlets as $outlet)
                {{-- Setiap outlet adalah link ke halaman kalkulator bahan --}}
                <a href="{{ route('bahans.index', ['outlet' => $outlet]) }}" 
                   class="block p-4 sm:p-6 bg-white border rounded-lg text-center font-semibold text-gray-700 shadow-sm hover:bg-gray-100 hover:shadow-md transition-all duration-200">
                    
                    {{--  --}}
                    <svg class="w-12 h-12 mx-auto mb-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.25a.75.75 0 01-.75-.75v-7.5a.75.75 0 01.75-.75h3.75m-4.5 0v-7.5a.75.75 0 01.75-.75h3.75m0-3V3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V5.25m-3.75 0h3.75m-3.75 0a.75.75 0 01.75-.75h2.25a.75.75 0 01.75.75M3 13.5h18M3 7.5h18" />
                    </svg>

                    <span>{{ $outlet }}</span>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center p-10 bg-white border rounded-lg">
            <p class="text-gray-500">Belum ada data outlet yang tersedia.</p>
            <p class="text-sm text-gray-400 mt-2">Silakan tambahkan data bahan melalui kalkulator untuk membuat outlet baru.</p>
        </div>
    @endif

</div>
@endsection