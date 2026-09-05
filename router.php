<?php
// Local PHP development router.
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?: '/');

// Security protections
if (str_contains($path, '..') || str_contains($path, "\0") || str_contains($path, '\\') ||
    preg_match('#(^|/)\.|^/(app|resources|database|storage|tests|tools|node_modules)(/|$)#i', $path)) {
    http_response_code(403);
    exit('Forbidden');
}

// Static asset handling (/public_assets/ and /assets/)
$assetPath = $path;
if (str_starts_with($path, '/assets/')) {
    $assetPath = '/public_assets/' . substr($path, 8);
}
if (str_starts_with($assetPath, '/public_assets/')) {
    $assetFile = __DIR__ . $assetPath;
    if (str_starts_with($assetPath, '/public_assets/uploads/') && !preg_match('/\.(png|jpe?g|webp|gif)$/i', $assetPath)) {
        http_response_code(403);
        exit('Forbidden');
    }
    if (is_file($assetFile) && preg_match('/\.(css|js|woff2?|ttf|png|jpe?g|webp|gif|ico|svg|map)$/i', $assetPath)) {
        return false;
    }
}
if ($path === '/favicon.ico') {
    $fav = __DIR__ . '/public_assets/images/favicon.svg';
    if (is_file($fav)) {
        header('Content-Type: image/svg+xml');
        readfile($fav);
        return true;
    }
}

// 301 Permanent Redirects for legacy URLs
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
if ($path === '/public/index.php' || $path === '/public/') {
    header('Location: /' . $query, true, 301);
    exit;
}
if (preg_match('#^/public/([a-zA-Z0-9_\-]+)\.php$#i', $path, $m)) {
    $target = $m[1] === 'index' ? '/' : '/' . $m[1];
    header('Location: ' . $target . $query, true, 301);
    exit;
}
if (preg_match('#^/([a-zA-Z0-9_\-]+)\.php$#i', $path, $m)) {
    $page = $m[1];
    if (in_array($page, ['about', 'services', 'portfolio', 'blog', 'contact', 'home'], true)) {
        header('Location: /' . $page . $query, true, 301);
        exit;
    }
}

// Clean Public Routing
require_once __DIR__ . '/app/bootstrap.php';

if ($path === '/' || $path === '/index.php') {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    HomeController::index();
    return true;
}
if ($path === '/home') {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    HomeController::index();
    return true;
}
if ($path === '/about') {
    $_SERVER['SCRIPT_NAME'] = '/public/about.php';
    HomeController::about();
    return true;
}
if ($path === '/contact') {
    $_SERVER['SCRIPT_NAME'] = '/public/contact.php';
    ContactController::index();
    return true;
}
if ($path === '/services') {
    $_SERVER['SCRIPT_NAME'] = '/public/services.php';
    ServiceController::index();
    return true;
}
if (preg_match('#^/services/([a-zA-Z0-9_\-]+)$#', $path, $m)) {
    $_SERVER['SCRIPT_NAME'] = '/public/services.php';
    $_GET['slug'] = $m[1];
    ServiceController::index();
    return true;
}
if ($path === '/portfolio') {
    $_SERVER['SCRIPT_NAME'] = '/public/portfolio.php';
    ProjectController::index();
    return true;
}
if (preg_match('#^/portfolio/([a-zA-Z0-9_\-]+)$#', $path, $m)) {
    $_SERVER['SCRIPT_NAME'] = '/public/portfolio.php';
    $_GET['slug'] = $m[1];
    ProjectController::index();
    return true;
}
if ($path === '/blog') {
    $_SERVER['SCRIPT_NAME'] = '/public/blog.php';
    BlogController::index();
    return true;
}
if (preg_match('#^/blog/([a-zA-Z0-9_\-]+)$#', $path, $m)) {
    $_SERVER['SCRIPT_NAME'] = '/public/blog.php';
    $_GET['slug'] = $m[1];
    BlogController::index();
    return true;
}
if ($path === '/install' || $path === '/install.php') {
    $_SERVER['SCRIPT_NAME'] = '/install.php';
    require __DIR__ . '/install.php';
    return true;
}

// Admin routes
if (str_starts_with($path, '/admin')) {
    $adminPath = __DIR__ . $path;
    if (is_dir($adminPath)) $adminPath = rtrim($adminPath, '/') . '/index.php';
    if (!str_ends_with($adminPath, '.php') && is_file($adminPath . '.php')) $adminPath .= '.php';
    if (is_file($adminPath)) {
        $_SERVER['SCRIPT_NAME'] = str_replace(__DIR__, '', $adminPath);
        require $adminPath;
        return true;
    }
}

// 404 Fallback
not_found();

