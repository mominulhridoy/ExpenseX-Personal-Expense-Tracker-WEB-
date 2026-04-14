<?php

class Budget extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'budgets';
    }

    public function createOrUpdate($data) {
        $sql = "INSERT INTO {$this->table} (user_id, category_id, amount, month, year) 
                VALUES (:user_id, :category_id, :amount, :month, :year)
                ON DUPLICATE KEY UPDATE amount = VALUES(amount)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':category_id' => $data['category_id'],
            ':amount' => $data['amount'],
            ':month' => $data['month'],
            ':year' => $data['year']
        ]);
    }

    // Get all budgets with their current spending for a specific user and month
    public function getBudgetsWithSpending($user_id, $month, $year) {
        $sql = "SELECT b.id, b.category_id, b.amount as budget_amount, c.name, c.icon, c.color,
                COALESCE(SUM(t.amount), 0) as spent_amount
                FROM {$this->table} b
                JOIN categories c ON b.category_id = c.id
                LEFT JOIN transactions t ON t.category_id = b.category_id 
                    AND t.user_id = b.user_id 
                    AND MONTH(t.transaction_date) = :month 
                    AND YEAR(t.transaction_date) = :year
                    AND t.type = 'expense'
                WHERE b.user_id = :user_id AND b.month = :month AND b.year = :year
                GROUP BY b.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':month' => $month,
            ':year' => $year
        ]);
        return $stmt->fetchAll();
    }
}
?>
