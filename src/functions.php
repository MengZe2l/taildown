<?php
function getVersions($db) {
    return $db->query('SELECT * FROM versions ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
}

function getLatestAnnouncement($db) {
    $result = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 1");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllAnnouncements($db, $page = 1, $perPage = 5) {
    $offset = ($page - 1) * $perPage;
    $result = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT $offset, $perPage");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAnnouncementCount($db) {
    $result = $db->query("SELECT COUNT(*) AS count FROM announcements");
    return $result->fetch_assoc()['count'];
}

function getSettings($db) {
    return $db->query('SELECT * FROM settings LIMIT 1')->fetch_assoc();
}

function searchVersions($db, $keyword) {
    $keyword = '%' . $keyword . '%';
    $sql = "SELECT * FROM versions WHERE version LIKE ? OR changelog LIKE ?";
    return $db->query($sql, [$keyword, $keyword])->fetch_all(MYSQLI_ASSOC);
}

function getVersionsPaginated($db, $limit, $offset) {
    $sql = "SELECT * FROM versions ORDER BY created_at DESC LIMIT ? OFFSET ?";
    return $db->query($sql, [$limit, $offset])->fetch_all(MYSQLI_ASSOC);
}

function searchVersionsPaginated($db, $keyword, $limit, $offset) {
    $sql = "SELECT * FROM versions WHERE version LIKE ? OR changelog LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $param = "%$keyword%";
    return $db->query($sql, [$param, $param, $limit, $offset])->fetch_all(MYSQLI_ASSOC);
}

function countTotalVersions($db) {
    $sql = "SELECT COUNT(*) AS total FROM versions";
    return $db->query($sql)->fetch_assoc()['total'];
}

function countSearchResults($db, $keyword) {
    $sql = "SELECT COUNT(*) AS total FROM versions WHERE version LIKE ? OR changelog LIKE ?";
    $param = "%$keyword%";
    return $db->query($sql, [$param, $param])->fetch_assoc()['total'];
}
?>
