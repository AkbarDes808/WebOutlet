@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto p-4 sm:p-6"><div class="bg-white border rounded-xl p-6"><h1 class="text-2xl font-bold mb-5">Tambah Data: {{ $table }}</h1>
<form method="POST" action="{{ route('database.store', $table) }}" class="space-y-4">@csrf
@foreach($columns as $column)<div><label class="block text-sm font-medium mb-1">{{ $column }}</label><input name="{{ $column }}" class="w-full border rounded-lg px-3 py-2" @if($column === 'id') placeholder="Otomatis jika tersedia" @endif></div>@endforeach
<button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button></form></div></div>
@endsection