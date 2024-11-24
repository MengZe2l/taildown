<?php
require '../config.php';
require '../src/database.php';
require '../src/functions.php';
require '../src/helpers.php'; // 确保引入了辅助函数

$config = require '../config.php';
$db = new Database($config);

// 获取数据
$versions = getVersions($db);
$announcements = getLatestAnnouncement($db);
$settings = getSettings($db);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($settings['title'] ?? '版本下载站'); ?></title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- 引入 FontAwesome 图标库 (使用 BootCDN) -->
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Header Section -->
    <header class="bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-600 text-white shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-medium"><?php echo sanitize($settings['title'] ?? '版本下载站'); ?></h1>
        </div>
    </header>

    <!-- Main Content Section -->
    <main class="py-8 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Announcements Section -->
            <section class="bg-white p-6 rounded-lg shadow-sm mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">公告</h2>
                <p class="text-gray-600"><?php echo sanitize($announcements[0]['content'] ?? '暂无公告'); ?></p>
            </section>

            <!-- Versions Section -->
            <section class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">版本列表</h2>
                <div class="space-y-6">
                    <?php foreach ($versions as $version): ?>
                        <div class="border border-gray-200 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i class="fas fa-cogs text-indigo-600 mr-3"></i>
                                <?php echo sanitize($version['version']); ?>
                            </h3>
                            <p class="text-gray-700 mt-4"><?php echo sanitize($version['changelog']); ?></p>
                            <div class="mt-4">
                                <a href="<?php echo sanitize($version['file_url']); ?>" class="inline-block bg-indigo-500 text-white hover:bg-indigo-600 rounded-lg py-2 px-5 transition-all duration-200">
                                    <i class="fas fa-download mr-2"></i> 下载
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-600 text-white text-center py-4 mt-8 shadow-sm">
        <p>&copy; <?php echo date('Y'); ?> 版本下载站. All rights reserved.</p>
    </footer>

</body>
</html>
