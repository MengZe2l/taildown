<?php

$configFile = __DIR__ . '/config.php';

if (file_exists($configFile)) {
    header('Location: ../public/index.php');
} else {
    header('Location: install.php');
    exit;
}

