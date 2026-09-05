<?php
require __DIR__ . '/_init.php';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Use the sign-out button.'); }
verify_csrf(); logout_user(); flash('success','You have signed out.'); redirect(url('admin/login.php'));
