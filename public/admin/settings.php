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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $favicon = $_POST['favicon'];
    $db->query("UPDATE settings SET title = ?, description = ?, keywords = ?, favicon = ? WHERE id = 1", [$title, $description, $keywords, $favicon]);
    header('Location: settings.php');
    exit;
}
$settings = $db->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 站点设置</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50">
    <?php include('header.php'); ?>
    <main class="container mx-auto p-8 mt-6 bg-white shadow-lg rounded-lg">
        <section class="space-y-6 mt-12">
    <h2 class="text-2xl font-semibold text-gray-800">修改站点信息</h2>
    <form method="POST" class="space-y-6">
        <!-- 站点标题 -->
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <label for="title" class="text-lg font-medium text-gray-700 w-full md:w-1/4">站点标题</label>
            <input 
                id="title" 
                name="title" 
                type="text" 
                class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="站点标题" 
                value="<?php echo htmlspecialchars($settings['title']); ?>" 
                required>
        </div>
        <!-- 关于站点 -->
        <div>
            <label for="description" class="block text-gray-700 font-medium mb-2">关于站点</label>
            <textarea 
                id="description" 
                name="description" 
                rows="5" 
                class="block w-full p-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                placeholder="站点描述" 
                required><?php echo htmlspecialchars($settings['description']); ?></textarea>
        </div>
        <!-- 关键词 -->
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <label for="keywords" class="text-lg font-medium text-gray-700 w-full md:w-1/4">关键词 (用逗号分隔)</label>
            <input 
                id="keywords" 
                name="keywords" 
                type="text" 
                class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="关键词" 
                value="<?php echo htmlspecialchars($settings['keywords']); ?>" 
                required>
        </div>
        <!-- Favicon 链接 -->
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <label for="favicon" class="text-lg font-medium text-gray-700 w-full md:w-1/4">Favicon 链接</label>
            <input 
                id="favicon" 
                name="favicon" 
                type="text" 
                class="w-full md:w-2/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Favicon 链接" 
                value="<?php echo htmlspecialchars($settings['favicon']); ?>">
        </div>
        <!-- 提交按钮 -->
        <div class="mt-6">
            <button 
                type="submit" 
                class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-300">
                <i class="fas fa-save mr-2"></i> 保存设置
            </button>
        </div>
    </form>
</section>
    </main>
    <?php include('footer.php'); ?>

</body>
</html>
