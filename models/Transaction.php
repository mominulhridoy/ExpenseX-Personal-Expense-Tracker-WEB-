<?php

class Transaction extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'transactions';
    }

    /**
     * Create a new transaction record
     * Fixes: Call to undefined method Transaction::create()
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (user_id, type, category_id, title, amount, transaction_date, note, is_recurring) 
                VALUES (:user_id, :type, :category_id, :title, :amount, :transaction_date, :note, :is_recurring)";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':user_id'          => $data['user_id'],
            ':type'             => $data['type'],
            ':category_id'      => $data['category_id'],
            ':title'            => $data['title'],
            ':amount'           => $data['amount'],
            ':transaction_date' => $data['transaction_date'],
            ':note'             => $data['note'],
            ':is_recurring'     => $data['is_recurring']
        ]);
    }

    /**
     * Find a specific transaction by ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Delete a transaction by ID
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Update an existing transaction record
     */
    public function updateRecord($id, $user_id, $data) {
        $sql = "UPDATE {$this->table} 
                SET type = :type, 
                    category_id = :category_id, 
                    title = :title, 
                    amount = :amount, 
                    transaction_date = :transaction_date, 
                    note = :note, 
                    is_recurring = :is_recurring 
                WHERE id = :id AND user_id = :user_id";
        
        $params = [
            ':id'               => $id,
            ':user_id'          => $user_id,
            ':type'             => $data['type'],
            ':category_id'      => $data['category_id'],
            ':title'            => $data['title'],
            ':amount'           => $data['amount'],
            ':transaction_date' => $data['transaction_date'],
            ':note'             => $data['note'],
            ':is_recurring'     => $data['is_recurring']
        ];
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Get filtered transactions with pagination
     */
    public function getFiltered($user_id, $filters, $limit, $offset) {
        $sql = "SELECT t.*, c.name as category_name, c.color as category_color 
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

        $sql .= " ORDER BY t.transaction_date DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count total transactions for pagination
     */
    public function countFiltered($user_id, $filters) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];
        
        if (!empty($filters['search'])) {
            $sql .= " AND title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
?>
