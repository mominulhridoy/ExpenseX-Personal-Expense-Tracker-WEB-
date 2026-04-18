<?php 
ob_start(); 
?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Transactions</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage your income and expenses</p>
    </div>
    <div class="flex items-center space-x-3 w-full md:w-auto">
        <button onclick="document.getElementById('filter-menu').classList.toggle('hidden')" class="px-4 py-2 bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm flex items-center space-x-2">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
        </button>
        <a href="index.php?route=transactions_add" class="flex-1 md:flex-none px-4 py-2 bg-primary hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md shadow-indigo-200 dark:shadow-none flex items-center justify-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add New</span>
        </a>
    </div>
</div>

<div id="filter-menu" class="bg-white dark:bg-dark-card p-5 rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border mb-6 <?php echo empty($_GET['search']) && empty($_GET['type']) && empty($_GET['category_id']) && empty($_GET['start_date']) ? 'hidden' : ''; ?> transition-all">
    <form action="index.php" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <input type="hidden" name="route" value="transactions">
        
        <div class="lg:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Search</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <input type="text" name="search" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" placeholder="Search title..." class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-sm dark:text-white">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Type</label>
            <select name="type" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none dark:text-white">
                <option value="">All Types</option>
                <option value="income" <?php echo ($filters['type'] ?? '') === 'income' ? 'selected' : ''; ?>>Income</option>
                <option value="expense" <?php echo ($filters['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>Expense</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Category</label>
            <select name="category_id" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none dark:text-white">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Start Date</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($filters['start_date'] ?? ''); ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-sm dark:text-white">
        </div>

        <div class="lg:col-span-1 flex items-end ml-auto space-x-2 w-full lg:w-auto mt-4 lg:mt-0 lg:ml-0 lg:col-start-5">
            <?php if (!empty(array_filter(array_values($filters ?? [])))): ?>
                <a href="index.php?route=transactions" class="px-4 py-2 text-sm text-slate-500 hover:text-red-500 transition-colors">Clear</a>
            <?php endif; ?>
            <button type="submit" class="w-full lg:w-auto px-6 py-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white rounded-xl text-sm font-medium transition-colors">Apply</button>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-dark-border">
                    <th class="px-6 py-4">Transaction Details</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Amount</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                <?php if (!empty($transactions) && count($transactions) > 0): ?>
                    <?php foreach ($transactions as $tx): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 <?php echo $tx['type'] == 'income' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400'; ?>">
                                        <i class="fas <?php echo $tx['type'] == 'income' ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white"><?php echo htmlspecialchars($tx['title']); ?></p>
                                        <?php if ($tx['is_recurring']): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                <i class="fas fa-sync-alt mr-1"></i> Recurring
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: <?php echo htmlspecialchars($tx['category_color'] ?? '#cbd5e1'); ?>;"></div>
                                    <span class="text-sm text-slate-600 dark:text-slate-400"><?php echo htmlspecialchars($tx['category_name'] ?? 'Uncategorized'); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                <?php echo date('M d, Y', strtotime($tx['transaction_date'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold <?php echo $tx['type'] == 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white'; ?>">
                                    <?php echo $tx['type'] == 'income' ? '+' : '-'; ?>৳<?php echo number_format($tx['amount'], 2); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="index.php?route=transactions_edit&id=<?php echo $tx['id']; ?>" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 bg-slate-50 hover:bg-indigo-50 dark:bg-slate-800 dark:hover:bg-indigo-900/30 rounded-lg transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="index.php?route=transactions_delete" method="POST" class="inline" onsubmit="return confirm('Delete this transaction forever?');">
                                        <?php echo CSRF::getTokenField(); ?>
                                        <input type="hidden" name="id" value="<?php echo $tx['id']; ?>">
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 bg-slate-50 hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mb-4">
                                <i class="fas fa-receipt text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-1">No Transactions Found</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">You haven't added any transactions matching these filters.</p>
                            <a href="index.php?route=transactions_add" class="inline-flex items-center text-sm font-medium text-primary hover:text-indigo-600 dark:hover:text-indigo-400">
                                <i class="fas fa-plus mr-1"></i> Add your first record
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-dark-border flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-sm text-slate-500 dark:text-slate-400">
                Showing <?php echo ($offset ?? 0) + 1; ?> to <?php echo min(($offset ?? 0) + ($limit ?? 10), ($total_records ?? 0)); ?> of <?php echo $total_records ?? 0; ?> entries
            </span>
            <div class="flex items-center space-x-1">
                <?php 
                $queryParams = $_GET;
                for ($i = 1; $i <= $total_pages; $i++): 
                    $queryParams['page'] = $i;
                    $url = 'index.php?' . http_build_query($queryParams);
                    $isActive = ($page ?? 1) == $i;
                ?>
                    <a href="<?php echo $url; ?>" class="px-3 py-1 text-sm rounded-lg <?php echo $isActive ? 'bg-primary text-white font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean(); 
require_once 'views/layouts/app.php'; 
?>