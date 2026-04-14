<?php ob_start(); ?>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-amber-600 dark:text-amber-500 flex items-center">
        <i class="fas fa-shield-alt mr-3"></i> Admin Dashboard
    </h2>
    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 text-red">System-wide overview and statistics</p>
</div>

<!-- System Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-dark-card rounded-xl p-5 border border-slate-100 dark:border-dark-border shadow-sm flex items-center space-x-4">
        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg flex flex-shrink-0 items-center justify-center">
            <i class="fas fa-users text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Users</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white"><?php echo number_format($stats['total_users']); ?></h3>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl p-5 border border-slate-100 dark:border-dark-border shadow-sm flex items-center space-x-4">
        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg flex flex-shrink-0 items-center justify-center">
            <i class="fas fa-exchange-alt text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Entries</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white"><?php echo number_format($stats['total_transactions']); ?></h3>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl p-5 border border-slate-100 dark:border-dark-border shadow-sm flex items-center space-x-4">
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex flex-shrink-0 items-center justify-center">
            <i class="fas fa-money-bill-wave text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Volume (৳)</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white"><?php echo number_format($stats['total_volume'] / 1000, 1); ?>k</h3>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl p-5 border border-slate-100 dark:border-dark-border shadow-sm flex items-center space-x-4">
        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg flex flex-shrink-0 items-center justify-center">
            <i class="fas fa-bullseye text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Active Budgets</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white"><?php echo number_format($stats['active_budgets']); ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Active Users Table -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-slate-100 dark:border-dark-border overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 dark:text-white">Recent Registrations</h3>
            <a href="index.php?route=admin_users" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">User</th>
                        <th class="px-5 py-3 font-medium">Role</th>
                        <th class="px-5 py-3 font-medium text-right">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    <?php foreach ($recent_users as $user): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-800 dark:text-white"><?php echo htmlspecialchars($user['name']); ?></p>
                            <p class="text-xs text-slate-500"><?php echo htmlspecialchars($user['email']); ?></p>
                        </td>
                        <td class="px-5 py-3">
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-md text-xs font-medium">Admin</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 rounded-md text-xs font-medium">User</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-right text-slate-500 dark:text-slate-400 text-xs">
                            <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-slate-100 dark:border-dark-border p-5">
        <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Admin Actions</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <a href="index.php?route=admin_users" class="flex flex-col items-center justify-center p-6 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/50 dark:hover:bg-slate-800 rounded-xl border border-slate-200 dark:border-dark-border transition-colors text-center group">
                <i class="fas fa-users-cog text-3xl text-indigo-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-slate-700 dark:text-slate-300">Manage Users</span>
            </a>
            
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/50 dark:hover:bg-slate-800 rounded-xl border border-slate-200 dark:border-dark-border transition-colors text-center group opacity-50 cursor-not-allowed" title="Coming Soon">
                <i class="fas fa-tags text-3xl text-emerald-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-slate-700 dark:text-slate-300">Global Categories</span>
                <span class="text-[10px] mt-1 text-slate-400">Coming Soon</span>
            </a>
            
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/50 dark:hover:bg-slate-800 rounded-xl border border-slate-200 dark:border-dark-border transition-colors text-center group opacity-50 cursor-not-allowed" title="Coming Soon">
                <i class="fas fa-file-export text-3xl text-blue-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-slate-700 dark:text-slate-300">System Reports</span>
                <span class="text-[10px] mt-1 text-slate-400">Coming Soon</span>
            </a>
            
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/50 dark:hover:bg-slate-800 rounded-xl border border-slate-200 dark:border-dark-border transition-colors text-center group opacity-50 cursor-not-allowed" title="Coming Soon">
                <i class="fas fa-cogs text-3xl text-slate-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-medium text-slate-700 dark:text-slate-300">Settings</span>
                <span class="text-[10px] mt-1 text-slate-400">Coming Soon</span>
            </a>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once 'views/layouts/app.php'; 
?>
