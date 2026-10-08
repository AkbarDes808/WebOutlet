@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6">
    <div class="flex items-center justify-between mb-5">
        <div><h1 class="text-2xl font-bold text-gray-800">Tabel: {{ $table }}</h1><p class="text-sm text-gray-500">{{ $count }} data</p></div>
        <a href="{{ route('database.create', $table) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Tambah Data</a>
    </div>
    @if(session('success'))<div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">{{ session('success') }}</div>@endif
    <div class="overflow-x-auto bg-white border rounded-xl">
        <table class="min-w-full text-sm"><thead class="bg-gray-100"><tr>@foreach($columns as $column)<th class="px-3 py-2 text-left">{{ $column }}</th>@endforeach<th class="px-3 py-2">Aksi</th></tr></thead>
        <tbody class="divide-y">@forelse($rows as $row)<tr>@foreach($columns as $column)<td class="px-3 py-2">{{ is_scalar($row->{$column} ?? null) ? $row->{$column} : json_encode($row->{$column} ?? null) }}</td>@endforeach
        <td class="px-3 py-2"><a class="text-blue-600" href="{{ route('database.edit', [$table, $row->{$columns[0]}]) }}">Edit</a></td></tr>@empty<tr><td colspan="{{ count($columns)+1 }}" class="p-6 text-center text-gray-500">Tidak ada data.</td></tr>@endforelse</tbody></table>
    </div>
</div>
@endsection