<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>Wish Chicken</title>

    <link rel="icon" type="image/png"
          href="https://raw.githubusercontent.com/AkbarDes808/Diagram/main/wishlogo.png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    html{
        background:#F5F6F8;
    }

    body{
        padding-top:env(safe-area-inset-top);
    }

    @supports (padding-top: constant(safe-area-inset-top)){
        body{
            padding-top:constant(safe-area-inset-top);
        }
    }
    </style>
</head>
<script>

function confirmLogout() {

    Swal.fire({
        title: 'Keluar Akun?',
        html: `
            <div class="text-sm text-gray-600">
                Shift Anda masih berjalan.<br>
                Pilih tindakan yang ingin dilakukan.
            </div>
        `,
        icon: 'question',

        showConfirmButton: false, // <-- tambahkan ini
        showCancelButton: true,
        showDenyButton: true,

        denyButtonText: 'Tutup Shift',
        cancelButtonText: 'Batal',

        denyButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280'

    }).then((result) => {

        if (result.isDenied) {
            window.location.href = "{{ route('shift.index') }}";
        }

    });

}

</script>
<body class="bg-gray-100 min-h-screen flex overflow-x-hidden">

<!-- OVERLAY -->
<div id="overlay" class="fixed inset-0 bg-black/40 hidden z-40 lg:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
class="fixed left-0 top-0 bottom-0 w-64 bg-[#F5F6F8] border-r flex flex-col
transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50
pt-[env(safe-area-inset-top)]">

    <!-- LOGO -->
    <div class="flex items-center gap-3 px-5 h-24 border-b shrink-0">
        <div class="w-9 h-9 bg-yellow-400 rounded-lg flex items-center justify-center text-xl">
            🍗
        </div>
        <span class="font-semibold text-gray-800 text-lg">Wish Chicken</span>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-2 py-4 space-y-1 text-sm overflow-y-auto">

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg relative
        {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-200' }}">

            @if(request()->routeIs('dashboard'))
            <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
            @endif

            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        {{-- KASIR (SEMUA ROLE) --}}
        <a href="{{ route('kasir.index') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg relative
        {{ request()->routeIs('kasir.index') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-200' }}">

            @if(request()->routeIs('kasir.index'))
            <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
            @endif

            <i class="fa-solid fa-sack-dollar"></i>
            Kasir
        </a>

        {{-- INVENTORY ADMIN + SPV --}}
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'SPV')

            <a href="{{ route('bahans.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg relative
            {{ request()->routeIs('bahans.index') || request()->routeIs('bahans.create')
                ? 'bg-blue-50 text-blue-600 font-semibold'
                : 'text-gray-600 hover:bg-gray-200' }}">

                @if(request()->routeIs('bahans.index') || request()->routeIs('bahans.create'))
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
                @endif

                <i class="fa-solid fa-box"></i>
                Inventory
            </a>

        @endif

        {{-- MARINASI KHUSUS ADMIN --}}
        @if(Auth::user()->role === 'admin')

            <a href="{{ route('marinasi.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg relative
            {{ request()->routeIs('marinasi.*')
                ? 'bg-blue-50 text-blue-600 font-semibold'
                : 'text-gray-600 hover:bg-gray-200' }}">

                @if(request()->routeIs('marinasi.*'))
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
                @endif

                <i class="fa-solid fa-drumstick-bite"></i>
                Marinasi
            </a>

        @endif

        {{-- HISTORY ADMIN + SPV --}}
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'SPV')

            <a href="{{ route('bahans.history') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg relative
            {{ request()->routeIs('bahans.history')
                ? 'bg-blue-50 text-blue-600 font-semibold'
                : 'text-gray-600 hover:bg-gray-200' }}">

                @if(request()->routeIs('bahans.history'))
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
                @endif

                <i class="fa-solid fa-clock-rotate-left"></i>
                History
            </a>

        @endif

        {{-- RIWAYAT PESANAN SEMUA ROLE --}}
        <a href="{{ route('kasir.history') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg relative
        {{ request()->routeIs('kasir.history')
            ? 'bg-blue-50 text-blue-600 font-semibold'
            : 'text-gray-600 hover:bg-gray-200' }}">

            @if(request()->routeIs('kasir.history'))
            <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
            @endif

            <i class="fa-solid fa-receipt"></i>
            Riwayat Pesanan
        </a>

        {{-- RIWAYAT SHIFT SEMUA ROLE --}}
        <a href="{{ route('shift.history') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg relative
        {{ request()->routeIs('shift.history')
            ? 'bg-blue-50 text-blue-600 font-semibold'
            : 'text-gray-600 hover:bg-gray-200' }}">

            @if(request()->routeIs('shift.history'))
            <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
            @endif

            <i class="fa-solid fa-cash-register"></i>
            Riwayat Shift
        </a>

        {{-- OUTLETS ADMIN + SPV --}}
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'SPV')

            <a href="{{ route('outlets.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg relative
            {{ request()->routeIs('outlets.*')
                ? 'bg-blue-50 text-blue-600 font-semibold'
                : 'text-gray-600 hover:bg-gray-200' }}">

                @if(request()->routeIs('outlets.*'))
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-600 rounded-r"></span>
                @endif

                <i class="fa-solid fa-building"></i>
                Outlets
            </a>

        @endif
        <!-- PRINTER -->
        <button
            type="button"
            onclick="testBluetoothPrinter()"
            class="w-full flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-200 rounded-lg">

            <i class="fa-solid fa-print"></i>
            Test Printer
        </button>

        <hr class="my-2">
        {{-- USERS ADMIN --}}
        <hr class="my-2">

        {{-- LOGOUT --}}
        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
            @php
                $shiftClosed = auth()->check()
                    ? \App\Models\ShiftClosing::where('user_id', auth()->id())
                        ->whereDate('tanggal', now()->toDateString())
                        ->exists()
                    : false;
            @endphp
            @csrf   
            <button
                type="button"
                onclick="{{ $shiftClosed ? 'document.getElementById(\'logoutForm\').submit()' : 'confirmLogout()' }}"
                class="w-full flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-200 rounded-lg">

                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </nav>

    <!-- USER -->
    <div class="px-5 py-4 border-t">
        <p class="text-sm font-semibold text-gray-800">
            {{ Auth::user()->name }}
        </p>
        <p class="text-xs text-gray-500">
            {{ Auth::user()->email }}
        </p>
    </div>

