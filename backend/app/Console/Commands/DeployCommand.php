<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class DeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy
                            {--quick : Skip frontend and admin asset builds}
                            {--skip-frontend : Skip compiling and syncing Next.js customer frontend}
                            {--skip-admin : Skip compiling Vite admin assets}
                            {--fresh : Run migrate:fresh with seeders (Caution: deletes existing data)}
                            {--no-down : Do not toggle maintenance mode during deployment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Orchestrate the complete deployment of the unified KNOTELLE Laravel application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $startTime = microtime(true);

        $this->outputBanner();

        $projectRoot = $this->resolveProjectRoot();
        $backendDir = base_path();
        $frontendDir = $projectRoot . DIRECTORY_SEPARATOR . 'frontend';

        $isQuick = $this->option('quick');
        $skipFrontend = $isQuick || $this->option('skip-frontend');
        $skipAdmin = $isQuick || $this->option('skip-admin');
        $isFresh = $this->option('fresh');
        $noDown = $this->option('no-down');

        $isDown = false;

        try {
            // 1. Maintenance Mode
            if (!$noDown) {
                $this->info('🔒 Step 1/7: Enabling Maintenance Mode...');
                $this->call('down', [
                    '--retry' => 60,
                    '--secret' => 'knotelle-deploy-bypass',
                ]);
                $isDown = true;
                $this->line('   Application is now in maintenance mode (Bypass: /knotelle-deploy-bypass)');
            } else {
                $this->comment('⏭️  Skipping Maintenance Mode (--no-down)');
            }

            // 2. Build Customer Frontend (Next.js -> backend/public)
            if (!$skipFrontend && is_dir($frontendDir) && file_exists($frontendDir . DIRECTORY_SEPARATOR . 'package.json')) {
                $this->info('🛍️  Step 2/7: Compiling Next.js Frontend & Syncing to Laravel Public...');
                $this->runShellProcess('npm run build:laravel', $frontendDir);
                $this->line('   <info>✓</info> Customer storefront exported directly to backend/public/');
            } else {
                $this->comment('⏭️  Skipping Frontend Build');
            }

            // 3. Build Blade Admin Assets (Vite)
            if (!$skipAdmin && file_exists($backendDir . DIRECTORY_SEPARATOR . 'package.json')) {
                $this->info('⚙️  Step 3/7: Compiling Laravel Vite Admin Assets...');
                $this->runShellProcess('npm run build', $backendDir);
                $this->line('   <info>✓</info> Admin panel Vite assets built into backend/public/build/');
            } else {
                $this->comment('⏭️  Skipping Admin Vite Build');
            }

            // 4. Database Migrations
            $this->info('🗄️  Step 4/7: Running Database Migrations...');
            if ($isFresh) {
                $this->warn('   ⚠️ Running migrate:fresh --seed (Database will be reset!)');
                $this->call('migrate:fresh', [
                    '--seed' => true,
                    '--force' => true,
                ]);
            } else {
                $this->call('migrate', [
                    '--force' => true,
                ]);
            }
            $this->line('   <info>✓</info> Database schema up to date.');

            // 5. Storage Symlink
            $this->info('🔗 Step 5/7: Verifying Storage Symlink...');
            $publicStorage = public_path('storage');
            if (is_link($publicStorage) || (file_exists($publicStorage) && !is_dir($publicStorage))) {
                $this->line('   <info>✓</info> Storage symlink already configured.');
            } else {
                $this->call('storage:link');
                $this->line('   <info>✓</info> Storage symlink created.');
            }

            // 6. Cache Clearing & Optimization
            $this->info('⚡ Step 6/7: Optimizing Laravel Caches...');
            $this->call('optimize:clear');
            $this->call('config:cache');
            $this->call('route:cache');
            $this->call('view:cache');
            $this->call('event:cache');
            $this->line('   <info>✓</info> Configuration, routes, views, and events cached.');

            // 7. Restart Queue Workers
            $this->info('🔄 Step 7/7: Restarting Background Queue Workers...');
            $this->call('queue:restart');
            $this->line('   <info>✓</info> Queue worker restart signal sent.');

            // Self-Test / Health Check
            $this->runHealthVerification();

        } catch (\Throwable $e) {
            $this->error("\n❌ Deployment halted due to an unexpected error:");
            $this->error($e->getMessage());
            return Command::FAILURE;
        } finally {
            if ($isDown) {
                $this->info("\n🔓 Restoring Application from Maintenance Mode...");
                $this->call('up');
                $this->line('   Application is live!');
            }
        }

        $duration = round(microtime(true) - $startTime, 2);
        $this->outputSuccess($duration);

        return Command::SUCCESS;
    }

    /**
     * Resolve root directory containing both backend and frontend.
     */
    protected function resolveProjectRoot(): string
    {
        $base = base_path();
        $parent = dirname($base);

        if (is_dir($parent . DIRECTORY_SEPARATOR . 'frontend')) {
            return $parent;
        }

        if (is_dir($base . DIRECTORY_SEPARATOR . 'frontend')) {
            return $base;
        }

        return $parent;
    }

    /**
     * Run shell process cross-platform with real-time output.
     */
    protected function runShellProcess(string $command, string $cwd): void
    {
        $this->line("   Executing: <comment>{$command}</comment> in {$cwd}");

        $process = Process::fromShellCommandline($command, $cwd);
        $process->setTimeout(600); // 10 minutes timeout for builds

        $process->run(function ($type, $buffer) {
            if ($this->output->isVerbose()) {
                $this->output->write($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                "Command failed: [{$command}]\n" . $process->getErrorOutput() . "\n" . $process->getOutput()
            );
        }
    }

    /**
     * Run internal health and readiness checks.
     */
    protected function runHealthVerification(): void
    {
        $this->info("\n🩺 Running Post-Deployment Health Check...");

        // 1. Database Connection
        try {
            DB::connection()->getPdo();
            $this->line('   <info>✓</info> Database connection: OK');
        } catch (\Throwable $e) {
            $this->warn('   ⚠️ Database connection check failed: ' . $e->getMessage());
        }

        // 2. Storage Directory Writable
        $storagePath = storage_path('framework/views');
        if (is_writable($storagePath)) {
            $this->line('   <info>✓</info> Storage permissions: OK (Writable)');
        } else {
            $this->warn('   ⚠️ Storage directory is not writable: ' . $storagePath);
        }

        // 3. Customer Homepage Static File
        $indexHtml = public_path('index.html');
        if (file_exists($indexHtml)) {
            $this->line('   <info>✓</info> Customer storefront static bundle: Present');
        } else {
            $this->warn('   ⚠️ public/index.html not found! Run frontend build to generate storefront.');
        }
    }

    /**
     * Output styled CLI banner.
     */
    protected function outputBanner(): void
    {
        $this->newLine();
        $this->line('<fg=cyan;options=bold>====================================================================</>');
        $this->line('<fg=magenta;options=bold>  KNOTELLE — Laravel Native Unified Deployment Engine</>');
        $this->line('<fg=cyan;options=bold>====================================================================</>');
        $this->line('  Environment: <comment>' . app()->environment() . '</comment>');
        $this->line('  Laravel    : <comment>v' . app()->version() . '</comment>');
        $this->line('  PHP        : <comment>v' . PHP_VERSION . '</comment>');
        $this->line('  Base Path  : <comment>' . base_path() . '</comment>');
        $this->line('<fg=cyan;options=bold>--------------------------------------------------------------------</>');
        $this->newLine();
    }

    /**
     * Output styled success summary.
     */
    protected function outputSuccess(float $duration): void
    {
        $this->newLine();
        $this->line('<fg=green;options=bold>====================================================================</>');
        $this->line("<fg=green;options=bold>  ✅ DEPLOYMENT COMPLETED SUCCESSFULLY in {$duration}s</>");
        $this->line('<fg=green;options=bold>====================================================================</>');
        $this->line('  🛍️  Storefront: ' . config('app.url') . '/');
        $this->line('  🔐 Admin     : ' . config('app.url') . '/admin/login');
        $this->line('  🩺 Health    : ' . config('app.url') . '/up');
        $this->newLine();
    }
}
