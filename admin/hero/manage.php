<?php
require dirname(__DIR__) . '/_init.php';
$fields = ['hero_eyebrow'=>['Small heading',80],'hero_title'=>['Heading: first line',80],'hero_highlight'=>['Highlighted word',50],'hero_after'=>['Rest of heading',100],'hero_description'=>['Description',400],'hero_button'=>['Button label',30],'about_title'=>['About heading',100],'about_highlight'=>['About highlighted line',100],'about_description'=>['About description',3000],'cta_title'=>['Call-to-action heading',100],'cta_description'=>['Call-to-action description',200]];
$images = ['hero_image'=>'Hero illustration','process_image'=>'Process illustration','about_image'=>'Agency illustration'];
$data=Setting::all(); $errors=[];
if(($_SERVER['REQUEST_METHOD']??'GET')==='POST') {
    verify_csrf(); $values=[];
    foreach($fields as $key=>[$label,$max]) { $values[$key]=input($key); if(str_length($values[$key])<1 || str_length($values[$key])>$max) $errors[]=$label.' is required and must not exceed '.$max.' characters.'; }
    $data=array_merge($data,$values);
    if(!$errors) {
        $newImages=[];
        try {
            foreach($images as $key=>$label) { $path=upload_image($key,$data[$key]??null); if($path!==($data[$key]??null)) $newImages[$key]=$path; if($path) $values[$key]=$path; }
            Setting::save($values);
            foreach($newImages as $key=>$path) delete_upload($data[$key]??null);
            flash('success','Your homepage has been updated.'); redirect(url('admin/hero/manage.php'));
        } catch(InvalidArgumentException $error) { foreach($newImages as $path) delete_upload($path); $errors[]=$error->getMessage(); }
        catch(Throwable $error) { foreach($newImages as $path) delete_upload($path); error_log((string)$error); $errors[]='Changes could not be saved. Check your upload and storage folder permissions.'; }
    }
}
admin_start('Make a great first impression.','hero','Edit your homepage introduction, illustrations, about section and call to action.'); admin_errors($errors);
?>
<form method="post" enctype="multipart/form-data"><?= csrf_field() ?><div class="grid xl:grid-cols-[1.5fr_1fr] gap-6"><div class="space-y-6"><section class="card p-7 space-y-5"><h2 class="text-xl font-bold mb-5">Hero section</h2><?php foreach(array_slice($fields,0,6,true) as $key=>[$label,$max]) admin_field($key,$label,$data[$key]??'',str_contains($key,'description')?'textarea':'text',true,$max); ?></section><section class="card p-7 space-y-5"><h2 class="text-xl font-bold mb-5">About & call to action</h2><?php foreach(array_slice($fields,6,null,true) as $key=>[$label,$max]) admin_field($key,$label,$data[$key]??'',str_contains($key,'description')?'textarea':'text',true,$max); ?></section></div><div class="space-y-6"><section class="card p-7 space-y-7"><h2 class="text-xl font-bold">Illustrations</h2><?php foreach($images as $key=>$label) admin_image($key,$label,$data[$key]??null); ?></section><div class="card p-6"><button type="submit" class="btn btn-primary w-full">Save homepage <?= icon('check','w-4 h-4') ?></button></div></div></div></form>
<?php admin_end(); ?>
