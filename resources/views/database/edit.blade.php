@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto p-4 sm:p-6"><div class="bg-white border rounded-xl p-6"><h1 class="text-2xl font-bold mb-5">Edit Data: {{ $table }}</h1>
<form method="POST" action="{{ route('database.update', [$table, $row->{$primaryKey}]) }}" class="space-y-4">@csrf @method('PUT')
@foreach($columns as $column)<div><label class="block text-sm font-medium mb-1">{{ $column }}</label><input name="{{ $column }}" value="{{ $row->{$column} ?? '' }}" class="w-full border rounded-lg px-3 py-2" @disabled($column === $primaryKey)></div>@endforeach
<button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan Perubahan</button></form></div></div>
@endsection