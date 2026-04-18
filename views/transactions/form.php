<?php
$isEdit = isset($transaction);
$actionRoute = $isEdit ? 'transactions_update' : 'transactions_store';
$pageTitle = $isEdit ? 'Edit Transaction' : 'New Transaction';

// Pre-fill values
$type = $isEdit ? $transaction['type'] : ($_GET['type'] ?? 'expense');
$title = $isEdit ? $transaction['title'] : '';
$amount = $isEdit ? $transaction['amount'] : '';
$category_id = $isEdit ? $transaction['category_id'] : '';
$transaction_date = $isEdit ? $transaction['transaction_date'] : date('Y-m-d');
$note = $isEdit ? $transaction['note'] : '';
$is_recurring = $isEdit ? $transaction['is_recurring'] : 0;

ob_start(); 
?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo $pageTitle; ?></h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Record a financial movement</p>
    </div>
    <a href="index.php?route=transactions" class="px-4 py-2 border border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm text-sm font-medium">
        Back to List
    </a>
</div>

<div class="max-w-3xl bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border overflow-hidden">
    <div class="p-6 md:p-8">
        <form action="index.php?route=<?php echo $actionRoute; ?>" method="POST" onsubmit="window.showLoader()">
            <?php echo CSRF::getTokenField(); ?>
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
            <?php endif; ?>

            <!-- Type Selector Tabs -->
            <div class="flex p-1 mb-8 space-x-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl" role="tablist">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="expense" class="peer sr-only" <?php echo $type === 'expense' ? 'checked' : ''; ?> onchange="toggleCategories('expense')">
                    <div class="w-full text-center py-2.5 text-sm font-medium rounded-lg text-slate-500 dark:text-slate-400 peer-checked:bg-white dark:peer-checked:bg-dark-card peer-checked:text-slate-900 dark:peer-checked:text-white peer-checked:shadow-sm transition-all duration-200">
                        <i class="fas fa-arrow-up text-red-500 mr-2"></i>Expense
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="income" class="peer sr-only" <?php echo $type === 'income' ? 'checked' : ''; ?> onchange="toggleCategories('income')">
                    <div class="w-full text-center py-2.5 text-sm font-medium rounded-lg text-slate-500 dark:text-slate-400 peer-checked:bg-white dark:peer-checked:bg-dark-card peer-checked:text-slate-900 dark:peer-checked:text-white peer-checked:shadow-sm transition-all duration-200">
                        <i class="fas fa-arrow-down text-emerald-500 mr-2"></i>Income
                    </div>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Title / Description <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-slate-900 dark:text-white" placeholder="e.g., Monthly Groceries">
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Amount (৳) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500 dark:text-slate-400 font-bold">৳</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="amount" value="<?php echo htmlspecialchars($amount); ?>" required class="w-full pl-8 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-slate-900 dark:text-white" placeholder="0.00">
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Category <span class="text-red-500">*</span></label>
                    
                    <!-- Expense Categories Select -->
                    <select id="cat_expense" name="category_id_expense" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-slate-900 dark:text-white appearance-none <?php echo $type === 'income' ? 'hidden' : ''; ?>" <?php echo $type === 'expense' ? 'required' : ''; ?>>
                        <option value="">Select an expense category...</option>
                        <?php foreach ($expense_categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($type === 'expense' && $category_id == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Income Categories Select -->
                    <select id="cat_income" name="category_id_income" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-slate-900 dark:text-white appearance-none <?php echo $type === 'expense' ? 'hidden' : ''; ?>" <?php echo $type === 'income' ? 'required' : ''; ?>>
                        <option value="">Select an income category...</option>
                        <?php foreach ($income_categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($type === 'income' && $category_id == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <!-- Hidden actual input to capture the selected one -->
                    <input type="hidden" name="category_id" id="final_category_id" value="<?php echo $category_id; ?>">
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="transaction_date" value="<?php echo htmlspecialchars($transaction_date); ?>" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-slate-900 dark:text-white">
                </div>

                <!-- Recurring Checkbox -->
                <div class="flex items-center h-full pt-6">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_recurring" class="sr-only peer" <?php echo $is_recurring ? 'checked' : ''; ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 dark:peer-focus:ring-primary/30 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">Set as Recurring</span>
                    </label>
                </div>

                <!-- Note -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Note (Optional)</label>
                    <textarea name="note" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-slate-900 dark:text-white placeholder-slate-400" placeholder="Add extra details..."><?php echo htmlspecialchars($note); ?></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end items-center border-t border-slate-100 dark:border-dark-border pt-6">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-indigo-700 text-white font-medium shadow-md shadow-indigo-200 dark:shadow-none transition-all transform hover:-translate-y-0.5">
                    <?php echo $isEdit ? 'Update Transaction' : 'Save Transaction'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCategories(selectedType) {
        const catExpense = document.getElementById('cat_expense');
        const catIncome = document.getElementById('cat_income');
        const hiddenFinal = document.getElementById('final_category_id');

        if (selectedType === 'expense') {
            catExpense.classList.remove('hidden');
            catExpense.setAttribute('required', 'required');
            catIncome.classList.add('hidden');
            catIncome.removeAttribute('required');
            // update hidden value on switch based on what selects have
            hiddenFinal.value = catExpense.value;
        } else {
            catIncome.classList.remove('hidden');
            catIncome.setAttribute('required', 'required');
            catExpense.classList.add('hidden');
            catExpense.removeAttribute('required');
            hiddenFinal.value = catIncome.value;
        }
    }

    // Attach sync events
    document.getElementById('cat_expense').addEventListener('change', function() {
        document.getElementById('final_category_id').value = this.value;
    });
    document.getElementById('cat_income').addEventListener('change', function() {
        document.getElementById('final_category_id').value = this.value;
    });
</script>

<?php 
$content = ob_get_clean(); 
require_once 'views/layouts/app.php'; 
?>
