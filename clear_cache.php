<?php
// تأكد من أن هذه الأوامر يتم تنفيذها في بيئة آمنة، ويجب أن تتم من قبل شخص موثوق به فقط!
$output = [];
$output[] = shell_exec('php /home/u286008865/domains/greenfile.site/public_html/api/artisan route:clear');
$output[] = shell_exec('php /home/u286008865/domains/greenfile.site/public_html/api/artisan config:clear');
$output[] = shell_exec('php /home/u286008865/domains/greenfile.site/public_html/api/artisan cache:clear');
$output[] = shell_exec('php /home/u286008865/domains/greenfile.site/public_html/api/artisan optimize:clear');
$output[] = shell_exec('composer dump-autoload');
echo '<pre>';
print_r($output);
echo '</pre>';
?>
