<?php
declare(strict_types=1);
function current_user(): ?array { if (empty($_SESSION['user_id'])) return null; return User::find((int)$_SESSION['user_id']); }
function require_auth(): array {
    if (!empty($_SESSION['last_activity']) && time() - (int)$_SESSION['last_activity'] > SESSION_TIMEOUT) { unset($_SESSION['user_id'], $_SESSION['last_activity']); flash('error', 'Your session expired. Please sign in again.'); }
    $user = current_user();
    if (!$user || $user['role'] !== 'admin' || (int)($_SESSION['session_version'] ?? 0) !== (int)$user['session_version']) { unset($_SESSION['user_id']); redirect(url('admin/login.php')); }
    $_SESSION['last_activity'] = time();
    header('Cache-Control: no-store, private');
    return $user;
}
function login_user(array $user): void { session_regenerate_id(true); $_SESSION['user_id'] = (int)$user['id']; $_SESSION['session_version'] = (int)$user['session_version']; $_SESSION['session_version'] = (int)$user['session_version']; $_SESSION['last_activity'] = time(); $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
function logout_user(): void { $_SESSION = []; session_regenerate_id(true); }
// Persistent file-based counters prevent resetting a session to bypass throttling.
// Production keying uses REMOTE_ADDR; never trust arbitrary forwarded headers.
function rate_limit(string $scope, int $limit, int $window): bool {
    $directory = ROOT_PATH . '/storage/rate-limits';
    if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) throw new RuntimeException('Storage directory is not writable.');
    $path = $directory . '/' . hash('sha256', $scope . '|' . ($_SERVER['REMOTE_ADDR'] ?? 'cli')) . '.json';
    $file = fopen($path, 'c+');
    if (!$file || !flock($file, LOCK_EX)) throw new RuntimeException('Rate limit storage unavailable.');
    try {
        $record = json_decode(stream_get_contents($file) ?: '{}', true) ?: [];
        if (($record['reset'] ?? 0) <= time()) $record = ['reset' => time() + $window, 'count' => 0];
        $allowed = $record['count'] < $limit;
        if ($allowed) $record['count']++;
        rewind($file); ftruncate($file, 0); fwrite($file, json_encode($record, JSON_THROW_ON_ERROR)); fflush($file);
        return $allowed;
    } finally { flock($file, LOCK_UN); fclose($file); }
}
