<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('deploy {--quick} {--skip-frontend} {--skip-admin} {--fresh} {--no-down}', function () {
    $params = [];
    if ($this->option('quick')) $params['--quick'] = true;
    if ($this->option('skip-frontend')) $params['--skip-frontend'] = true;
    if ($this->option('skip-admin')) $params['--skip-admin'] = true;
    if ($this->option('fresh')) $params['--fresh'] = true;
    if ($this->option('no-down')) $params['--no-down'] = true;

    return $this->call('app:deploy', $params);
})->purpose('Deploy the unified KNOTELLE application (Alias for app:deploy)');
