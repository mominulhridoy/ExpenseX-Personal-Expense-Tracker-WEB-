<?php

class DashboardController {
    
    public function index() {
        Auth::requireLogin();
        $user_id = Auth::id();
        
        // Create the model and get the connection simply
        $model = new Model();
        $conn = $model->getConnection();

        // 1. Calculate Summary Cards
        $total_income = $this->getScalar($conn, "SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'income'", [$user_id]);
        $total_expense = $this->getScalar($conn, "SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'expense'", [$user_id]);
        $balance = $total_income - $total_expense;

        // 2. Monthly Trend for current year (Line Chart)
        $year = date('Y');
        $trend_data = $this->getMonthlyTrend($conn, $user_id, $year);

        // 3. Category Spending (Pie Chart) currently for this month
        $month = date('m');
        $category_data = $this->getCategorySpending($conn, $user_id, $month, $year);

        // 4. Recent Transactions
        $stmt = $conn->prepare("SELECT t.*, c.name as category_name, c.color as category_color 
                                FROM transactions t 
                                LEFT JOIN categories c ON t.category_id = c.id 
                                WHERE t.user_id = ? 
                                ORDER BY t.transaction_date DESC, t.id DESC LIMIT 5");
        $stmt->execute([$user_id]);
        $recent_transactions = $stmt->fetchAll();

        require_once 'views/dashboard/index.php';
    }

    private function getScalar($conn, $query, $params = []) {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchColumn();
        return $result ? (float)$result : 0.00;
    }

    private function getMonthlyTrend($conn, $user_id, $year) {
        $sql = "SELECT MONTH(transaction_date) as month, type, SUM(amount) as total 
                FROM transactions 
                WHERE user_id = :user_id AND YEAR(transaction_date) = :year 
                GROUP BY MONTH(transaction_date), type";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':year' => $year]);
        $results = $stmt->fetchAll();

        $trend = ['income' => array_fill(1, 12, 0), 'expense' => array_fill(1, 12, 0)];
        foreach ($results as $row) {
            $trend[$row['type']][$row['month']] = (float)$row['total'];
        }
        return [
            'income' => array_values($trend['income']), 
            'expense' => array_values($trend['expense'])
        ];
    }

    private function getCategorySpending($conn, $user_id, $month, $year) {
        $sql = "SELECT c.name, c.color, SUM(t.amount) as total 
                FROM transactions t
                JOIN categories c ON t.category_id = c.id
                WHERE t.user_id = :user_id AND MONTH(t.transaction_date) = :month AND YEAR(t.transaction_date) = :year AND t.type = 'expense'
                GROUP BY c.id
                ORDER BY total DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':month' => $month, ':year' => $year]);
        return $stmt->fetchAll();
    }
}