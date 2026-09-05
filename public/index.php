<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
// Strip base path if present (e.g. /BrandBuzz-Agency or /public)
$base = base_path();
if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}
$path = '/' . ltrim($path, '/');
if (str_starts_with($path, '/public/')) {
    $path = substr($path, 7);
}

// Clean routing dispatch
if ($path === '/' || $path === '/index.php' || $path === '/home') {
    HomeController::index();
    exit;
}
if ($path === '/about' || $path === '/about.php') {
    HomeController::about();
    exit;
}
if ($path === '/contact' || $path === '/contact.php') {
    ContactController::index();
    exit;
}
if ($path === '/services' || $path === '/services.php') {
    ServiceController::index();
    exit;
}
if (preg_match('#^/services/([a-zA-Z0-9_\-]+)$#', $path, $m)) {
    $_GET['slug'] = $m[1];
    ServiceController::index();
    exit;
}
if ($path === '/portfolio' || $path === '/portfolio.php') {
    ProjectController::index();
    exit;
}
if (preg_match('#^/portfolio/([a-zA-Z0-9_\-]+)$#', $path, $m)) {
    $_GET['slug'] = $m[1];
    ProjectController::index();
    exit;
}
if ($path === '/blog' || $path === '/blog.php') {
    BlogController::index();
    exit;
}
if (preg_match('#^/blog/([a-zA-Z0-9_\-]+)$#', $path, $m)) {
    $_GET['slug'] = $m[1];
    BlogController::index();
    exit;
}
if ($path === '/install' || $path === '/install.php') {
    require ROOT_PATH . '/install.php';
    exit;
}
if (str_starts_with($path, '/admin')) {
    $adminFile = ROOT_PATH . $path;
    if (is_dir($adminFile)) $adminFile = rtrim($adminFile, '/') . '/index.php';
    if (!str_ends_with($adminFile, '.php') && is_file($adminFile . '.php')) $adminFile .= '.php';
    if (is_file($adminFile)) {
        $_SERVER['SCRIPT_NAME'] = str_replace(ROOT_PATH, '', $adminFile);
        require $adminFile;
        exit;
    }
}

// Default fallback
not_found();
