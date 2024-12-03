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

$settings = $db->query('SELECT * FROM settings LIMIT 1')->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['announcement_content'])) {
    $announcement_content = $_POST['announcement_content'];
    $db->query("INSERT INTO announcements (content) VALUES (?)", [$announcement_content]);
    header('Location: announcements.php');
    exit;
}
$announcements = $db->query('SELECT * FROM announcements ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 公告管理</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include('header.php'); ?>
    <main class="container mx-auto p-8 mt-8 bg-white shadow-lg rounded-lg">
        <section class="space-y-8">
            <h2 class="text-3xl font-bold text-gray-800 border-b pb-4">发布公告</h2>
            <form method="POST" class="space-y-6">
                <div>
                    <label for="announcement_content" class="block text-lg font-medium text-gray-700 mb-2">公告内容</label>
                    <textarea id="announcement_content" name="announcement_content" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="请输入公告内容..." required></textarea>
                </div>
                <button type="submit" class="w-full md:w-auto bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-bullhorn mr-2"></i> 发布公告
                </button>
            </form>
        </section>
        <section class="mt-12">
            <h2 class="text-3xl font-bold text-gray-800 border-b pb-4 mb-6">公告列表</h2>
            <?php if (count($announcements) > 0): ?>
                <div class="space-y-6">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition duration-300">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-bullhorn text-blue-500 mr-3"></i>
                                    公告 <?php echo date('Y-m-d H:i:s', strtotime($announcement['created_at'])); ?>
                                </h3>
                            </div>
                            <p class="text-gray-700 mt-4 leading-relaxed"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center">没有公告。</p>
            <?php endif; ?>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
