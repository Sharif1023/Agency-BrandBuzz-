<?php
declare(strict_types=1);
function e(mixed $value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function input(string $key, mixed $default = ''): string { $v = $_POST[$key] ?? $default; return is_scalar($v) ? trim((string)$v) : ''; }
function password_input(string $key): string { $value = $_POST[$key] ?? ''; return is_string($value) ? $value : ''; }
function query(string $key, string $default = ''): string { $v = $_GET[$key] ?? $default; return is_scalar($v) ? trim((string)$v) : $default; }
function base_path(): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    foreach (['/public/', '/admin/'] as $segment) { $pos = strpos($script, $segment); if ($pos !== false) return substr($script, 0, $pos); }
    $dir = str_replace('\\', '/', dirname($script));
    return $dir === '/' || $dir === '.' ? '' : rtrim($dir, '/');
}
function url(string $path = ''): string {
    $base = env('APP_URL');
    if ($base === '') $base = base_path();
    $base = rtrim($base, '/');
    $trimmed = ltrim($path, '/');
    return $trimmed === '' ? ($base !== '' ? $base . '/' : '/') : ($base !== '' ? $base . '/' . $trimmed : '/' . $trimmed);
}
function page_url(string $page = 'index', array $params = []): string {
    $page = trim($page, '/');
    if ($page === '' || $page === 'index') {
        $path = '';
    } else {
        $path = preg_replace('/\.php$/i', '', $page);
    }
    $query = $params ? '?' . http_build_query($params) : '';
    return url($path) . $query;
}
function asset(string $path): string { return url('public_assets/' . ltrim($path, '/')); }
function media(?string $path, string $fallback = 'images/agency.webp'): string {
    $path = $path ?: $fallback;
    if (!preg_match('#^(images|uploads)/[a-zA-Z0-9_.\-/]+$#', $path) || str_contains($path, '..')) $path = $fallback;
    return asset($path);
}
function redirect(string $location): never { header('Location: ' . $location, true, 303); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'][] = ['type' => $type, 'message' => $message]; }
function flashes(): array { $messages = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $messages; }
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void {
    if (APP_ENV === 'local') return;
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], input('_token'))) { http_response_code(419); render_error('Your session expired', 'Go back, refresh the page, and submit the form again.'); exit; }
}
function view(string $name, array $data = []): void { extract($data, EXTR_SKIP); require ROOT_PATH . '/resources/views/' . $name . '.php'; }
function setting(string $key, string $fallback = ''): string { return Setting::get($key, $fallback); }
function slugify(string $text): string { $text = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($text))); return trim($text, '-') ?: 'item'; }
function paragraphs(string $text): string {
    return implode('', array_map(fn($p) => '<p>' . nl2br(e(trim($p))) . '</p>', preg_split('/\R\s*\R/', trim($text)) ?: []));
}
function str_length(string $text): int { return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text); }
function valid_email(string $email): bool { return strlen($email) <= 190 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false; }
function safe_external(string $value): bool { return $value === '' || (strlen($value) <= 500 && filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['https','http'], true)); }
function render_error(string $title, string $message): void {
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . e($title) . ' · BrandBuzz</title><link rel="stylesheet" href="' . e(asset('css/tailwind.css')) . '"><body class="bg-peach"><main class="container-page py-24"><div class="card max-w-xl mx-auto p-10"><a class="brand" href="' . e(page_url()) . '">BrandBuzz<span>.</span></a><h1 class="text-3xl font-bold mt-8">' . e($title) . '</h1><p class="text-muted mt-4">' . e($message) . '</p><a class="btn btn-primary mt-8" href="' . e(page_url()) . '">Back to home</a></div></main></body></html>';
}
function not_found(): never { http_response_code(404); render_error('Page not found', 'This page may have moved or is no longer published.'); exit; }
function public_page(string $template, array $data = []): void {
    view('layouts/header', $data); view('layouts/navbar', $data); echo '<main id="main-content">'; view($template, $data); echo '</main>'; view('layouts/footer', $data);
}
function badge(string $status): string { $class = match($status) { 'published','active','read' => 'badge-green', 'draft','new' => 'badge-orange', default => 'badge-gray' }; return '<span class="badge ' . $class . '">' . e(ucfirst($status)) . '</span>'; }
function icon(string $name, string $class = 'w-5 h-5'): string {
    $paths = [
      'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>', 'chevron' => '<path d="m9 5 7 7-7 7"/>',
      'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
      'megaphone' => '<path d="m3 10 15-6v16L3 14zM6 15l2 6h3l-2-5M21 8v8"/>',
      'sparkles' => '<path d="m12 3 2.8 6.2L21 12l-6.2 2.8L12 21l-2.8-6.2L3 12l6.2-2.8zM20 2v4M18 4h4"/>',
      'code' => '<path d="m8 6-6 6 6 6m8-12 6 6-6 6m-3-15-2 18"/>',
      'check' => '<path d="m5 12 4 4L19 6"/>', 'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
      'close' => '<path d="m6 6 12 12M6 18 18 6"/>', 'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/>',
      'phone' => '<path d="m5 3 4 1 1 5-3 2a16 16 0 0 0 6 6l2-3 5 1 1 4c0 2-3 3-5 2C9 19 5 15 3 8 2 6 3 3 5 3Z"/>',
      'pin' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
      'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
      'folder' => '<path d="M3 7V5a2 2 0 0 1 2-2h5l2 3h7a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/>',
      'file' => '<path d="M14 2H5v20h14V7zM14 2v5h5M8 12h8M8 16h8"/>',
      'settings' => '<path d="M4 6h16M4 12h16M4 18h16"/><circle cx="8" cy="6" r="2"/><circle cx="16" cy="12" r="2"/><circle cx="10" cy="18" r="2"/>',
      'logout' => '<path d="M9 4H4v16h5m6-13 5 5-5 5M8 12h12"/>',
      'edit' => '<path d="m16 3 5 5-12 12-6 1 1-6Z M13 6l5 5"/>',
      'trash' => '<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7M14 10v7"/>',
      'plus' => '<path d="M12 4v16M4 12h16"/>', 'eye' => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
      'lock' => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4M12 14v3"/>',
      'star' => '<path d="m12 2 3 6.4 7 .9-5.1 4.9 1.2 6.9L12 18l-6.1 3.1 1.2-6.9L2 9.3l7-.9Z"/>',
    ];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['sparkles']) . '</svg>';
}
