<?php
require dirname(__DIR__) . '/_init.php';
$fields=['site_name'=>['Brand name',40],'meta_description'=>['SEO description',300],'footer_text'=>['Footer description',250],'contact_email'=>['Contact email',190],'contact_phone'=>['Phone number',40],'contact_address'=>['Office address',300],'office_hours'=>['Office hours',150],'facebook'=>['Facebook URL',500],'instagram'=>['Instagram URL',500],'linkedin'=>['LinkedIn URL',500]];
for($i=1;$i<=3;$i++) foreach(['name'=>100,'role'=>100,'quote'=>500] as $type=>$max) $fields['testimonial_'.$i.'_'.$type]=['Testimonial '.$i.' '.ucfirst($type),$max];
$data=Setting::all(); $errors=[];
if(($_SERVER['REQUEST_METHOD']??'GET')==='POST') {
    verify_csrf();
    if(input('action')==='password') {
        $current=password_input('current_password'); $new=password_input('new_password');
        if(!password_verify($current,$adminUser['password'])) $errors[]='Your current password is incorrect.';
        if(strlen($new)<12 || strlen($new)>72) $errors[]='Use a new password between 12 and 72 bytes.';
        if($new!==password_input('confirm_password')) $errors[]='The new passwords do not match.';
        if(!$errors) { User::update((int)$adminUser['id'],['password'=>password_hash($new,PASSWORD_DEFAULT),'session_version'=>(int)$adminUser['session_version']+1]); $_SESSION['session_version']=(int)$adminUser['session_version']+1; session_regenerate_id(true); $_SESSION['csrf']=bin2hex(random_bytes(32)); flash('success','Your password has been changed. Other sessions have been signed out.'); redirect(url('admin/settings/index.php')); }
    } elseif(input('action')==='settings') {
        $values=[];
        foreach($fields as $key=>[$label,$max]) { $values[$key]=input($key); if(str_length($values[$key])>$max) $errors[]=$label.' must not exceed '.$max.' characters.'; }
        $values['show_testimonials']=input('show_testimonials')==='1'?'1':'0';
        $values['demo_labels']=input('demo_labels')==='1'?'1':'0';
        if(str_length($values['site_name'])<2) $errors[]='Enter a brand name with at least two characters.';
        if(!valid_email($values['contact_email'])) $errors[]='Enter a valid contact email.';
        if($values['contact_phone']!=='' && !preg_match('/^[0-9+() .\-]{5,40}$/',$values['contact_phone'])) $errors[]='Use a valid phone number or leave it blank.';
        foreach(['facebook','instagram','linkedin'] as $key) if(!safe_external($values[$key])) $errors[]='Use a full http:// or https:// URL for '.$key.'.';
        $data=array_merge($data,$values);
        if(!$errors) { Setting::save($values); flash('success','Website settings saved.'); redirect(url('admin/settings/index.php')); }
    } else $errors[]='Invalid action.';
}
admin_start('All the little details.','settings','Keep your brand, contact information and account up to date.'); admin_errors($errors);
?>
<form method="post" class="space-y-6"><?= csrf_field() ?><input type="hidden" name="action" value="settings"><div class="grid lg:grid-cols-2 gap-6"><section class="card p-7 space-y-5"><h2 class="text-xl font-bold">Brand & search</h2><?php foreach(array_slice($fields,0,3,true) as $key=>[$label,$max]) admin_field($key,$label,$data[$key]??'',str_contains($key,'description')||$key==='footer_text'?'textarea':'text',$key==='site_name',$max); ?></section><section class="card p-7 space-y-5"><h2 class="text-xl font-bold">Contact details</h2><?php foreach(array_slice($fields,3,4,true) as $key=>[$label,$max]) admin_field($key,$label,$data[$key]??'',$key==='contact_email'?'email':'text',$key==='contact_email',$max); ?></section></div><section class="card p-7"><h2 class="text-xl font-bold mb-6">Social links</h2><div class="grid md:grid-cols-3 gap-5"><?php foreach(['facebook','instagram','linkedin'] as $key) admin_field($key,$fields[$key][0],$data[$key]??'','url',false,500,'Leave empty to hide this link.'); ?></div></section><section class="card p-7"><div class="flex flex-wrap justify-between gap-4 mb-6"><h2 class="text-xl font-bold">Client testimonials</h2><label class="flex items-center gap-3"><input type="checkbox" name="show_testimonials" value="1" <?= ($data['show_testimonials']??'1')==='1'?'checked':'' ?>>Show on homepage</label></div><p class="field-help mb-6">Replace the sample names and quotes with genuine client feedback. Turn off the demo labels below after adding your real projects and client feedback.</p><label class="flex items-center gap-3 mb-6"><input type="checkbox" name="demo_labels" value="1" <?= ($data['demo_labels']??'1')==='1'?'checked':'' ?>>Show demo labels on the public website</label><div class="grid md:grid-cols-3 gap-7"><?php for($i=1;$i<=3;$i++): ?><div class="space-y-4"><?php foreach(['name','role','quote'] as $type): ?><?php $key='testimonial_'.$i.'_'.$type; admin_field($key,$fields[$key][0],$data[$key]??'',$type==='quote'?'textarea':'text',false,$fields[$key][1]); ?><?php endforeach ?></div><?php endfor ?></div></section><button class="btn btn-primary" type="submit">Save website settings <?= icon('check','w-4 h-4') ?></button></form>
<section class="card p-7 mt-10 max-w-3xl"><h2 class="text-xl font-bold mb-3">Change your password</h2><p class="text-muted text-sm mb-6">Choose a unique password with at least 12 characters.</p><form method="post" class="space-y-5"><?= csrf_field() ?><input type="hidden" name="action" value="password"><div><label for="current_password">Current password</label><input type="password" id="current_password" name="current_password" required autocomplete="current-password" maxlength="72"></div><div class="grid sm:grid-cols-2 gap-5"><div><label for="new_password">New password</label><input type="password" id="new_password" name="new_password" required autocomplete="new-password" minlength="12" maxlength="72"></div><div><label for="confirm_password">Confirm new password</label><input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password" minlength="12" maxlength="72"></div></div><button class="btn btn-outline" type="submit"><?= icon('lock','w-4 h-4') ?>Update password</button></form></section>
<?php admin_end(); ?>
