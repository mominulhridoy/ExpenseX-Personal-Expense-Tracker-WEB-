<?php
class User extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'users';
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($name, $email, $password) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $password]);
    }
}
?>
