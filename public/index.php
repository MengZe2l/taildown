<?php
require '../config.php';
require '../src/database.php';
require '../src/functions.php';
require '../src/helpers.php';

$config = require '../config.php';
$db = new Database($config);
$versions = getVersions($db) ?? [];
$announcements = getLatestAnnouncement($db) ?? [];
$settings = getSettings($db) ?? [];

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;
if ($search) {
    $versions = searchVersionsPaginated($db, $search, $itemsPerPage, $offset);
    $totalItems = countSearchResults($db, $search);
} else {
    $versions = getVersionsPaginated($db, $itemsPerPage, $offset);
    $totalItems = countTotalVersions($db);
}
$totalPages = ceil($totalItems / $itemsPerPage);
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
      <i id="tipIcon" class="fas fa-info-circle icon-btn text-blue-600" title="简介"></i>
    </div>
  </div>

    <!-- Main Content -->
    <main class="py-8 bg-gray-100">
        
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- 左侧公告栏和搜索栏 -->
        <aside class="lg:col-span-1 space-y-6">
            <!-- 公告 -->
            <section id="announcement" class="bg-white p-6 rounded-lg shadow relative">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">公告</h2>
                <p class="text-gray-600">
                    <?php echo $announcements ? sanitize($announcements[0]['content']) : '暂无公告'; ?>
                </p>
            </section>

            <!-- 搜索 -->
            <section class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">搜索版本</h2>
                <form action="" method="get" class="flex flex-col space-y-4">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="输入关键词进行搜索..." 
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    >
                    <button 
                        type="submit" 
                        class="w-full px-6 py-2 bg-indigo-500 text-white font-semibold rounded-lg shadow hover:bg-indigo-600 transition-all"
                    >
                        搜索
                    </button>
                </form>
            </section>
        </aside>

        <!-- 右侧版本列表 -->
        <section class="lg:col-span-3 p-2 rounded-lg">
            
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
</div>
        
            <div id="modal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold text-gray-900" id="modal-title">关于本站</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($settings['description']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" id="cancel-button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            关闭
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <section class="mt-8 flex justify-center space-x-2">
    <!-- 上页按钮 -->
    <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            ⇦
        </a>
    <?php endif; ?>

    <!-- 首页 -->
    <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=1" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            1
        </a>
        <?php if ($page > 2): ?>
            <span class="px-4 py-2 text-gray-500">...</span>
        <?php endif; ?>
    <?php endif; ?>

    <!-- 当前页 -->
    <span class="px-4 py-2 bg-indigo-500 text-white rounded">
        <?php echo $page; ?>
    </span>

    <!-- 尾页 -->
    <?php if ($page < $totalPages): ?>
        <?php if ($page < $totalPages - 1): ?>
            <span class="px-4 py-2 text-gray-500">...</span>
        <?php endif; ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $totalPages; ?>" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            <?php echo $totalPages; ?>
        </a>
    <?php endif; ?>

    <!-- 下页按钮 -->
    <?php if ($page < $totalPages): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            ⇨
        </a>
    <?php endif; ?>
</section>
    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-800">
        <div class="container mx-auto py-4 px-4 text-center">
            <p class="text-sm">&copy; <?php echo date('Y'); ?> <?php echo sanitize($settings['title'] ?? '版本下载站'); ?> | 由 MengZe2l 提供程序支持</p>
        </div>
    </footer>

 <script>
        const modal = document.getElementById('modal');
        const tipIcon = document.getElementById('tipIcon');
        const cancelButton = document.getElementById('cancel-button');
        function openModal() {
            modal.classList.remove('hidden');
        }
        function closeModal() {
            modal.classList.add('hidden');
        }
        tipIcon.addEventListener('click', openModal);
        cancelButton.addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>