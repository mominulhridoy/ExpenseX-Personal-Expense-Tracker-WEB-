<?php
require_once __DIR__ . '/../auth/session.php';
$is_auth_page = isset($is_auth_page) ? $is_auth_page : false;
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseX - Personal Expense Tracker</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#4f46e5', // Indigo 600
                        secondary: '#10b981', // Emerald 500
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</head>
<body class="h-full font-sans text-gray-800 antialiased selection:bg-primary selection:text-white">

<?php 
// Display flash messages
$flash = getFlashMessage();
if ($flash): 
    $flashClass = 'bg-blue-100 text-blue-800 border-blue-200';
    $icon = 'fa-info-circle text-blue-500';
    if ($flash['type'] === 'error') {
        $flashClass = 'bg-red-50 text-red-800 border-red-200';
        $icon = 'fa-exclamation-circle text-red-500';
    } elseif ($flash['type'] === 'success') {
        $flashClass = 'bg-green-50 text-green-800 border-green-200';
        $icon = 'fa-check-circle text-green-500';
    }
?>
    <div id="flash-message" class="fixed top-4 right-4 z-50 max-w-sm rounded-lg border-l-4 <?php echo $flashClass; ?> p-4 shadow-lg flex items-start space-x-3 transition-opacity duration-300">
        <i class="fas <?php echo $icon; ?> mt-0.5 text-lg"></i>
        <div class="flex-1">
            <p class="font-medium"><?php echo htmlspecialchars($flash['message']); ?></p>
        </div>
        <button onclick="document.getElementById('flash-message').style.display='none'" class="text-gray-400 hover:text-gray-600 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        // Auto-hide flash message after 5 seconds
        setTimeout(() => {
            const flashMsg = document.getElementById('flash-message');
            if (flashMsg) {
                flashMsg.style.opacity = '0';
                setTimeout(() => flashMsg.style.display = 'none', 300);
            }
        }, 5000);
    </script>
<?php endif; ?>

<?php if (!$is_auth_page): ?>
    <div class="flex h-screen overflow-hidden bg-slate-50">
        <!-- Sidebar layout starts -->
        <?php require_once 'sidebar.php'; ?>
        
        <!-- Main Content Area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <!-- Top Header Navbar -->
            <header class="sticky top-0 z-30 flex items-center justify-between w-full h-16 px-6 bg-white border-b border-slate-200 shadow-sm">
                <!-- Mobile menu button -->
                <button class="text-slate-500 hover:text-slate-600 lg:hidden focus:outline-none" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <h1 class="text-xl font-semibold text-slate-800 lg:hidden">ExpenseX</h1>
                
                <div class="flex items-center ml-auto">
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-sm font-medium text-slate-700 hover:text-primary focus:outline-none transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center shadow-sm">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <span class="hidden md:block"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                        </button>
                    </div>
                </div>
            </header>
            
            <main class="w-full px-6 py-8 mx-auto xl:max-w-7xl">
<?php endif; ?>
