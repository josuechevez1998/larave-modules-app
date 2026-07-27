#!/usr/bin/env bash
cd /home/guanacodev/modular-app
./vendor/bin/sail artisan view:clear >/dev/null
./vendor/bin/sail php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
auth()->loginUsingId(1);
$html = view("dashboard")->render();
$out = [];
$out[] = (str_contains($html, "ui-sidebar") || str_contains($html, "data-flux-sidebar") ? "HAS_SIDEBAR" : "NO_SIDEBAR");
$out[] = (str_contains($html, "Dashboard") ? "HAS_DASHBOARD" : "NO_DASHBOARD");
$out[] = strlen($html)." bytes";
file_put_contents("layout-smoke.txt", implode(PHP_EOL, $out).PHP_EOL);
echo implode(PHP_EOL, $out).PHP_EOL;
'
