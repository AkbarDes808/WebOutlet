<x-guest-layout>
    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf

        <h2 class="text-2xl font-bold mb-6 text-center">
            Register User (Admin Only)
        </h2>

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" class="block mt-1 w-full"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" value="Role" />
            <select name="role" id="role"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="outlet">Outlet</option>
                <option value="SPV">SPV</option>
                <option value="admin">Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                Create User
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
