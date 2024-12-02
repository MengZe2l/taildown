<?php
echo <<<HTML
<header class="bg-white text-gray-800 p-3 shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-2xl font-bold">
            <a href="index.php" class="hover:text-gray-500 transition duration-200">后台管理</a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden flex items-center">
            <button id="hamburger-icon" class="text-gray-800 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Desktop Menu -->
        <nav id="nav-links" class="hidden lg:flex space-x-6">
            <a href="versions.php" class="text-gray-800 text-lg font-medium hover:text-gray-500 transition duration-200">版本管理</a>
            <a href="settings.php" class="text-gray-800 text-lg font-medium hover:text-gray-500 transition duration-200">站点设置</a>
            <a href="announcements.php" class="text-gray-800 text-lg font-medium hover:text-gray-500 transition duration-200">公告管理</a>
        </nav>
    </div>
</header>

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 h-full w-48 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-40">
    <div class="p-3 bg-gray-100 shadow-md flex justify-between items-center">
        <div class="text-xl font-bold">菜单</div>
        <button id="close-sidebar" class="text-gray-800 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-2">
        <a href="versions.php" class="block py-2 px-4 text-lg font-medium hover:bg-gray-100 transition duration-200">版本管理</a>
        <a href="settings.php" class="block py-2 px-4 text-lg font-medium hover:bg-gray-100 transition duration-200">站点设置</a>
        <a href="announcements.php" class="block py-2 px-4 text-lg font-medium hover:bg-gray-100 transition duration-200">公告管理</a>
    </nav>
</div>

<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30"></div>

<script>
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const closeSidebar = document.getElementById('close-sidebar');

    const toggleSidebar = () => {
        const isSidebarOpen = !sidebar.classList.contains('-translate-x-full');
        if (isSidebarOpen) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        } else {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
    };
    hamburgerIcon.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
    closeSidebar.addEventListener('click', toggleSidebar);
</script>
HTML;

