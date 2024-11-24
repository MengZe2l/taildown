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

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $version = $_POST['version'];
        $changelog = $_POST['changelog'];
        $file_url = $_POST['file_url'];

        $db->query("INSERT INTO versions (version, changelog, file_url) VALUES (?, ?, ?)", [$version, $changelog, $file_url]);
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $db->query("DELETE FROM versions WHERE id = ?", [$id]);
    }

    header('Location: versions.php');
    exit;
}

// 获取版本列表
$versions = $db->query("SELECT * FROM versions ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 版本管理</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- 引入 FontAwesome 图标库 -->
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-indigo-100 via-purple-200 to-indigo-100">

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

    <main class="container mx-auto p-6 mt-6 bg-white shadow-lg rounded-lg">
        <section>
            <h2 class="text-2xl font-semibold mb-6">已发布版本</h2>
            <div class="space-y-6">
                <?php foreach ($versions as $version): ?>
                    <div class="p-6 bg-gray-50 rounded-lg shadow-md hover:bg-gray-100 transition duration-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($version['version']); ?></h3>
                            <a href="<?php echo htmlspecialchars($version['file_url']); ?>" class="text-indigo-500 hover:text-indigo-600 transition duration-200">
                                <i class="fas fa-download mr-2"></i> 下载
                            </a>
                        </div>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($version['changelog']); ?></p>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $version['id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                                <i class="fas fa-trash-alt mr-2"></i> 删除
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mt-12">
            <h2 class="text-2xl font-semibold mb-6">新增版本</h2>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="action" value="add">
                <div class="mb-4">
                    <label for="version" class="block text-sm font-medium text-gray-600">版本号</label>
                    <input name="version" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="版本号" required>
                </div>

                <div class="mb-4">
                    <label for="changelog" class="block text-sm font-medium text-gray-600">更新日志</label>
                    <textarea name="changelog" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="更新日志" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="file_url" class="block text-sm font-medium text-gray-600">文件链接</label>
                    <input name="file_url" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="文件链接" required>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-3 rounded-lg hover:bg-gradient-to-l focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    新增版本
                </button>
            </form>
        </section>
    </main>

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
