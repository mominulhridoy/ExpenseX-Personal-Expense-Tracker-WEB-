<?php ob_start(); ?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-amber-600 dark:text-amber-500 flex items-center">
            <i class="fas fa-users-cog mr-3"></i> User Management
        </h2>
    </div>
    <a href="index.php?route=admin_dashboard" class="px-4 py-2 border border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm text-sm font-medium">
        Back to Dashboard
    </a>
</div>

<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-slate-100 dark:border-dark-border overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-xs">ID</th>
                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-xs">User Name</th>
                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-xs">Email</th>
                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-xs">Role</th>
                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-xs">Registered On</th>
                    <th class="px-6 py-4 font-medium uppercase tracking-wider text-xs text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono">
                        #<?php echo $user['id']; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-primary text-white flex items-center justify-center text-xs font-bold">
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            </div>
                            <span class="font-medium text-slate-800 dark:text-white"><?php echo htmlspecialchars($user['name']); ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                        <?php echo htmlspecialchars($user['email']); ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400 rounded-lg text-xs font-semibold">
                                <i class="fas fa-shield-alt mr-1"></i> Admin
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-lg text-xs font-medium">
                                <i class="fas fa-user mr-1"></i> User
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                        <?php echo date('M d, Y h:i A', strtotime($user['created_at'])); ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-2 opacity-50 cursor-not-allowed" title="Feature coming soon">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once 'views/layouts/app.php'; 
?>
