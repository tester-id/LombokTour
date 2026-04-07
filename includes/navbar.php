<?php
// Tentukan path prefix berdasarkan lokasi file yang memanggil
$current_path = $_SERVER['PHP_SELF'];
$path_parts = explode('/', trim($current_path, '/'));
$project_index = array_search('lomboktour', $path_parts);
$depth = count($path_parts) - $project_index - 2;

$base = "";
for($i = 0; $i < $depth; $i++) {
    $base .= "../";
}
?>
<nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="<?php echo $base; ?>index.php" class="text-2xl font-bold flex items-center gap-2 text-brand-teal hover:text-teal-700 transition">
                <i class="fa-solid fa-umbrella-beach text-amber-500"></i> <span class="tracking-tight">LombokTour</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="flex items-center gap-3 mr-4 text-sm text-gray-600">
                        <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-brand-teal font-bold">
                            <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                        </div>
                        <span class="font-medium"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                    </div>
                    <a href="<?php echo $base; ?>auth/logout.php" class="flex items-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 px-4 py-2 rounded-full transition text-sm font-medium border border-red-100">
                        <i class="fa-solid fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo $base; ?>auth/login.php" class="text-gray-600 hover:text-brand-teal px-3 py-2 rounded-md text-sm font-medium transition">
                        Login
                    </a>
                    <a href="<?php echo $base; ?>auth/register.php" class="bg-brand-teal hover:bg-teal-700 text-white px-5 py-2.5 rounded-full shadow-lg shadow-teal-500/30 transition text-sm font-medium transform hover:-translate-y-0.5">
                        Daftar Sekarang
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="relative w-10 h-10 text-gray-600 focus:outline-none">
                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-6 h-5">
                        <span id="bar-1" class="absolute block w-full h-0.5 bg-current rounded-full transition-all duration-300 ease-in-out -translate-y-2"></span>
                        <span id="bar-2" class="absolute block w-full h-0.5 bg-current rounded-full transition-all duration-300 ease-in-out"></span>
                        <span id="bar-3" class="absolute block w-full h-0.5 bg-current rounded-full transition-all duration-300 ease-in-out translate-y-2"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden overflow-hidden md:hidden bg-white border-t border-gray-100 transition-all duration-300 ease-in-out max-h-0">
        <div class="px-4 pt-2 pb-4 space-y-2 shadow-lg">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="flex items-center gap-3 p-3 border-b border-gray-50 mb-2">
                    <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-brand-teal font-bold">
                        <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                    </div>
                    <span class="font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                </div>
                <a href="<?php echo $base; ?>index.php" class="block text-gray-600 hover:text-brand-teal hover:bg-teal-50 px-3 py-2 rounded-md text-base font-medium">
                    <i class="fa-solid fa-home w-6"></i> Dashboard
                </a>
                <a href="<?php echo $base; ?>auth/logout.php" class="block text-red-600 hover:bg-red-50 px-3 py-2 rounded-md text-base font-medium">
                    <i class="fa-solid fa-sign-out-alt w-6"></i> Logout
                </a>
            <?php else: ?>
                <a href="<?php echo $base; ?>auth/login.php" class="block text-gray-600 hover:text-brand-teal hover:bg-teal-50 px-3 py-2 rounded-md text-base font-medium">
                    Login
                </a>
                <a href="<?php echo $base; ?>auth/register.php" class="block bg-brand-teal text-white px-3 py-2 rounded-md text-base font-medium text-center shadow-md">
                    Daftar Sekarang
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
