#!/usr/bin/env bash
set -euo pipefail

cd -- "$(dirname -- "${BASH_SOURCE[0]}")"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

fail() {
    printf 'Deployment stopped: %s\n' "$1" >&2
    exit 1
}

[[ -f .env ]] || fail 'Copy .env.production.example to .env and configure it first.'
[[ -f composer.lock ]] || fail 'composer.lock is missing.'
[[ -f public/build/manifest.json ]] || fail 'Upload the prepared public/build files first.'
[[ ! -e public/hot ]] || fail 'Remove the development-only public/hot file before deployment.'
grep -Eq '^APP_ENV=production[[:space:]]*$' .env || fail 'APP_ENV must be production.'
grep -Eq '^APP_DEBUG=false[[:space:]]*$' .env || fail 'APP_DEBUG must be false.'
[[ -w storage && -w bootstrap/cache ]] || fail 'storage and bootstrap/cache must be writable.'
"$PHP_BIN" -r 'exit(version_compare(PHP_VERSION, "8.4.1", ">=") ? 0 : 1);' || fail 'The SSH PHP interpreter must be PHP 8.4.1 or newer.'

"$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --optimize-autoloader
"$COMPOSER_BIN" check-platform-reqs --no-dev
"$PHP_BIN" artisan config:clear --no-interaction

# Generate a key only for a fresh configuration; preserve every existing key.
if "$PHP_BIN" -r 'require "vendor/autoload.php"; $values = Dotenv\Dotenv::parse(file_get_contents(".env")); exit(empty($values["APP_KEY"]) ? 0 : 1);'; then
    "$PHP_BIN" artisan key:generate --force --no-interaction
fi

# Validate the effective configuration without printing secrets.
"$PHP_BIN" <<'PHP'
<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$url = parse_url((string) config('app.url'));
$checks = [
    'APP_ENV must be production.' => $app->environment('production'),
    'APP_DEBUG must be false.' => !config('app.debug'),
    'Configure APP_URL with the HTTPS application address.' => ($url['scheme'] ?? '') === 'https' && !empty($url['host']) && $url['host'] !== 'localhost',
    'APP_KEY is missing.' => !empty(config('app.key')),
    'Use the Cloudways MySQL connection.' => config('database.default') === 'mysql',
    'Configure the Cloudways database name and username.' => !empty(config('database.connections.mysql.database')) && !empty(config('database.connections.mysql.username')),
    'Enable secure session cookies.' => config('session.secure') === true,
];
foreach ($checks as $message => $valid) {
    if (!$valid) {
        fwrite(STDERR, "Deployment stopped: {$message}\n");
        exit(1);
    }
}
try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
} catch (Throwable $exception) {
    fwrite(STDERR, "Deployment stopped: database connection failed. Review the credentials privately.\n");
    exit(1);
}
if (!Illuminate\Support\Facades\Schema::hasTable('products') || !Illuminate\Support\Facades\Schema::hasTable('users')) {
    fwrite(STDERR, "Deployment stopped: import the Natviewer database backup first.\n");
    exit(1);
}
if (!App\Models\User::query()->where('is_admin', true)->exists()) {
    fwrite(STDERR, "Deployment stopped: the imported database needs a Natviewer administrator.\n");
    exit(1);
}
foreach (App\Models\ProductImage::query()->get(['disk', 'path']) as $image) {
    if (!Illuminate\Support\Facades\Storage::disk($image->disk)->exists($image->path)) {
        fwrite(STDERR, "Deployment stopped: upload all catalog images from the prepared backup first.\n");
        exit(1);
    }
}
PHP

# Import the prepared database and uploaded images before running this script.
# No seeders, database resets, or dependency updates run during deployment.
"$PHP_BIN" artisan migrate --force --no-interaction
if [[ -L public/storage ]]; then
    [[ "$(readlink -f public/storage)" == "$(realpath storage/app/public)" ]] || fail 'public/storage points to another directory.'
else
    [[ ! -e public/storage ]] || fail 'public/storage already exists as a regular file or directory.'
    "$PHP_BIN" artisan storage:link --no-interaction
fi
"$PHP_BIN" artisan optimize --no-interaction

printf 'Laravel deployment preparation completed. Verify HTTPS, login, images, and quote requests before launch.\n'
