<?php

class TransactionController {
    
    public function index() {
        Auth::requireLogin();
        
        $transactionModel = new Transaction();
        $categoryModel = new Category();
        
        $user_id = Auth::id();
        
        // Paginator setup
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Filters
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'type' => $_GET['type'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];
        
        $transactions = $transactionModel->getFiltered($user_id, $filters, $limit, $offset);
        $total_records = $transactionModel->countFiltered($user_id, $filters);
        $total_pages = ceil($total_records / $limit);
        
        // Data for dropdowns
        $categories = $categoryModel->getAllForUser($user_id);
        
        require_once 'views/transactions/index.php';
    }

    public function create() {
        Auth::requireLogin();
        
        $categoryModel = new Category();
        $user_id = Auth::id();
        $income_categories = $categoryModel->getByType($user_id, 'income');
        $expense_categories = $categoryModel->getByType($user_id, 'expense');
        
        require_once 'views/transactions/form.php';
    }

    public function store() {
        Auth::requireLogin();
        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            die("CSRF Validation Failed");
        }

        $transactionModel = new Transaction();
        $data = [
            'user_id' => Auth::id(),
            'type' => $_POST['type'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'title' => trim($_POST['title'] ?? ''),
            'amount' => $_POST['amount'] ?? 0,
            'transaction_date' => $_POST['transaction_date'] ?? '',
            'note' => trim($_POST['note'] ?? ''),
            'is_recurring' => isset($_POST['is_recurring']) ? 1 : 0
        ];
        
        if (empty($data['title']) || empty($data['amount']) || empty($data['category_id']) || empty($data['transaction_date'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please fill all required fields.'];
            header("Location: index.php?route=transactions_add");
            return;
        }

        if ($transactionModel->create($data)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Transaction added successfully.'];
            header("Location: index.php?route=transactions");
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to add transaction.'];
            header("Location: index.php?route=transactions_add");
        }
    }

    public function edit() {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=transactions");
            exit;
        }

        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($id);

        if (!$transaction || $transaction['user_id'] != Auth::id()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Transaction not found.'];
            header("Location: index.php?route=transactions");
            exit;
        }

        $categoryModel = new Category();
        $user_id = Auth::id();
        $income_categories = $categoryModel->getByType($user_id, 'income');
        $expense_categories = $categoryModel->getByType($user_id, 'expense');
        
        require_once 'views/transactions/form.php';
    }

    public function update() {
        Auth::requireLogin();
        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            die("CSRF Validation Failed");
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: index.php?route=transactions");
            return;
        }

        $transactionModel = new Transaction();
        $data = [
            'type' => $_POST['type'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'title' => trim($_POST['title'] ?? ''),
            'amount' => $_POST['amount'] ?? 0,
            'transaction_date' => $_POST['transaction_date'] ?? '',
            'note' => trim($_POST['note'] ?? ''),
            'is_recurring' => isset($_POST['is_recurring']) ? 1 : 0
        ];

        
        if ($transactionModel->updateRecord($id, Auth::id(), $data)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Transaction updated.'];
            header("Location: index.php?route=transactions");
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Update failed.'];
            header("Location: index.php?route=transactions_edit&id=$id");
        }
    }

    public function delete() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            die("Invalid Request");
        }

        $id = $_POST['id'] ?? null;
        $transactionModel = new Transaction();
        $transaction = $transactionModel->find($id);

        if ($transaction && $transaction['user_id'] == Auth::id()) {
            $transactionModel->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Transaction deleted.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Transaction not found.'];
        }

        header("Location: index.php?route=transactions");
    }
}
?>
