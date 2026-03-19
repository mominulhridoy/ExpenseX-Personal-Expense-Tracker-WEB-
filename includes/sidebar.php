<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside id="sidebar" class="absolute z-20 flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-white border-r border-slate-200 transition-transform duration-300 ease-in-out transform -translate-x-full lg:static lg:translate-x-0 shadow-sm">
    <div class="flex items-center justify-center mb-10">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-200 text-white">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Expense<span class="text-primary">X</span></h2>
        </div>
        <!-- Close button for mobile -->
        <button class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 lg:hidden" onclick="document.getElementById('sidebar').classList.add('-translate-x-full')">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="flex flex-col space-y-2">
        <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'bg-indigo-50 text-primary border-l-4 border-primary' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent'; ?> flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-r-lg group">
            <i class="fas fa-home w-6 <?php echo ($current_page == 'dashboard.php') ? 'text-primary' : 'text-slate-400 group-hover:text-slate-500'; ?>"></i>
            <span>Dashboard</span>
        </a>

        <a href="expenses.php" class="<?php echo ($current_page == 'expenses.php') ? 'bg-indigo-50 text-primary border-l-4 border-primary' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent'; ?> flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-r-lg group">
            <i class="fas fa-list-ul w-6 <?php echo ($current_page == 'expenses.php') ? 'text-primary' : 'text-slate-400 group-hover:text-slate-500'; ?>"></i>
            <span>All Expenses</span>
        </a>

        <a href="add_expense.php" class="<?php echo ($current_page == 'add_expense.php') ? 'bg-indigo-50 text-primary border-l-4 border-primary' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent'; ?> flex items-center px-4 py-3 text-sm font-medium transition-colors rounded-r-lg group">
            <i class="fas fa-plus-circle w-6 <?php echo ($current_page == 'add_expense.php') ? 'text-primary' : 'text-slate-400 group-hover:text-slate-500'; ?>"></i>
            <span>Add Expense</span>
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-200">
        <a href="logout.php" class="flex items-center px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-50 rounded-lg transition-colors group">
            <i class="fas fa-sign-out-alt w-6 text-red-400 group-hover:text-red-500"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
<!-- Sidebar Overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900 bg-opacity-50 z-10 hidden lg:hidden" onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden');"></div>
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
