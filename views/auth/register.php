<?php 
$is_auth_page = true;
ob_start(); 
?>

<div class="bg-white dark:bg-dark-card p-8 rounded-2xl shadow-xl border border-slate-100 dark:border-dark-border w-full transform transition-all">
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-gradient-to-br from-primary to-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none mb-6">
            <i class="fas fa-user-plus text-3xl text-white"></i>
        </div>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Create Account</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Join ExpenseX to master your finances</p>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg flex items-start space-x-3">
            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
            <p class="text-sm text-red-700 dark:text-red-400 font-medium"><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <form class="space-y-5" action="index.php?route=register" method="POST" onsubmit="window.showLoader()">
        <?php echo CSRF::getTokenField(); ?>
        
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Full Name</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-slate-400"></i>
                </div>
                <input id="name" name="name" type="text" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-dark-border rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors sm:text-sm" placeholder="John Doe">
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-400"></i>
                </div>
                <input id="email" name="email" type="email" autocomplete="email" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-dark-border rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors sm:text-sm" placeholder="you@example.com">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-slate-400"></i>
                </div>
                <input id="password" name="password" type="password" required minlength="6" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-dark-border rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors sm:text-sm" placeholder="At least 6 characters">
            </div>
        </div>

        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-md shadow-indigo-200 dark:shadow-none text-sm font-medium text-white bg-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-dark-card transition-all transform hover:-translate-y-0.5 mt-2">
            Sign Up
        </button>
    </form>

    <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
        Already have an account? 
        <a href="index.php?route=login" class="font-medium text-primary hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors">Sign in here</a>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once 'views/layouts/app.php'; 
?>
