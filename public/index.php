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
    <style>
    @media (min-width: 1024px) {
      .topbar {
        padding: 1rem 2rem;
      }
      .topbar .text-lg {
        font-size: 1.5rem;
      }
      .footbar {
        padding-left: 3rem;
        padding-top: 3rem;
        padding-bottom: 3rem;
      }
      #roomList {
        grid-template-columns: repeat(4, 1fr);
      }
      .floating-btn {
        bottom: 30px;
        right: 30px;
        padding: 1.5rem;
        font-size: 2rem;
      }
    }
      .topbar {
      position: sticky;
      top: 0;
      z-index: 50;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 0.5rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: rgba(255, 255, 255, 0.8);
    }

    .topbar .text-lg {
      font-size: 1.25rem;
    }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Header -->
      <div class="topbar">
    <div>
      <span class="text-lg font-bold"><?php echo sanitize($settings['title'] ?? '版本下载站'); ?></h1></span>
    </div>
    <div>
      <i id="tipIcon" class="fas fa-info-circle icon-btn text-blue-600" title="说明"></i>
    </div>
  </div>

    <!-- Main Content -->
    <main class="py-8 bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Announcements -->
            <section id="announcement" class="bg-white p-6 rounded-lg shadow mb-8 relative">
    <button 
        onclick="document.getElementById('announcement').style.display='none'" 
        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 focus:outline-none"
        aria-label="关闭">
        ✖
    </button>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">公告</h2>
    <p class="text-gray-600">
        <?php echo $announcements ? sanitize($announcements[0]['content']) : '暂无公告'; ?>
    </p>
</section>

            <!-- Versions -->
            <section class="p-2 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">版本列表</h2>
                <?php if (count($versions) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($versions as $version): ?>
                        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
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
    <footer class="bg-gradient-to-r text-white from-indigo-400 via-purple-500 to-indigo-600 footbar">
        <p>&copy; <?php echo date('Y'); ?> <?php echo sanitize($settings['title'] ?? '版本下载站'); ?>. 版权所有.</p>
    </footer>

</body>
</html>
