<?php

class Database {
    private $conn;
    public function __construct($config) {
        $this->conn = new mysqli(
            $config['db_host'],
            $config['db_user'],
            $config['db_pass'],
            $config['db_name']
        );
        if ($this->conn->connect_error) {
            die('数据库连接失败: ' . $this->conn->connect_error);
        }
    }
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }
    public function close() {
        $this->conn->close();
    }
}
