<?php
function redirect($url) {
    header("Location: $url");
    exit;
}
function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}