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
<body class="bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50">
    <?php include('header.php'); ?>
    <main class="container mx-auto p-8 mt-6 bg-white shadow-lg rounded-lg">
        <section class="space-y-6 mt-12">
            <h2 class="text-2xl font-semibold text-gray-800">发布公告</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="announcement_content" class="block text-gray-700">公告内容</label>
                    <textarea id="announcement_content" name="announcement_content" rows="5" class="block w-full p-2 border rounded-lg" required></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition duration-300">
                    <i class="fas fa-bullhorn mr-2"></i> 发布公告
                </button>
            </form>
        </section>
        <section class="mt-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">公告列表</h2>
            <?php if (count($announcements) > 0): ?>
                <div class="space-y-6">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="border border-gray-200 p-6 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class="fas fa-bullhorn text-indigo-600 mr-3"></i>
                                    公告 <?php echo date('Y-m-d H:i:s', strtotime($announcement['created_at'])); ?>
                                </h3>
                            </div>
                            <p class="text-gray-700 mt-4"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">没有公告。</p>
            <?php endif; ?>
        </section>
    </main>
<?php include('footer.php'); ?>
</body>
</html>
