<?php
class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function isAuthenticated() {
        session_start();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
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
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
