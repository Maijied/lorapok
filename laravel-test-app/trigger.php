<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$m = app('execution-monitor');
$m->startRoute('test-route');
$m->endRoute('test-route', 3);
$m->checkThresholds();
echo "done\n";
