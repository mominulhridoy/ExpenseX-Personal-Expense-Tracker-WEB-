<?php

class Transaction extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'transactions';
    }

    /**
     * Find a specific transaction by ID
     * This fixes the "Call to undefined method" error
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
     * Update an existing record
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
        
        $data['id'] = $id;
        $data['user_id'] = $user_id;
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Get filtered results for the index page
     */
    public function getFiltered($user_id, $filters, $limit, $offset) {
        $sql = "SELECT t.*, c.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = :user_id";
        
        $params = [':user_id' => $user_id];

        if (!empty($filters['search'])) {
            $sql .= " AND t.title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY t.transaction_date DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countFiltered($user_id, $filters) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
