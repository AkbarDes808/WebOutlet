@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h1 class="text-2xl font-bold text-gray-800">Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi akun dan keamanan Anda.</p>

        <div class="mt-6 space-y-6">
            <div class="border rounded-lg p-4">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="border rounded-lg p-4">
                @include('profile.partials.update-password-form')
            </div>
            <div class="border rounded-lg p-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
