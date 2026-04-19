<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = \App\Models\Setting::first();
echo "--- KEYWORDS ---\n";
print_r($s->trending_keywords);
echo "\n--- HEADER ---\n";
print_r($s->header_navigation);
echo "\n--- FOOTER ---\n";
print_r($s->footer_navigation);
