<?php
require __DIR__ . '/app/bootstrap.php';
header('Cache-Control: no-store'); header('X-Robots-Tag: noindex, nofollow');
$errors=[]; $installed=false; $databaseReady=false;
$checks=['PHP 8.1 or newer'=>version_compare(PHP_VERSION,'8.1.0','>='),'PDO MySQL extension'=>extension_loaded('pdo_mysql'),'Fileinfo extension'=>extension_loaded('fileinfo'),'Private .env file'=>is_file(ROOT_PATH.'/.env'),'Writable storage folder'=>is_writable(ROOT_PATH.'/storage'),'Writable uploads folder'=>is_writable(UPLOAD_DIR),'Private installation key (32+ characters)'=>strlen(env('INSTALL_KEY'))>=32];
try { $installed=User::count()>0 || is_file(ROOT_PATH.'/storage/installed.lock'); Setting::all(); $databaseReady=true; } catch(Throwable $error) { error_log((string)$error); }
$checks['Database tables imported']=$databaseReady;
if(($_SERVER['REQUEST_METHOD']??'GET')==='POST' && !$installed) {
    verify_csrf();
    if(in_array(false,$checks,true)) $errors[]='Complete the setup checks below before creating your account.';
    elseif(!rate_limit('install',5,900)) $errors[]='Too many installation attempts. Try again in 15 minutes.';
    elseif(!hash_equals(env('INSTALL_KEY'),input('install_key'))) $errors[]='The installation key is incorrect.';
    else {
        $name=input('name'); $email=strtolower(input('email')); $password=password_input('password');
        if(str_length($name)<2 || str_length($name)>100) $errors[]='Use a name between 2 and 100 characters.';
        if(!valid_email($email)) $errors[]='Enter a valid email address.';
        if(strlen($password)<12 || strlen($password)>72) $errors[]='Choose a password between 12 and 72 bytes.';
        if($password!==password_input('password_confirmation')) $errors[]='The passwords do not match.';
        if(!$errors) {
            $db=Database::connection(); $lock='brandbuzz_install_'.substr(hash('sha256',env('DB_DATABASE')),0,24);
            $stmt=$db->prepare('SELECT GET_LOCK(?,5)'); $stmt->execute([$lock]);
            if((int)$stmt->fetchColumn()!==1) $errors[]='Another installation is in progress. Please try again.';
            else {
                try {
                    if(User::count()>0) $errors[]='The administrator account has already been created.';
                    else { User::create(['name'=>$name,'email'=>$email,'password'=>password_hash($password,PASSWORD_DEFAULT),'role'=>'admin']); file_put_contents(ROOT_PATH.'/storage/installed.lock',date('c'),LOCK_EX); flash('success','Your admin account is ready. Sign in to personalise your website.'); redirect(url('admin/login.php')); }
                } finally { $release=$db->prepare('SELECT RELEASE_LOCK(?)'); $release->execute([$lock]); }
            }
        }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Install · BrandBuzz</title><link rel="stylesheet" href="<?= e(asset('css/tailwind.css')) ?>"></head><body class="bg-peach"><main class="container-page py-12 max-w-3xl"><a class="brand" href="<?= e(page_url()) ?>"><span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>BrandBuzz<span>.</span></a><section class="card p-7 md:p-10 mt-8"><?php if($installed): ?><span class="service-icon accent-green"><?= icon('check') ?></span><h1 class="text-3xl font-bold">You're all set.</h1><p class="text-muted mt-4">The administrator account has already been created. This installer is locked.</p><a class="btn btn-primary mt-7" href="<?= e(url('admin/login.php')) ?>">Sign in to admin</a><?php else: ?><p class="eyebrow">One-time setup</p><h1 class="text-3xl font-bold">Make BrandBuzz yours.</h1><p class="text-muted mt-4">Create your private administrator account to start managing your website.</p><ul class="space-y-3 my-7"><?php foreach($checks as $label=>$passed): ?><li class="flex items-center gap-3 text-sm"><span class="<?= $passed?'text-green-700':'text-red-700' ?>"><?= icon($passed?'check':'close','w-5 h-5') ?></span><?= e($label) ?></li><?php endforeach ?></ul><?php if(!$databaseReady): ?><p class="flash flash-error">Create a MySQL database, import database/agency.sql using phpMyAdmin and enter your connection details in .env. Follow SETUP-BN.md for full instructions.</p><?php endif ?><?php foreach($errors as $error): ?><div role="alert" class="flash flash-error"><?= e($error) ?></div><?php endforeach ?><form method="post" class="space-y-5"><?= csrf_field() ?><div><label for="install_key">Private installation key</label><input id="install_key" name="install_key" type="password" required autocomplete="off"><p class="field-help">Enter the INSTALL_KEY you set in your .env file.</p></div><div><label for="name">Your name</label><input id="name" name="name" value="<?= e(input('name')) ?>" required maxlength="100" autocomplete="name"></div><div><label for="email">Admin email</label><input id="email" name="email" type="email" value="<?= e(input('email')) ?>" required maxlength="190" autocomplete="username"></div><div><label for="password">Password</label><input id="password" name="password" type="password" minlength="12" maxlength="72" required autocomplete="new-password"></div><div><label for="password_confirmation">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="12" maxlength="72" required autocomplete="new-password"></div><button type="submit" class="btn btn-primary w-full">Create admin account <?= icon('arrow','w-4 h-4') ?></button></form><?php endif ?></section></main></body></html>
