<?php

class AdminController {
    
    public function dashboard() {
        Auth::requireAdmin();
        
        // Use the clean connection method
        $model = new Model();
        $conn = $model->getConnection();

        // System Stats
        $stats = [
            'total_users' => $this->getScalar($conn, "SELECT COUNT(*) FROM users"),
            'total_transactions' => $this->getScalar($conn, "SELECT COUNT(*) FROM transactions"),
            'total_volume' => $this->getScalar($conn, "SELECT SUM(amount) FROM transactions"),
            'active_budgets' => $this->getScalar($conn, "SELECT COUNT(*) FROM budgets WHERE month = ? AND year = ?", [date('m'), date('Y')])
        ];

        // Recent Users
        $stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
        $stmt->execute();
        $recent_users = $stmt->fetchAll();

        require_once 'views/admin/dashboard.php';
    }

    public function users() {
        Auth::requireAdmin();
        
        $model = new Model();
        $conn = $model->getConnection();
        
        $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        require_once 'views/admin/users.php';
    }

    private function getScalar($conn, $query, $params = []) {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchColumn();
        return $result ? (float)$result : 0;
    }
}