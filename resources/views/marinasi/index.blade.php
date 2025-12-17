@extends('layouts.app')

@section('content')
@php
    function formatAngka($value) {
        $angka = (int) ($value ?? 0);
        return $angka < 1000 ? $angka : number_format($angka, 0, '.', ',');
    }

    $fields = [
        'daging_ayam'   => 'Daging Ayam',
        'saus_teriyaki' => 'Saus Teriyaki',
        'bawang_putih'  => 'Bawang Putih',
        'lada'          => 'Lada',
        'garam'         => 'Garam',
        'ketumbar'      => 'Ketumbar',
    ];
@endphp

<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">🍗 Marinasi</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- STOK TERKINI --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-3">Stok Saat Ini</h2>
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border text-left">Bahan</th>
                    <th class="p-2 border text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fields as $key => $label)
                <tr>
                    <td class="p-2 border">{{ $label }}</td>
                    <td class="p-2 border text-right font-semibold">
                        {{ formatAngka($marinasi->$key ?? 0) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- FORM --}}
    <form id="formMarinasi" action="{{ route('marinasi.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($fields as $key => $label)
            <div>
                <label class="block text-sm font-medium mb-1">{{ $label }}</label>
                <input
                    type="text"
                    name="{{ $key }}"
                    class="input-marinasi w-full border rounded p-2"
                    placeholder="Masukkan Jumlah Bahan"
                    autocomplete="off"
                >
                <p class="text-xs text-red-600 hidden error-text">
                    Field ini wajib diisi
                </p>
            </div>
            @endforeach
        </div>

        <div class="mt-6 hidden" id="btnSimpanWrapper">
            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
            >
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.input-marinasi');
    const btnWrapper = document.getElementById('btnSimpanWrapper');

    function isValidValue(val) {
        return val !== '' && val !== '-';
    }

    function validateForm() {
        let allFilled = true;

        inputs.forEach(input => {
            const error = input.nextElementSibling;
            if (!isValidValue(input.value.trim())) {
                allFilled = false;
                error.classList.remove('hidden');
            } else {
                error.classList.add('hidden');
            }
        });

        btnWrapper.classList.toggle('hidden', !allFilled);
        return allFilled;
    }

    inputs.forEach(input => {
        input.addEventListener('input', e => {
            let val = e.target.value;

            if (val === '-') {
                validateForm();
                return;
            }

            val = val.replace(/[^0-9\-]/g, '');

            if (val !== '') {
                const neg = val.startsWith('-');
                val = val.replace('-', '');
                val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                e.target.value = neg ? '-' + val : val;
            }

            validateForm();
        });
    });

    document.getElementById('formMarinasi').addEventListener('submit', e => {
        if (!validateForm()) {
            e.preventDefault();
            alert('Semua field wajib diisi.');
        }
    });
});
</script>
@endsection
