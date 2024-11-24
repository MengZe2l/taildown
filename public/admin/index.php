<?php
require '../../config.php';
require '../../src/database.php';
require '../../src/auth.php';

$config = require '../../config.php';
$db = new Database($config);
$auth = new Auth($db);

// 检查是否已登录
if ($auth->isAuthenticated()) {
    header('Location: dashboard.php'); // 如果已登录，跳转到仪表盘
    exit;
} else {
    header('Location: ./login.php'); // 未登录则跳转到登录页面
    exit;
}
