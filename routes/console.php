<?php

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('dev:csp-check {--path=/ : Request path (use --path=/about; avoid bare / in Git Bash)} {--force : Run even when APP_ENV is not local/testing}', function () {
    $path = (string) $this->option('path');
    if ($path === '' || str_contains($path, 'Program Files')) {
        $path = '/';
    }
    if (! in_array(app()->environment(), ['local', 'testing'], true) && ! $this->option('force')) {
        $this->error('Refusing to run outside local/testing. Pass --force if you mean to run this in production.');

        return Command::FAILURE;
    }

    $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
    $request = Request::create($path, 'GET');
    $response = $kernel->handle($request);

    $csp = $response->headers->get('Content-Security-Policy');
    $reportOnly = $response->headers->get('Content-Security-Policy-Report-Only');

    $this->line('Internal request: GET '.$path);
    $this->line('Content-Security-Policy: '.($csp ?? '(none — Laravel did not set this)'));
    $this->line('Content-Security-Policy-Report-Only: '.($reportOnly ?? '(none)'));

    $kernel->terminate($request, $response);

    if ($csp !== null || $reportOnly !== null) {
        $this->warn('Laravel attached a CSP header to this response. Inspect middleware and global response callbacks.');
    } else {
        $this->info('OK: Laravel did not attach CSP. Browser console CSP errors are from another source (proxy, <meta>, extension, or in-editor browser).');
    }

    return Command::SUCCESS;
})->purpose('Runtime check: does Laravel set Content-Security-Policy on a response?');
