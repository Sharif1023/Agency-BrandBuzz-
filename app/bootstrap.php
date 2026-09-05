<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli' && ob_get_level() === 0) ob_start();
require_once __DIR__ . '/config/constants.php';
ini_set('display_errors', APP_ENV === 'local' ? '1' : '0');
ini_set('log_errors', '1');
if (is_dir(ROOT_PATH . '/storage') && is_writable(ROOT_PATH . '/storage')) ini_set('error_log', ROOT_PATH . '/storage/php-error.log');
error_reporting(E_ALL);
require_once __DIR__ . '/config/database.php';
spl_autoload_register(function(string $class): void {
    if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $class)) return;
    foreach (['models','controllers'] as $directory) { $path = __DIR__ . '/' . $directory . '/' . $class . '.php'; if (is_file($path)) { require_once $path; return; } }
});
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/upload.php';
require_once __DIR__ . '/helpers/seo.php';
if (session_status() === PHP_SESSION_NONE) {
    session_name('brandbuzz_session');
    ini_set('session.use_strict_mode', '1'); ini_set('session.use_only_cookies', '1');
    session_set_cookie_params(['lifetime' => 0, 'path' => parse_url(url(), PHP_URL_PATH) ?: '/', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || str_starts_with(env('APP_URL'), 'https://'), 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data: blob:; style-src 'self'; script-src 'self'; font-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'");
set_exception_handler(function(Throwable $error): void {
    error_log((string)$error);
    while (ob_get_level() > 0) ob_end_clean();
    http_response_code(500);
    $message = APP_ENV === 'local' ? $error->getMessage() : 'We could not load this page. Please try again shortly.';
    render_error('Something went wrong', $message);
});
