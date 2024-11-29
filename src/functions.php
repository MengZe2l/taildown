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
?>
