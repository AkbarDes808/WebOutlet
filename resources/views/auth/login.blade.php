<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wish Chicken</title>
    <link rel="icon" type="image/png"
        href="https://raw.githubusercontent.com/AkbarDes808/Diagram/main/wishlogo.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        
        <div class="flex flex-col items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="bg-white p-8 md:p-10 rounded-xl shadow-lg">
                    
<div class="text-center mb-8">
    <img
        src="https://raw.githubusercontent.com/AkbarDes808/Diagram/main/wishlogo.png"
        alt="WISH Logo"
        class="h-16 mx-auto mb-4"
    >

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
                            <div class="mt-1 relative">

                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter your password"
                                >

                                <button
                                    type="button"
                                    id="toggle-password"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    aria-label="Tampilkan password"
                                >

                                    <!-- ICON MATA -->
                                    <svg
                                        id="eye-open"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>

                                    <!-- ICON MATA TERTUTUP -->
                                    <svg
                                        id="eye-closed"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 hidden"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.154-3.47"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.228 6.228A9.95 9.95 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.96 9.96 0 01-4.132 5.411"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.228 6.228L3 3m3.228 3.228l3.07 3.07m0 0a3 3 0 104.243 4.243M9.298 9.298l4.243 4.243m0 0L21 21"
                                        />
                                    </svg>

                                </button>

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
    <script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    togglePassword.addEventListener('click', function () {

        if (passwordInput.type === 'password') {

            // Tampilkan password
            passwordInput.type = 'text';

            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');

            togglePassword.setAttribute(
                'aria-label',
                'Sembunyikan password'
            );

        } else {

            // Sembunyikan password
            passwordInput.type = 'password';

            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');

            togglePassword.setAttribute(
                'aria-label',
                'Tampilkan password'
            );
        }

    });
</script>
</body>
</html>