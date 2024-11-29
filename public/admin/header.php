<?php
// header.php

echo <<<HTML
<header class="bg-gray-800 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-3xl font-semibold"><a href="index.php">后台管理</a></div>

        <div class="lg:hidden flex items-center">
            <button id="hamburger-icon" class="text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="nav-links" class="hidden lg:flex space-x-6">
            <a href="versions.php" class="text-white text-lg hover:text-gray-400 transition duration-200">版本管理</a>
            <a href="settings.php" class="text-white text-lg hover:text-gray-400 transition duration-200">站点设置</a>
            <a href="announcements.php" class="text-white text-lg hover:text-gray-400 transition duration-200">公告管理</a>
        </div>
    </div>

    <div id="hamburger-menu" class="lg:hidden absolute left-0 right-0 top-16 bg-gray-800 text-white p-4 hidden transition-all duration-300 transform -translate-y-8 opacity-0">
        <a href="versions.php" class="block py-2 hover:text-gray-400 transition duration-200">版本管理</a>
        <a href="settings.php" class="block py-2 hover:text-gray-400 transition duration-200">站点设置</a>
        <a href="announcements.php" class="block py-2 hover:text-gray-400 transition duration-200">公告管理</a>
    </div>
</header>

<script>
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const hamburgerMenu = document.getElementById('hamburger-menu');
    hamburgerIcon.addEventListener('click', () => {
        hamburgerMenu.classList.toggle('hidden');
        if (hamburgerMenu.classList.contains('hidden')) {
            hamburgerMenu.classList.add('-translate-y-8', 'opacity-0');
            hamburgerMenu.classList.remove('translate-y-0', 'opacity-100');
        } else {
            hamburgerMenu.classList.add('translate-y-0', 'opacity-100');
            hamburgerMenu.classList.remove('-translate-y-8', 'opacity-0');
        }
    });
</script>
HTML;
?>
