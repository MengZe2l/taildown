<?php
if (file_exists(__DIR__ . '/config.php')) {
    die('安装程序已运行，请删除 config.php 文件后再试。');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];
    $admin_user = $_POST['admin_user'];
    $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
    $site_title = $_POST['site_title'];
    $site_desc = $_POST['site_desc'];
    $site_keywords = $_POST['site_keywords'];
    $site_favicon = $_POST['site_favicon'];
    $conn = new mysqli($db_host, $db_user, $db_pass);
    if ($conn->connect_error) {
        die('数据库连接失败: ' . $conn->connect_error);
    }
    $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`");
    $conn->select_db($db_name);
    $queries = [
        "CREATE TABLE IF NOT EXISTS `versions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `version` VARCHAR(50) NOT NULL,
            `changelog` TEXT NOT NULL,
            `file_url` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS `announcements` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `content` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS `settings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `keywords` TEXT NOT NULL,
            `favicon` TEXT NOT NULL
        );",
        "CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL,
            `password` VARCHAR(255) NOT NULL
        );"
    ];
    foreach ($queries as $query) {
        if (!$conn->query($query)) {
            die('数据库表创建失败: ' . $conn->error);
        }
    }
    $conn->query("INSERT INTO `settings` (title, description, keywords, favicon) VALUES ('$site_title', '$site_desc', '$site_keywords', '$site_favicon');");
    $conn->query("INSERT INTO `admins` (username, password) VALUES ('$admin_user', '$admin_pass');");
    $config_content = <<<PHP
<?php
return [
    'db_host' => '$db_host',
    'db_user' => '$db_user',
    'db_pass' => '$db_pass',
    'db_name' => '$db_name',
];
PHP;
    if (!file_put_contents(__DIR__ . '/config.php', $config_content)) {
        die('配置文件写入失败，请检查目录权限。');
    }
    echo '安装完成！您现在可以删除 install.php 文件并开始使用。';
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安装程序</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6e7aef, #ff79c6);
        }
    </style>
</head>
<body class="font-sans">

    <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-center text-gray-800 mb-6">安装程序</h1>
<p class="mb-6">安装向导是梦泽觉得写的最好的代码哦～<br/>安装成功之后记得删除<code>install.php</code>文件哦</p>
        <form method="POST">
            <!-- 数据库配置 -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">数据库配置</h2>
                <label>
                    <input name="db_host" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="数据库地址 (如: localhost)" required>
                </label>
                <label>
                    <input name="db_user" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="数据库用户名" required>
                </label>
                <label>
                    <input name="db_pass" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" placeholder="数据库密码">
                </label>
                <label>
                    <input name="db_name" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="数据库名称" required>
                </label>
            </section>

            <!-- 管理员配置 -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">管理员配置</h2>
                <label>
                    <input name="admin_user" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="管理员用户名" required>
                </label>
                <label>
                    <input name="admin_pass" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" placeholder="管理员密码" required>
                </label>
            </section>

            <!-- 站点信息 -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">站点信息</h2>
                <label>
                    <input name="site_title" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="站点标题" required>
                </label>
                <label>
                    <input name="site_desc" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="站点简介" required>
                </label>
                <label>
                    <input name="site_keywords" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="站点关键词" required>
                </label>
                <label>
                    <input name="site_favicon" class="block w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Favicon URL (可选)">
                </label>
            </section>

            <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">开始安装</button>
        </form>
    </div>

</body>
</html>
