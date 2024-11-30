<?php
require '../../config.php';
require '../../src/database.php';
require '../../src/auth.php';

$config = require '../../config.php';
$db = new Database($config);
$auth = new Auth($db);

if ($auth->isAuthenticated()) {
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: ./login.php');
    exit;
}
