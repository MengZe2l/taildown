<?php
require '../../config.php';
require '../../src/database.php';
require '../../src/auth.php';

$config = require '../../config.php';
$db = new Database($config);
$auth = new Auth($db);

// 检查是否已登录
if ($auth->isAuthenticated()) {
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: ./login.php');
    exit;
}
