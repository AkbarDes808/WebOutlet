<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wish Chicken Dashboard</title>

    {{-- Vite untuk Tailwind CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Link untuk Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Style untuk font (Inter) agar sesuai gambar --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen bg-white">
        <aside id="sidebar" class="w-64 -translate-x-full fixed inset-y-0 left-0 z-30 bg-[#F8F9FA] p-4 flex flex-col transition-transform duration-300 ease-in-out md:translate-x-0 md:relative md:inset-0">
            
            <div class="flex items-center justify-between mb-8">
                <a href="#" class="flex items-center space-x-2 text-xl font-bold text-gray-800">
                    <i class="bi bi-brightness-high-fill text-yellow-500 text-2xl"></i>
                    <span>Wish Chicken</span>
                </a>
                <i class="bi bi-view-list text-gray-500 text-2xl"></i>
            </div>

            <nav class="flex-grow">
                <ul>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-2.5 text-gray-700 bg-gray-200 rounded-lg">
                            <i class="bi bi-grid-1x2-fill w-6 text-base"></i>
                            <span class="ml-2 font-semibold text-sm">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-2.5 text-gray-600 hover:bg-gray-200 rounded-lg">
                            <i class="bi bi-box-seam w-6 text-base"></i>
                            <span class="ml-2 text-sm">Inventory</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-2.5 text-gray-600 hover:bg-gray-200 rounded-lg">
                            <i class="bi bi-cart-check w-6 text-base"></i>
                            <span class="ml-2 text-sm">Sales orders</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-2.5 text-gray-600 hover:bg-gray-200 rounded-lg">
                            <i class="bi bi-receipt w-6 text-base"></i>
                            <span class="ml-2 text-sm">Purchase orders</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center p-2.5 text-gray-600 hover:bg-gray-200 rounded-lg">
                            <i class="bi bi-truck w-6 text-base"></i>
                            <span class="ml-2 text-sm">Suppliers</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="mt-auto">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $user['avatar'] ?? 'https://i.pravatar.cc/40' }}" alt="User Avatar" class="w-9 h-9 rounded-full">
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $user['fullName'] ?? 'User Name' }}</p>
                            <p class="text-xs text-gray-500">{{ $user['email'] ?? 'user@example.com' }}</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-down text-gray-500 text-xs"></i>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between p-4 bg-white border-b md:hidden">
                <div class="flex items-center space-x-2 text-xl font-bold text-gray-800">
                    <i class="bi bi-brightness-high-fill text-yellow-500"></i>
                    <span>Wish Chicken</span>
                </div>
                <button id="menu-button" class="text-gray-700 text-2xl focus:outline-none">
                    <i class="bi bi-list"></i>
                </button>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.getElementById('menu-button');

        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
        
        // Klik di luar sidebar untuk menutupnya di mobile (opsional)
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
                if(!sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
    </script>
</body>
</html>