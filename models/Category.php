<?php
class Category extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'categories';
    }

    public function getAllForUser($user_id) {
        // Fetch global (user_id IS NULL) and user-specific categories
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id IS NULL OR user_id = ? ORDER BY name ASC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
    
    public function getByType($user_id, $type) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE (user_id IS NULL OR user_id = ?) AND type = ? ORDER BY name ASC");
        $stmt->execute([$user_id, $type]);
        return $stmt->fetchAll();
    }
}
?>
