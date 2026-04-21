<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-900 transition-colors duration-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseX Professional - Financial Manager</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: '#4f46e5', // Indigo 600
                        secondary: '#10b981', // Emerald 500
                        dark: {
                            bg: '#0f172a',    // slate-900
                            card: '#1e293b',  // slate-800
                            border: '#334155' // slate-700
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Loading overlay */
        #loader { transition: opacity 0.3s ease; }
    </style>
</head>
<body class="h-full font-sans text-slate-800 dark:text-slate-200 antialiased selection:bg-primary selection:text-white">

    <!-- Loading Overlay -->
    <div id="loader" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm hidden">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-200 border-t-primary"></div>
    </div>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col space-y-2 pointer-events-none"></div>

    <?php 
    // Handle PHP Flash Messages
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo "<script>document.addEventListener('DOMContentLoaded', () => showToast('{$flash['type']}', '" . addslashes($flash['message']) . "'));</script>";
    }
    ?>

    <?php if (isset($is_auth_page) && $is_auth_page): ?>
        <!-- Auth Layout -->
        <div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-dark-bg py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary/20 rounded-full blur-3xl"></div>
            
            <div class="relative w-full max-w-md">
                <?php echo $content; ?>
            </div>
            
            <!-- Dark Mode Toggle floating for auth pages -->
            <button id="auth-theme-toggle" class="absolute top-4 right-4 p-2 rounded-lg text-slate-500 hover:bg-slate-200 dark:text-slate-400 dark:hover:bg-slate-800 focus:outline-none transition-colors">
                <i class="fas fa-moon dark:hidden text-xl"></i>
                <i class="fas fa-sun hidden dark:block text-xl"></i>
            </button>
        </div>
    <?php else: ?>
        <!-- Main Dashboard Layout -->
        <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-dark-bg">
            
            <?php require_once 'views/layouts/sidebar.php'; ?>
            
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                <?php require_once 'views/layouts/header.php'; ?>
                
                <main class="flex-grow w-full px-4 sm:px-6 lg:px-8 py-8 mx-auto max-w-7xl">
                    <?php echo $content; ?>
                </main>
                
                <footer class="mt-auto py-6 text-center text-sm text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-dark-border">
                    <p>&copy; <?php echo date('Y'); ?> ExpenseX Professional. All rights reserved.</p>
                </footer>
            </div>
        </div>
    <?php endif; ?>

    <!-- Core Scripts -->
    <script>
        // Dark Mode Logic
        const html = document.documentElement;
        const themeToggleBtns = document.querySelectorAll('#theme-toggle, #auth-theme-toggle');
        
        // Check local storage or system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        const toggleTheme = () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
            // Trigger custom event for charts to update
            window.dispatchEvent(new Event('theme-changed'));
        };

        themeToggleBtns.forEach(btn => btn.addEventListener('click', toggleTheme));

        // Toast Notification System
        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let icon = 'fa-info-circle';
            let colors = 'bg-white text-slate-800 border-slate-200';
            
            if (type === 'success') {
                icon = 'fa-check-circle text-emerald-500';
                colors = 'bg-white dark:bg-dark-card border-emerald-100 dark:border-emerald-900/30 text-slate-800 dark:text-slate-200';
            } else if (type === 'error') {
                icon = 'fa-exclamation-circle text-red-500';
                colors = 'bg-white dark:bg-dark-card border-red-100 dark:border-red-900/30 text-slate-800 dark:text-slate-200';
            }

            toast.className = `flex items-center w-full max-w-xs p-4 shadow-lg rounded-xl border ${colors} pointer-events-auto transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="ml-3 text-sm font-medium">${message}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white dark:bg-dark-card text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-slate-300 p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 inline-flex h-8 w-8 transition-colors" onclick="this.parentElement.remove()">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            });
            
            // Auto remove after 5s
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Global Loading Helper
        window.showLoader = () => document.getElementById('loader').classList.remove('hidden');
        window.hideLoader = () => document.getElementById('loader').classList.add('hidden');
    </script>
</body>
</html>
