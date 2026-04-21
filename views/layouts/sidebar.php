<?php
$route = $_GET['route'] ?? 'dashboard';
?>
<!-- Sidebar -->
<aside id="sidebar" class="absolute z-40 flex flex-col w-64 h-screen overflow-y-auto bg-white dark:bg-dark-card border-r border-slate-200 dark:border-dark-border transition-transform duration-300 ease-in-out transform -translate-x-full lg:static lg:translate-x-0 shadow-sm">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
        <a href="index.php?route=dashboard" class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none text-white">
                <i class="fas fa-wallet text-lg"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Expense<span class="text-primary">X</span></h2>
        </a>
        <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 lg:hidden p-1" onclick="document.getElementById('sidebar').classList.add('-translate-x-full')">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="px-4 py-6 space-y-1">
        <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>
        
        <a href="index.php?route=dashboard" class="<?php echo ($route == 'dashboard') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-primary dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200'; ?> flex items-center px-3 py-2.5 text-sm font-medium transition-colors rounded-lg group">
            <i class="fas fa-chart-pie w-6 text-center <?php echo ($route == 'dashboard') ? 'text-primary dark:text-indigo-400' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-400'; ?>"></i>
            <span>Dashboard</span>
        </a>

        <a href="index.php?route=transactions" class="<?php echo ($route == 'transactions') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-primary dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200'; ?> flex items-center px-3 py-2.5 text-sm font-medium transition-colors rounded-lg group">
            <i class="fas fa-list w-6 text-center <?php echo ($route == 'transactions') ? 'text-primary dark:text-indigo-400' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-400'; ?>"></i>
            <span>Transactions</span>
        </a>

        <a href="index.php?route=budgets" class="<?php echo ($route == 'budgets') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-primary dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200'; ?> flex items-center px-3 py-2.5 text-sm font-medium transition-colors rounded-lg group">
            <i class="fas fa-bullseye w-6 text-center <?php echo ($route == 'budgets') ? 'text-primary dark:text-indigo-400' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-400'; ?>"></i>
            <span>Budgets</span>
        </a>
    </div>

    <?php if (Auth::isAdmin()): ?>
    <div class="px-4 py-2 space-y-1">
        <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Administration</p>
        <a href="index.php?route=admin_dashboard" class="<?php echo ($route == 'admin_dashboard') ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200'; ?> flex items-center px-3 py-2.5 text-sm font-medium transition-colors rounded-lg group">
            <i class="fas fa-shield-alt w-6 text-center <?php echo ($route == 'admin_dashboard') ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400 group-hover:text-slate-500 dark:text-slate-500 dark:group-hover:text-slate-400'; ?>"></i>
            <span>Admin Panel</span>
        </a>
    </div>
    <?php endif; ?>

    <!-- Quick Action Button in Sidebar -->
    <div class="px-4 mt-6">
        <a href="index.php?route=transactions_add" class="flex flex-col items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors border border-transparent rounded-xl shadow-sm shadow-indigo-200 dark:shadow-none bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-dark-card group">
            <div class="flex items-center space-x-2">
                <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-200"></i>
                <span>Add Record</span>
            </div>
        </a>
    </div>

</aside>

<!-- Sidebar Overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/80 backdrop-blur-sm z-30 hidden lg:hidden" onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden');"></div>

<script>
    // Show overlay when sidebar is open on mobile
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "class") {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    overlay.classList.remove('hidden');
                } else {
                    overlay.classList.add('hidden');
                }
            }
        });
    });
    observer.observe(document.getElementById('sidebar'), { attributes: true });
</script>
