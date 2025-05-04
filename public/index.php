<?php
echo 'PHP version: ' . phpversion();
echo '<br>';
echo 'Available functions: ';
print_r(get_defined_functions());
?>
<!--
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ⚠️ مؤقتًا فقط لمسح الكاش
if (isset($_GET['run_cache_clear']) && $_GET['run_cache_clear'] === '1') {
    echo "<pre>";
    echo "Running artisan commands...\n";
    echo shell_exec('php artisan route:clear');
    echo shell_exec('php artisan config:clear');
    echo shell_exec('php artisan cache:clear');
    echo shell_exec('php artisan optimize:clear');
    echo shell_exec('composer dump-autoload');
    echo "</pre>";
    exit; // لا تكمل تحميل التطبيق بعد تنفيذ الأوامر
} -->
