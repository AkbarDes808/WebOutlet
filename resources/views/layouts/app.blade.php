<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/your_kit_code.js" crossorigin="anonymous"></script> {{-- Jangan lupa ganti dengan kode Font Awesome Anda --}}

<title>Wish Chicken</title>

<link rel="icon" type="image/png"
      href="https://raw.githubusercontent.com/AkbarDes808/Diagram/main/wishlogo.png">

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="h-screen flex bg-gray-50">

    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-md flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50">
        
        <div class="p-6 flex items-center justify-between text-xl font-semibold border-b">
            <span class="flex items-center space-x-2">
                <img
                    src="https://raw.githubusercontent.com/AkbarDes808/Diagram/main/wishlogo.png"
                    alt="Wish Chicken Logo"
                    class="h-8 w-8 object-contain"
                >
                <span>Wish Chicken</span>
            </span>

            <button id="closeSidebar" class="lg:hidden text-gray-600 hover:text-black">✖</button>
        </div>
        <nav class="flex-1 overflow-y-auto">
            <ul class="space-y-1 p-2">
                {{-- Tombol Dashboard --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        {{-- ICON: Home --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                {{-- Tombol Inventory --}}
                <li>
                    <a href="{{ route('bahans.index') }}" class="flex items-center px-6 py-3 rounded {{ request()->routeIs('bahans.index') || request()->routeIs('bahans.create') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        {{-- ICON: Clipboard/Daftar --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Inventory
                    </a>
                </li>
                {{-- Tombol History --}}
                <li>
                    <a href="{{ route('bahans.history') }}" class="flex items-center px-6 py-3 rounded {{ request()->routeIs('bahans.history') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        {{-- ICON: Jam --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        History
                    </a>
                </li>
                {{-- Tombol Outlets --}}
@if(Auth::user()->role !== 'outlet')
    <li>
        <a href="{{ route('outlets.index') }}" class="flex items-center px-6 py-3 rounded {{ request()->routeIs('outlets.index') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
            {{-- ICON: Toko --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Outlets
        </a>
    </li>
@endif
<li>
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <a href="{{ route('logout') }}"
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 rounded"
           onclick="event.preventDefault(); this.closest('form').submit();">

            {{-- ICON: Keluar/Logout --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>

            {{ __('Keluar') }}
        </a>
    </form>
</li>
            </ul>
        </nav>

    <div class="p-6 border-t flex items-center space-x-3">
        <img
            src="https://raw.githubusercontent.com/AkbarDes808/Diagram/main/wishlogo.png"
            alt="User Avatar"
            class="w-10 h-10 rounded-full object-contain bg-white"
        >
        <div>
            <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>
    </div>

    </aside>

    <main class="flex-1 flex flex-col w-full lg:ml-64">
        <header class="lg:hidden flex items-center justify-between p-4 bg-white shadow">
            <button id="openSidebar" class="text-gray-600 hover:text-black">☰</button>
            <div class="flex items-center space-x-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h1>Wish Chicken</h1>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            @yield('content')
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function closeSidebarFunc() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebarFunc);
        overlay?.addEventListener('click', closeSidebarFunc);
    </script>
</body>
</html>