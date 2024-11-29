<?php
require '../../config.php';
require '../../src/database.php';
require '../../src/auth.php';
require '../../src/helpers.php';

$config = require '../../config.php';
$db = new Database($config);
$auth = new Auth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        redirect('dashboard.php');
    } else {
        $error = '登录失败，请检查用户名或密码';
    }
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员登录</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-indigo-100 via-purple-200 to-indigo-100">

    <div class="max-w-md mx-auto mt-32 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6">
            <i class="fas fa-sign-in-alt text-indigo-600 mr-2"></i> 管理员登录
        </h1>

        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo sanitize($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-600">用户名</label>
                <input name="username" type="text" id="username" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="请输入用户名" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-600">密码</label>
                <input name="password" type="password" id="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="请输入密码" required>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-3 rounded-lg hover:bg-gradient-to-l focus:ring-2 focus:ring-indigo-500 transition duration-200">
                登录
            </button>
        </form>
    </div>
    <?php include('footer.php'); ?>

</body>
</html>
