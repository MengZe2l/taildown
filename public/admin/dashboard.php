<?php
require '../../config.php';
require '../../src/database.php';
require '../../src/auth.php';
require '../../src/helpers.php';

$config = require '../../config.php';
$db = new Database($config);
$auth = new Auth($db);

if (!$auth->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

// 获取设置数据
$settings = $db->query('SELECT * FROM settings LIMIT 1')->fetch_assoc();

// 获取最新版本
$latestVersion = $db->query('SELECT * FROM versions ORDER BY created_at DESC LIMIT 1')->fetch_assoc();

// 获取最新公告
$latestAnnouncement = $db->query('SELECT * FROM announcements ORDER BY created_at DESC LIMIT 1')->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 仪表盘</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50">

    <!-- 汉堡菜单 -->
    <header class="bg-gray-800 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-3xl font-semibold">后台管理</div>
            <!-- 汉堡菜单按钮 -->
            <div class="lg:hidden flex items-center">
                <button id="hamburger-icon" class="text-white">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <!-- 侧边导航 -->
            <div id="nav-links" class="lg:flex hidden space-x-6">
                <a href="versions.php" class="text-white hover:text-gray-400">版本管理</a>
                <a href="settings.php" class="text-white hover:text-gray-400">站点设置</a>
                <a href="announcements.php" class="text-white hover:text-gray-400">公告管理</a>
            </div>
        </div>
        <!-- 汉堡菜单下拉 -->
        <div id="hamburger-menu" class="lg:hidden absolute left-0 right-0 top-16 bg-gray-800 text-white p-4 hidden">
            <a href="versions.php" class="block py-2">版本管理</a>
            <a href="settings.php" class="block py-2">站点设置</a>
            <a href="announcements.php" class="block py-2">公告管理</a>
        </div>
    </header>

    <!-- 主要内容区域 -->
    <main class="container mx-auto p-8 mt-6 bg-white shadow-lg rounded-lg">
        <!-- 最新版本 -->
        <section class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-800">最新版本</h2>
            <?php if ($latestVersion): ?>
                <div class="bg-gray-100 p-6 rounded-lg shadow-sm space-y-4">
                    <p><strong>版本号：</strong> <?php echo htmlspecialchars($latestVersion['version']); ?></p>
                    <p><strong>描述：</strong> <?php echo htmlspecialchars($latestVersion['changelog']); ?></p>
                    <p><strong>发布日期：</strong> <?php echo htmlspecialchars($latestVersion['created_at']); ?></p>
                </div>
            <?php else: ?>
                <p class="text-gray-500">暂无版本信息。</p>
            <?php endif; ?>
        </section>

        <!-- 最新公告 -->
        <section class="space-y-6 mt-12">
            <h2 class="text-2xl font-semibold text-gray-800">最新公告</h2>
            <?php if ($latestAnnouncement): ?>
                <div class="bg-gray-100 p-6 rounded-lg shadow-sm space-y-4">
                    <p><strong>公告内容：</strong> <?php echo nl2br(htmlspecialchars($latestAnnouncement['content'])); ?></p>
                    <p><strong>发布日期：</strong> <?php echo htmlspecialchars($latestAnnouncement['created_at']); ?></p>
                </div>
            <?php else: ?>
                <p class="text-gray-500">暂无公告信息。</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- 页脚 -->
    <footer class="text-center text-gray-500 text-sm mt-12 py-4">
        <p>&copy; <?php echo date('Y'); ?> 版本管理系统</p>
    </footer>

    <!-- JavaScript：控制汉堡菜单的显示与隐藏 -->
    <script>
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const navLinks = document.getElementById('nav-links');

        hamburgerIcon.addEventListener('click', () => {
            hamburgerMenu.classList.toggle('hidden');
        });

        // 关闭菜单点击链接时
        document.querySelectorAll('#hamburger-menu a').forEach(link => {
            link.addEventListener('click', () => {
                hamburgerMenu.classList.add('hidden');
            });
        });
    </script>

</body>
</html>
