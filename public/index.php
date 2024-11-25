<?php
require '../config.php';
require '../src/database.php';
require '../src/functions.php';
require '../src/helpers.php'; // 确保引入了辅助函数

$config = require '../config.php';
$db = new Database($config);

// 获取数据
$versions = getVersions($db) ?? [];
$announcements = getLatestAnnouncement($db) ?? [];
$settings = getSettings($db) ?? [];
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($settings['title'] ?? '版本下载站'); ?></title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.bootcdn.net/ajax/libs/alpinejs/3.13.1/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-600 text-white shadow">
        <div class="container mx-auto py-6 px-8">
            <h1 class="text-3xl font-semibold"><?php echo sanitize($settings['title'] ?? '版本下载站'); ?></h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Announcements -->
            <section class="bg-white p-6 rounded-lg shadow mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">公告</h2>
                <p class="text-gray-600">
                    <?php echo $announcements ? sanitize($announcements[0]['content']) : '暂无公告'; ?>
                </p>
            </section>

            <!-- Versions -->
            <section class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">版本列表</h2>
                <?php if (count($versions) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($versions as $version): ?>
                        <div class="border border-gray-200 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <span class="bg-indigo-500 text-white rounded-full h-8 w-8 flex items-center justify-center mr-3">
                                    <i class="fas fa-cogs"></i>
                                </span>
                                <?php echo sanitize($version['version']); ?>
                            </h3>
                            <p class="text-gray-700 mt-2 line-clamp-3">
                                <?php echo sanitize($version['changelog']); ?>
                            </p>
                            <div class="mt-4">
                                <a href="<?php echo sanitize($version['file_url']); ?>" class="inline-block bg-indigo-500 text-white hover:bg-indigo-600 rounded-lg py-2 px-5 transition-all">
                                    <i class="fas fa-download mr-2"></i> 下载
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-gray-500">当前没有可用的版本。</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-600 text-white text-center py-4">
        <p>&copy; <?php echo date('Y'); ?> 版本下载站. 版权所有.</p>
    </footer>

</body>
</html>