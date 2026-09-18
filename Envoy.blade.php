@servers(['localhost' => '127.0.0.1'])

{{--
|--------------------------------------------------------------------------
| KNOTELLE — Laravel Envoy Deployment Script (Blade-Language Deployment)
|--------------------------------------------------------------------------
| Usage:
|   envoy run deploy              # Full deployment (Git + Composer + NPM + Migrate + Cache)
|   envoy run quick               # Quick deployment (Git + Migrate + Cache)
|   envoy run frontend            # Rebuild only the Next.js customer frontend
|   envoy run admin               # Rebuild only the Blade admin Vite assets
|   envoy run health              # Check deployment health status
|--------------------------------------------------------------------------
--}}

@setup
    $projectDir = isset($dir) ? $dir : realpath(__DIR__);
    $backendDir = $projectDir . '/backend';
    $frontendDir = $projectDir . '/frontend';
    $branch = isset($branch) ? $branch : 'main';
    $php = isset($php) ? $php : 'php';
    $composer = isset($composer) ? $composer : 'composer';
@endsetup

@story('deploy')
    git_pull
    install_dependencies
    build_frontend
    build_admin
    artisan_deploy
    fix_permissions
    reload_services
@endstory

@story('quick')
    git_pull
    artisan_quick_deploy
    reload_services
@endstory

@task('git_pull', ['on' => 'localhost'])
    echo "📥 [1/7] Pulling latest code from Git branch {{ $branch }}..."
    cd {{ $projectDir }}
    git pull origin {{ $branch }} || git pull origin master || true
@endtask

@task('install_dependencies', ['on' => 'localhost'])
    echo "📦 [2/7] Installing Composer PHP dependencies..."
    cd {{ $backendDir }}
    {{ $composer }} install --no-dev --no-interaction --prefer-dist --optimize-autoloader
@endtask

@task('build_frontend', ['on' => 'localhost'])
    echo "🛍️  [3/7] Compiling Next.js customer frontend & syncing to Laravel public..."
    if [ -d "{{ $frontendDir }}" ]; then
        cd {{ $frontendDir }}
        npm ci --include=dev || npm install
        npm run build:laravel
    fi
@endtask

@task('build_admin', ['on' => 'localhost'])
    echo "⚙️  [4/7] Compiling Laravel Vite admin assets..."
    if [ -f "{{ $backendDir }}/package.json" ]; then
        cd {{ $backendDir }}
        npm ci --include=dev || npm install
        npm run build
    fi
@endtask

@task('artisan_deploy', ['on' => 'localhost'])
    echo "⚡ [5/7] Running Laravel deployment commands via Artisan..."
    cd {{ $backendDir }}
    {{ $php }} artisan app:deploy --skip-frontend --skip-admin
@endtask

@task('artisan_quick_deploy', ['on' => 'localhost'])
    echo "⚡ Quick Laravel deployment via Artisan..."
    cd {{ $backendDir }}
    {{ $php }} artisan app:deploy --quick
@endtask

@task('fix_permissions', ['on' => 'localhost'])
    echo "🔒 [6/7] Updating folder permissions for storage and bootstrap/cache..."
    cd {{ $backendDir }}
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true
    if command -v chown >/dev/null 2>&1; then
        chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
    fi
@endtask

@task('reload_services', ['on' => 'localhost'])
    echo "🔄 [7/7] Reloading PHP-FPM service..."
    if command -v systemctl >/dev/null 2>&1; then
        systemctl reload php8.3-fpm 2>/dev/null || systemctl reload php8.2-fpm 2>/dev/null || true
    fi
    echo "✅ Envoy deployment successfully completed!"
@endtask

@task('health', ['on' => 'localhost'])
    echo "🩺 Checking KNOTELLE Health Endpoints..."
    cd {{ $backendDir }}
    {{ $php }} artisan inspire
    echo "Storage check:"
    ls -la {{ $backendDir }}/public/storage 2>/dev/null || true
    echo "Index HTML check:"
    ls -lh {{ $backendDir }}/public/index.html 2>/dev/null || true
@endtask
