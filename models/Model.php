<?php

class Model {
    protected $conn;

    public function __construct() {
        // This assumes you have a config file or define variables here
        $host = 'localhost';
        $db   = 'expensex';
        $user = 'root';
        $pass = '';

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // This is the new method that fixes your error
    public function getConnection() {
        return $this->conn;
    }
}