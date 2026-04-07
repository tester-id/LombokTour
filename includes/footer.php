<footer class="bg-white border-t mt-auto">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <a href="#" class="text-xl font-bold flex items-center justify-center md:justify-start gap-2 text-brand-teal mb-1">
                    <i class="fa-solid fa-umbrella-beach text-amber-500"></i> LombokTour
                </a>
                <p class="text-gray-500 text-sm">Destinasi wisata terbaik di Pulau Lombok.</p>
            </div>

            <div class="flex gap-4 text-gray-400">
                <a href="#" class="hover:text-brand-teal transition"><i class="fa-brands fa-instagram fa-lg"></i></a>
                <a href="#" class="hover:text-brand-teal transition"><i class="fa-brands fa-facebook fa-lg"></i></a>
                <a href="#" class="hover:text-brand-teal transition"><i class="fa-brands fa-twitter fa-lg"></i></a>
            </div>
            
            <div class="text-center md:text-right text-sm text-gray-500">
                <p>&copy; <?php echo date('Y'); ?> LombokTour.</p>
                <p class="text-xs mt-1">Dibuat dengan <i class="fa-solid fa-heart text-red-500"></i> untuk Pariwisata.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts if needed -->
<?php
if (!isset($base)) {
    $current_path = $_SERVER['PHP_SELF'];
    $path_parts = explode('/', trim($current_path, '/'));
    // Handle cases where array_search might return false separately if needed, 
    // but assuming standard structure:
    $project_index = array_search('lomboktour', $path_parts);
    if ($project_index !== false) {
        $depth = count($path_parts) - $project_index - 2;
        $base = "";
        for($i = 0; $i < $depth; $i++) { $base .= "../"; }
    } else {
        $base = "../"; // Default fallback
    }
}
?>
<script src="<?php echo $base; ?>public/js/script.js"></script>
</body>
</html>
