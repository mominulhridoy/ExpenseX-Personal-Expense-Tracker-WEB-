<?php ob_start(); ?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Budgets</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage and track your monthly spending limits</p>
    </div>
    
    <!-- Month/Year Picker Form -->
    <form action="index.php" method="GET" class="flex items-center space-x-3 bg-white dark:bg-dark-card p-1.5 rounded-xl border border-slate-200 dark:border-dark-border shadow-sm">
        <input type="hidden" name="route" value="budgets">
        <select name="month" class="px-3 py-1.5 bg-transparent text-sm font-medium text-slate-700 dark:text-slate-300 focus:outline-none appearance-none cursor-pointer" onchange="this.form.submit()">
            <?php 
            for ($m = 1; $m <= 12; $m++) {
                $mName = date('F', mktime(0, 0, 0, $m, 1));
                $selected = ($m == $month) ? 'selected' : '';
                echo "<option value='$m' $selected>$mName</option>";
            }
            ?>
        </select>
        <div class="w-px h-5 bg-slate-200 dark:bg-dark-border"></div>
        <select name="year" class="px-3 py-1.5 bg-transparent text-sm font-medium text-slate-700 dark:text-slate-300 focus:outline-none appearance-none cursor-pointer" onchange="this.form.submit()">
            <?php 
            $currentY = date('Y');
            for ($y = $currentY - 2; $y <= $currentY + 2; $y++) {
                $selected = ($y == $year) ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Budget Setup Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border overflow-hidden sticky top-24">
            <div class="p-6 border-b border-slate-100 dark:border-dark-border">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Set Budget</h3>
            </div>
            
            <form action="index.php?route=budgets_store" method="POST" class="p-6 space-y-5" onsubmit="window.showLoader()">
                <?php echo CSRF::getTokenField(); ?>
                <input type="hidden" name="month" value="<?php echo $month; ?>">
                <input type="hidden" name="year" value="<?php echo $year; ?>">

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-slate-900 dark:text-white appearance-none">
                        <option value="">Select Expense Category...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Limit Amount (৳) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500 dark:text-slate-400 font-bold">৳</span>
                        </div>
                        <input type="number" step="0.01" min="1" name="amount" required class="w-full pl-8 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-dark-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-slate-900 dark:text-white" placeholder="0.00">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-primary hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors shadow-md shadow-indigo-200 dark:shadow-none">Save Budget Limit</button>
            </form>
        </div>
    </div>

    <!-- Active Budgets List -->
    <div class="lg:col-span-2 space-y-6">
        <?php if (empty($budgets)): ?>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full mb-4">
                    <i class="fas fa-bullseye text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No Budgets Set</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Set up your first category budget for <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?> using the form to start tracking your spending limits.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($budgets as $budget): 
                    $percent = ($budget['budget_amount'] > 0) ? ($budget['spent_amount'] / $budget['budget_amount']) * 100 : 0;
                    $isOver = $percent > 100;
                    $isWarning = $percent > 85 && !$isOver;
                    
                    $barColor = 'bg-primary dark:bg-indigo-500';
                    $textColor = 'text-primary dark:text-indigo-400';
                    if ($isOver) {
                        $barColor = 'bg-red-500';
                        $textColor = 'text-red-500 dark:text-red-400';
                    } elseif ($isWarning) {
                        $barColor = 'bg-amber-500';
                        $textColor = 'text-amber-500 dark:text-amber-400';
                    }
                    
                    // Cap visuals at 100% for the bar
                    $visualPercent = min($percent, 100);
                ?>
                    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border <?php echo $isOver ? 'border-red-200 dark:border-red-900/50 relative overflow-hidden' : 'border-slate-100 dark:border-dark-border'; ?> p-6">
                        
                        <?php if ($isOver): ?>
                            <!-- Subtle red glow for over-budget items -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-bl-full pointer-events-none"></div>
                        <?php endif; ?>

                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-<?php echo htmlspecialchars($budget['color']); ?>-100 dark:bg-<?php echo htmlspecialchars($budget['color']); ?>-900/40 text-<?php echo htmlspecialchars($budget['color']); ?>-600 dark:text-<?php echo htmlspecialchars($budget['color']); ?>-400 flex items-center justify-center">
                                    <i class="fas <?php echo htmlspecialchars($budget['icon']); ?>"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-white"><?php echo htmlspecialchars($budget['name']); ?></h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        <?php echo number_format($percent, 1); ?>% spent
                                    </p>
                                </div>
                            </div>
                            
                            <form action="index.php?route=budgets_delete" method="POST" onsubmit="return confirm('Remove budget limit for <?php echo htmlspecialchars($budget['name']); ?>?');">
                                <?php echo CSRF::getTokenField(); ?>
                                <input type="hidden" name="id" value="<?php echo $budget['id']; ?>">
                                <input type="hidden" name="month" value="<?php echo $month; ?>">
                                <input type="hidden" name="year" value="<?php echo $year; ?>">
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Delete Budget">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1.5 font-medium">
                                <span class="text-slate-700 dark:text-slate-300">৳<?php echo number_format($budget['spent_amount'], 2); ?></span>
                                <span class="text-slate-400 dark:text-slate-500">of ৳<?php echo number_format($budget['budget_amount'], 2); ?></span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                <div class="<?php echo $barColor; ?> h-2.5 rounded-full transition-all duration-500 ease-out" style="width: <?php echo $visualPercent; ?>%"></div>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div class="mt-4 text-xs font-medium <?php echo $textColor; ?> flex items-center bg-slate-50 dark:bg-slate-900/50 p-2 rounded-lg">
                            <?php if ($isOver): ?>
                                <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                Exceeded budget by ৳<?php echo number_format($budget['spent_amount'] - $budget['budget_amount'], 2); ?>
                            <?php elseif ($isWarning): ?>
                                <i class="fas fa-exclamation-circle mr-1.5"></i>
                                Nearing limit. ৳<?php echo number_format($budget['budget_amount'] - $budget['spent_amount'], 2); ?> left.
                            <?php else: ?>
                                <i class="fas fa-check-circle mr-1.5"></i>
                                On track. ৳<?php echo number_format($budget['budget_amount'] - $budget['spent_amount'], 2); ?> remaining.
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once 'views/layouts/app.php'; 
?>
