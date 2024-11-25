<?php
// header.php

echo <<<HTML
<header class="bg-gray-800 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-3xl font-semibold">后台管理</div>

        <!-- 汉堡菜单按钮（仅在小屏幕显示） -->
        <div class="lg:hidden flex items-center">
            <button id="hamburger-icon" class="text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- 侧边导航（PC端显示）-->
        <div id="nav-links" class="hidden lg:flex space-x-6">
            <a href="versions.php" class="text-white text-lg hover:text-gray-400 transition duration-200">版本管理</a>
            <a href="settings.php" class="text-white text-lg hover:text-gray-400 transition duration-200">站点设置</a>
            <a href="announcements.php" class="text-white text-lg hover:text-gray-400 transition duration-200">公告管理</a>
        </div>
    </div>

    <!-- 汉堡菜单下拉（仅在小屏幕显示）-->
    <div id="hamburger-menu" class="lg:hidden absolute left-0 right-0 top-16 bg-gray-800 text-white p-4 hidden transition-all duration-300 transform -translate-y-8 opacity-0">
        <a href="versions.php" class="block py-2 hover:text-gray-400 transition duration-200">版本管理</a>
        <a href="settings.php" class="block py-2 hover:text-gray-400 transition duration-200">站点设置</a>
        <a href="announcements.php" class="block py-2 hover:text-gray-400 transition duration-200">公告管理</a>
    </div>
</header>

<script>
    // 获取汉堡菜单按钮和下拉菜单
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const hamburgerMenu = document.getElementById('hamburger-menu');

    // 切换汉堡菜单显示状态
    hamburgerIcon.addEventListener('click', () => {
        hamburgerMenu.classList.toggle('hidden');
        
        // 通过添加和移除类来控制展开/收起动画
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
