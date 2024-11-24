<?php

// 获取所有版本
function getVersions($db) {
    return $db->query('SELECT * FROM versions ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
}

// 获取最新公告
function getLatestAnnouncement($db) {
    $result = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 1");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// 获取所有公告（用于后台分页）
function getAllAnnouncements($db, $page = 1, $perPage = 5) {
    $offset = ($page - 1) * $perPage;
    $result = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT $offset, $perPage");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// 获取公告总数（用于分页）
function getAnnouncementCount($db) {
    $result = $db->query("SELECT COUNT(*) AS count FROM announcements");
    return $result->fetch_assoc()['count'];
}

// 获取站点设置
function getSettings($db) {
    return $db->query('SELECT * FROM settings LIMIT 1')->fetch_assoc();
}
?>
