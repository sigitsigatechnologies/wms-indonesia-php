<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WMS Indonesia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        @media (max-width: 1023px) {
            .sidebar { 
                transform: translateX(-100%); 
            }
            .sidebar.active { 
                transform: translateX(0); 
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Header -->
    <header class="lg:hidden bg-white shadow-md fixed top-0 left-0 right-0 z-40 h-16">
        <div class="flex items-center justify-between px-4 h-full">
            <button id="mobile-menu-btn" class="text-gray-700 hover:text-indigo-600 focus:outline-none p-2">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-lg font-bold text-indigo-600">
                <i class="fas fa-warehouse mr-2"></i>WMS
            </h1>
            <div class="w-10"></div>
        </div>
    </header>

    <div class="flex min-h-screen pt-16 lg:pt-0">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed lg:static inset-y-0 left-0 z-30 lg:z-0 w-64 bg-white shadow-lg h-full transition-transform duration-300 ease-in-out">
            <div class="p-4 lg:p-6 border-b">
                <h1 class="text-xl font-bold text-indigo-600 hidden lg:block">
                    <i class="fas fa-warehouse mr-2"></i>WMS
                </h1>
                <h1 class="text-xl font-bold text-indigo-600 lg:hidden">
                    <i class="fas fa-warehouse mr-2"></i>WMS Indonesia
                </h1>
            </div>
            <nav class="mt-2 lg:mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 lg:px-6 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <i class="fas fa-home w-6"></i>
                    <span class="ml-3 text-sm">Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 lg:px-6 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <i class="fas fa-box w-6"></i>
                    <span class="ml-3 text-sm">Produk</span>
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="flex items-center px-4 lg:px-6 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.suppliers.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <i class="fas fa-truck w-6"></i>
                    <span class="ml-3 text-sm">Supplier</span>
                </a>
                <a href="{{ route('admin.purchases.index') }}" class="flex items-center px-4 lg:px-6 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.purchases.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span class="ml-3 text-sm">Pembelian</span>
                </a>
                <a href="{{ route('admin.sales.index') }}" class="flex items-center px-4 lg:px-6 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.sales.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <i class="fas fa-cash-register w-6"></i>
                    <span class="ml-3 text-sm">Penjualan</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 lg:px-6 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : '' }}">
                    <i class="fas fa-tags w-6"></i>
                    <span class="ml-3 text-sm">Kategori</span>
                </a>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8 w-full">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const menuBtn = document.getElementById('mobile-menu-btn');
        
        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.add('hidden');
        }
        
        // Reset saat halaman load - pastikan sidebar tertutup di mobile
        window.addEventListener('load', () => {
            closeSidebar();
        });
        
        // Override link clicks untuk memastikan sidebar tertutup
        document.addEventListener('click', function(e) {
            const link = e.target.closest('nav a');
            if (link) {
                closeSidebar();
            }
        });
        
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
        });
        
        overlay.addEventListener('click', closeSidebar);
    </script>

    @stack('scripts')
</body>
</html>
