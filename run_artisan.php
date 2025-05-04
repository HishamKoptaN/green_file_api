<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// التحقق من دالة shell_exec
if (!function_exists('shell_exec')) {
    die('shell_exec is not available.');
}

// تحديد مسار مجلد المشروع
$projectDir = __DIR__; // إذا كان السكربت في الجذر
chdir($projectDir);

// تنفيذ أوامر Composer و Artisan
$installOutput = shell_exec('php composer.phar install 2>&1');
echo "<pre>$installOutput</pre>";

$dumpAutoloadOutput = shell_exec('php composer.phar dump-autoload 2>&1');
echo "<pre>$dumpAutoloadOutput</pre>";

$cacheClearOutput = shell_exec('php artisan config:cache 2>&1');
echo "<pre>$cacheClearOutput</pre>";

$routeCacheOutput = shell_exec('php artisan route:cache 2>&1');
echo "<pre>$routeCacheOutput</pre>";

$viewCacheOutput = shell_exec('php artisan view:cache 2>&1');
echo "<pre>$viewCacheOutput</pre>";

$clearCacheOutput = shell_exec('php artisan cache:clear 2>&1');
echo "<pre>$clearCacheOutput</pre>";
?>
