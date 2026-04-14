<?php

class BudgetController {

    public function index() {
        Auth::requireLogin();
        
        $user_id = Auth::id();
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        
        $budgetModel = new Budget();
        $budgets = $budgetModel->getBudgetsWithSpending($user_id, $month, $year);
        
        $categoryModel = new Category();
        // Since you can only budget expenses
        $categories = $categoryModel->getByType($user_id, 'expense');

        require_once 'views/budgets/index.php';
    }

    public function store() {
        Auth::requireLogin();
        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            die("CSRF Validation Failed");
        }

        $user_id = Auth::id();
        $category_id = $_POST['category_id'] ?? null;
        $amount = $_POST['amount'] ?? 0;
        $month = $_POST['month'] ?? date('m');
        $year = $_POST['year'] ?? date('Y');

        if (!$category_id || $amount <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid budget parameters.'];
        } else {
            $budgetModel = new Budget();
            if ($budgetModel->createOrUpdate([
                'user_id' => $user_id,
                'category_id' => $category_id,
                'amount' => $amount,
                'month' => $month,
                'year' => $year
            ])) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Budget saved successfully.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to save budget.'];
            }
        }

        header("Location: index.php?route=budgets&month={$month}&year={$year}");
    }

    public function delete() {
        Auth::requireLogin();
        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            die("CSRF Validation Failed");
        }

        $id = $_POST['id'] ?? null;
        $budgetModel = new Budget();
        $budget = $budgetModel->find($id);

        if ($budget && $budget['user_id'] == Auth::id()) {
            $budgetModel->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Budget removed.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Budget not found.'];
        }

        header("Location: index.php?route=budgets");
    }
}
?>
