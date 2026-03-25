<?php
session_start();

// Include Core Components
require_once 'core/Auth.php';
require_once 'core/CSRF.php';
require_once 'models/Model.php';

// Very Simple File Loader
$modelFiles = glob('models/*.php');
foreach ($modelFiles as $file) { require_once $file; }
$controllerFiles = glob('controllers/*.php');
foreach ($controllerFiles as $file) { require_once $file; }

$route = $_GET['route'] ?? 'dashboard';

// Simple Router Mapping Route -> Controller@Method
switch ($route) {
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $controller->loginPost();
        else $controller->login();
        break;
    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $controller->registerPost();
        else $controller->register();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
    case 'transactions':
        $controller = new TransactionController();
        $controller->index();
        break;
    case 'transactions_add':
        $controller = new TransactionController();
        $controller->create();
        break;
    case 'transactions_store':
        $controller = new TransactionController();
        $controller->store();
        break;
    case 'transactions_edit':
        $controller = new TransactionController();
        $controller->edit();
        break;
    case 'transactions_update':
        $controller = new TransactionController();
        $controller->update();
        break;
    case 'transactions_delete':
        $controller = new TransactionController();
        $controller->delete();
        break;
    case 'budgets':
        $controller = new BudgetController();
        $controller->index();
        break;
    case 'budgets_store':
        $controller = new BudgetController();
        $controller->store();
        break;
    case 'budgets_delete':
        $controller = new BudgetController();
        $controller->delete();
        break;
    case 'admin_dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;
    case 'admin_users':
        $controller = new AdminController();
        $controller->users();
        break;
    // ... Catch-all 404 handler
    default:
        // 404 handler implicitly redirects to dashboard
        header("Location: index.php?route=dashboard");
        exit;
}
?>
