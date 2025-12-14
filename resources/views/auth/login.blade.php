<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        
        <div class="flex flex-col items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="bg-white p-8 md:p-10 rounded-xl shadow-lg">
                    
                    <div class="text-center mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h1 class="text-3xl font-bold text-gray-800">Welcome back!</h1>
                        <p class="text-gray-500 mt-2">Log in to continue</p>
                    </div>

                    {{-- Menampilkan error validasi umum --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    {{-- Form ini sudah disesuaikan untuk Breeze --}}
                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="you@example.com">
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Enter your password">
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-md font-semibold text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition duration-150">
                                Log In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<div class="hidden lg:block relative">
    <img class="absolute inset-0 w-full h-full object-cover" 
         src="https://picsum.photos/1920/1080"
         alt="Wish Chicken Banner">
    <div class="absolute inset-0 bg-black opacity-20"></div>
</div>

    </div>
</body>
</html>