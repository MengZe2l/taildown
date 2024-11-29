<?php
class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 检查用户是否已登录
    public function isAuthenticated() {
        session_start();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    // 登录验证
    public function login($username, $password) {
        $stmt = $this->db->query("SELECT * FROM admins WHERE username = ?", [$username]);
        $admin = $stmt->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            session_start();
            $_SESSION['admin_logged_in'] = true;
            return true;
        }
        return false;
    }

    // 注销登录
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
