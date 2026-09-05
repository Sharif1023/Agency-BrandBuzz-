<?php
declare(strict_types=1);
define('ROOT_PATH', dirname(__DIR__, 2));
// No Composer dependency is needed. Environment variables override .env values.
if (is_file(ROOT_PATH . '/.env')) {
    foreach (file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key); $value = trim($value);
        if (strlen($value) >= 2 && (($value[0] === '"' && str_ends_with($value, '"')) || ($value[0] === "'" && str_ends_with($value, "'")))) $value = substr($value, 1, -1);
        if (preg_match('/^[A-Z][A-Z0-9_]*$/', $key) && getenv($key) === false) putenv($key . '=' . $value);
    }
}
function env(string $key, string $default = ''): string { $value = getenv($key); return $value === false ? $default : $value; }
define('APP_ENV', env('APP_ENV', 'production'));
define('UPLOAD_DIR', ROOT_PATH . '/public_assets/uploads/');
define('UPLOAD_LIMIT', max(1, min(20, (int) env('UPLOAD_MAX_MB', '5'))) * 1024 * 1024);
define('SESSION_TIMEOUT', max(300, (int) env('SESSION_TIMEOUT', '3600')));
date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Dhaka'));
