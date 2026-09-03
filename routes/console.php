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

Artisan::command('livewire:unpublish {--force : Delete without confirmation}', function () {
    $dir = public_path('vendor/livewire');

    if (! is_dir($dir)) {
        $this->info('No published Livewire assets found. Nothing to do.');

        return Command::SUCCESS;
    }

    if (! $this->option('force') && ! $this->confirm("Delete published Livewire assets in {$dir}?", true)) {
        $this->line('Aborted.');

        return Command::SUCCESS;
    }

    $failed = [];

    foreach ((array) glob($dir.'/*') as $file) {
        if (is_file($file) && ! @unlink($file)) {
            $failed[] = $file;
        }
    }

    @rmdir($dir);
    @rmdir(public_path('vendor'));

    if (is_dir($dir)) {
        $this->error("Could not fully remove {$dir}. Check filesystem ownership/permissions.");
        foreach ($failed as $file) {
            $this->line('  still present: '.$file);
        }

        return Command::FAILURE;
    }

    $this->info('Removed published Livewire assets. Livewire will now serve its JS from the versioned PHP route.');

    return Command::SUCCESS;
})->purpose('Remove stale public/vendor/livewire assets so Livewire serves JS from its own route');
