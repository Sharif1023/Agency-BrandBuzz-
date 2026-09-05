<?php
require dirname(__DIR__) . '/app/bootstrap.php';
header('Cache-Control: no-store, private');
$error = ''; $email = '';
if (current_user()) redirect(url('admin/dashboard.php'));
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    verify_csrf(); $email = strtolower(input('email')); $password = password_input('password');
    if (!rate_limit('login',10,900)) $error = 'Too many sign-in attempts. Please try again in 15 minutes.';
    elseif (!valid_email($email) || strlen($password) > 72) $error = 'The email or password is incorrect.';
    else {
        $user = User::byEmail($email);
        $hash = $user['password'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
        $valid = password_verify($password,$hash);
        if ($valid && $user && $user['role']==='admin') { if(password_needs_rehash($hash,PASSWORD_DEFAULT)) User::update((int)$user['id'],['password'=>password_hash($password,PASSWORD_DEFAULT)]); login_user($user); redirect(url('admin/dashboard.php')); }
        $error = 'The email or password is incorrect.';
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Sign in · BrandBuzz Admin</title><link rel="stylesheet" href="<?= e(asset('css/tailwind.css')) ?>"><script src="<?= e(asset('js/app.js')) ?>" defer></script></head><body class="bg-peach min-h-screen flex items-center justify-center p-6"><main class="card w-full max-w-4xl grid md:grid-cols-2 overflow-hidden"><div class="hidden md:flex bg-brand-50 p-8 flex-col justify-between"><a href="<?= e(page_url()) ?>" class="brand"><span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>BrandBuzz<span>.</span></a><img src="<?= e(asset('images/agency.webp')) ?>" alt="Creative workspace" width="500" height="500" class="mix-blend-multiply"><div><h2 class="text-2xl font-bold">A home for your<br>next big idea.</h2><p class="text-sm text-muted mt-3">Manage your content. Make a little buzz.</p></div></div><div class="p-7 md:p-10 py-12"><p class="eyebrow">Content studio</p><h1 class="text-3xl font-bold">Welcome back.</h1><p class="text-muted mt-3 mb-7">Sign in to manage your agency website.</p><?php foreach(flashes() as $message): ?><div role="status" class="flash flash-<?= e($message['type']) ?>"><?= e($message['message']) ?></div><?php endforeach ?><?php if($error): ?><p role="alert" class="flash flash-error"><?= e($error) ?></p><?php endif ?><form method="post" action="<?= e(url('admin/login.php')) ?>"><?= csrf_field() ?><div class="mb-5"><label for="email">Email address</label><input id="email" type="email" name="email" maxlength="190" autocomplete="username" value="<?= e($email) ?>" required></div><div><label for="password">Password</label><div class="relative"><input id="password" type="password" name="password" autocomplete="current-password" maxlength="72" required class="pr-16"><button type="button" data-password-toggle="password" aria-pressed="false" class="absolute right-3 top-3 text-sm text-brand-700 font-semibold p-1">Show</button></div></div><button class="btn btn-primary w-full mt-7" type="submit">Sign in <?= icon('arrow','w-4 h-4') ?></button></form><p class="text-xs text-muted mt-5">Use the account you created during installation.</p><a class="link-arrow mt-8" href="<?= e(page_url()) ?>">Back to website</a></div></main></body></html>
