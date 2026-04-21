<!-- Header -->
<header class="sticky top-0 z-30 flex items-center justify-between w-full h-16 px-4 sm:px-6 bg-white dark:bg-dark-card border-b border-slate-200 dark:border-dark-border shadow-sm transition-colors duration-200">
    <!-- Mobile menu button -->
    <button class="text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 lg:hidden focus:outline-none p-2" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
        <i class="fas fa-bars text-xl"></i>
    </button>
    
    <h1 class="text-xl font-bold text-slate-800 dark:text-white lg:hidden">Expense<span class="text-primary">X</span></h1>
    
    <div class="flex items-center ml-auto space-x-4">
        <!-- Theme Toggle -->
        <button id="theme-toggle" class="p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 rounded-lg transition-colors focus:outline-none">
            <i class="fas fa-moon dark:hidden text-lg"></i>
            <i class="fas fa-sun hidden dark:block text-lg"></i>
        </button>
        
        <!-- Notifications (Mockup for now) -->
        <button class="relative p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 rounded-lg transition-colors focus:outline-none">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-dark-card"></span>
        </button>

        <!-- Profile Dropdown Container (Hover based for simplicity) -->
        <div class="relative group">
            <button class="flex items-center space-x-2 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors focus:outline-none">
                <?php 
                $profile_img = $_SESSION['user_profile_img'] ?? null;
                if ($profile_img && file_exists('assets/uploads/' . $profile_img)): 
                ?>
                    <img src="assets/uploads/<?php echo htmlspecialchars($profile_img); ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover ring-2 ring-primary/20">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                    </div>
                <?php endif; ?>
                <span class="hidden md:block text-sm font-medium text-slate-700 dark:text-slate-200">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                </span>
                <i class="fas fa-chevron-down text-xs text-slate-400"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div class="absolute right-0 w-48 mt-1 origin-top-right bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="py-1">
                    <div class="px-4 py-2 border-b border-slate-100 dark:border-dark-border">
                        <p class="text-xs text-slate-500 dark:text-slate-400">Signed in as</p>
                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                    </div>
                    <!-- Settings Route Placeholder -->
                    <a href="index.php?route=settings" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-cog w-5 text-center text-slate-400"></i> Settings
                    </a>
                    <a href="index.php?route=logout" class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
