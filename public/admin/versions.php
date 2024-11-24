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

// 设置文件上传目录
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// 处理表单提交
$file_url = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $version = $_POST['version'];
        $changelog = $_POST['changelog'];

        // 文件上传处理
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = time() . '_' . basename($file['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $file_url = 'https://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $fileName;
            } else {
                $uploadError = "文件上传失败";
            }
        }

        // 保存数据到数据库
        if (empty($uploadError)) {
            $db->query("INSERT INTO versions (version, changelog, file_url) VALUES (?, ?, ?)", [$version, $changelog, $file_url]);
            header('Location: versions.php');
            exit;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $db->query("DELETE FROM versions WHERE id = ?", [$id]);
        header('Location: versions.php');
        exit;
    }
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
</head>
<body class="bg-gradient-to-r from-indigo-100 via-purple-200 to-indigo-100">
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
                                删除
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mt-12">
            <h2 class="text-2xl font-semibold mb-6">新增版本</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
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
                    <label for="file" class="block text-sm font-medium text-gray-600">上传文件</label>
                    <input type="file" name="file" id="fileInput" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                <div class="mb-4">
                    <label for="file_url" class="block text-sm font-medium text-gray-600">文件链接</label>
                    <input name="file_url" id="file_url" value="<?php echo htmlspecialchars($file_url); ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="文件链接" readonly>
                </div>

                <?php if (isset($uploadError)): ?>
                    <p class="text-red-500"><?php echo htmlspecialchars($uploadError); ?></p>
                <?php endif; ?>

                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-3 rounded-lg hover:bg-gradient-to-l focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    新增版本
                </button>
            </form>
        </section>
    </main>
</body>
</html>
