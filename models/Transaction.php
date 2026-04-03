<?php
class Transaction extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'transactions';
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, category_id, type, title, amount, transaction_date, note, is_recurring) 
                VALUES (:user_id, :category_id, :type, :title, :amount, :transaction_date, :note, :is_recurring)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':category_id' => $data['category_id'],
            ':type' => $data['type'],
            ':title' => $data['title'],
            ':amount' => $data['amount'],
            ':transaction_date' => $data['transaction_date'],
            ':note' => $data['note'] ?? null,
            ':is_recurring' => $data['is_recurring'] ?? 0
        ]);
    }

    public function updateRecord($id, $user_id, $data) {
        $sql = "UPDATE {$this->table} 
                SET category_id = :category_id, type = :type, title = :title, amount = :amount, 
                    transaction_date = :transaction_date, note = :note, is_recurring = :is_recurring 
                WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':type' => $data['type'],
            ':title' => $data['title'],
            ':amount' => $data['amount'],
            ':transaction_date' => $data['transaction_date'],
            ':note' => $data['note'] ?? null,
            ':is_recurring' => $data['is_recurring'] ?? 0,
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    public function getFiltered($user_id, $filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT t.*, c.name as category_name, c.icon as category_icon, c.color as category_color 
                FROM {$this->table} t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = :user_id";
        
        $params = [':user_id' => $user_id];

        if (!empty($filters['search'])) {
            $sql .= " AND t.title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['type'])) {
            $sql .= " AND t.type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['start_date'])) {
            $sql .= " AND t.transaction_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $sql .= " AND t.transaction_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY t.transaction_date DESC, t.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function countFiltered($user_id, $filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        if (!empty($filters['search'])) {
            $sql .= " AND title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['type'])) {
            $sql .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['start_date'])) {
            $sql .= " AND transaction_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $sql .= " AND transaction_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
?>