</aside>

<!-- MAIN -->
<div class="flex-1 flex flex-col min-w-0 w-full lg:ml-64 overflow-hidden">

    <!-- HEADER MOBILE -->
    <header
        class="lg:hidden sticky top-0 z-30 flex justify-between items-center bg-white shadow px-4 pt-[max(env(safe-area-inset-top),1rem)] pb-4 border-b">
        <button id="openSidebar">☰</button>
        <h1 class="font-bold text-lg">Wish Chicken</h1>
    </header>

    <!-- CONTENT -->
    <main class="flex-1 min-w-0 overflow-x-hidden p-4 sm:p-6">
        @yield('content')
    </main>

</div>

<!-- SCRIPT SIDEBAR -->
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const openBtn = document.getElementById('openSidebar');

function openSidebar() {
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
}

function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
}

openBtn?.addEventListener('click', openSidebar);
overlay?.addEventListener('click', closeSidebar);
</script>

<!-- SESSION HANDLER -->
<script>
let lastActive = Date.now();

async function checkSession() {
    try {
        const res = await fetch("/check-session", {
            method: "GET",
            credentials: "include"
        });

        if (res.status === 401) {
            alert("Session habis, silakan login ulang.");
            window.location.href = "/login";
        }

    } catch (e) {
        console.error("Session check failed", e);
    }
}


document.addEventListener("visibilitychange", () => {

    if (document.visibilityState === "visible") {

        let now = Date.now();

        let diff = (now - lastActive) / 1000;


        if (diff > 900) {

            location.reload();

        } else {

            checkSession();

        }

    } else {

        lastActive = Date.now();

    }

});
</script>


@if(session('shift_closed'))

<script>

Swal.fire({
    icon: 'warning',
    title: 'Shift Selesai',
    text: "{{ session('shift_closed') }}",

    showConfirmButton: false,
    showCancelButton: false,
    showDenyButton: false,

    allowOutsideClick: true,
    allowEscapeKey: true,
});

</script>

@endif
<script>
function testBluetoothPrinter() {

    // Cek apakah sedang berjalan di aplikasi Android
    if (
        typeof AndroidPrinter === 'undefined' ||
        typeof AndroidPrinter.testPrinter !== 'function'
    ) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak tersedia',
            text: 'Fitur printer Bluetooth hanya tersedia di aplikasi Android.'
        });

        return;
    }

    AndroidPrinter.testPrinter();
}
</script>

</body>
</html>