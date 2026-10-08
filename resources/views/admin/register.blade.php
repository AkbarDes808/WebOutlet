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
                <option value="outlet 1">Outlet 1</option>
                <option value="outlet 2">Outlet 2</option>
                <option value="outlet 3">Outlet 3</option>
                <option value="outlet 4">Outlet 4</option>
                <option value="outlet 5">Outlet 5</option>
                <option value="outlet 6">Outlet 6</option>
                <option value="outlet 7">Outlet 7</option>
                <option value="outlet 8">Outlet 8</option>
                <option value="outlet 9">Outlet 9</option>
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
