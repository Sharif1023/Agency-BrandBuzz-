<?php
if(!defined('ROOT_PATH')) exit;
$config = (require ROOT_PATH . '/app/config/admin_content.php')[$section]; $model = $config['model'];
$id = $editing ? (int)query('id') : 0; $original = $id ? $model::find($id) : null;
if ($editing && !$original) not_found();
$data = $original ?? ['title'=>'','slug'=>'','excerpt'=>'','content'=>'','status'=>'draft','sort_order'=>0,'icon'=>'sparkles','accent'=>'orange','category'=>'','client'=>'','project_year'=>date('Y'),'website_url'=>'','author'=>$adminUser['name'],'image'=>''];
$errors = [];
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    verify_csrf();
    foreach(['title','slug','excerpt','content','status','sort_order'] as $key) $data[$key] = input($key);
    $data['slug'] = $data['slug'] ?: slugify($data['title']);
    if(str_length($data['title'])<2 || str_length($data['title'])>140) $errors[]='Use a title between 2 and 140 characters.';
    if(!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/',$data['slug']) || strlen($data['slug'])>160) $errors[]='The slug must use lowercase English letters, numbers and single hyphens, up to 160 characters.';
    elseif($model::slugExists($data['slug'],$id)) $errors[]='This slug is already used. Choose a different slug.';
    if(str_length($data['excerpt'])<10 || str_length($data['excerpt'])>300) $errors[]='Write a summary between 10 and 300 characters.';
    if(str_length($data['content'])<20 || str_length($data['content'])>50000) $errors[]='Write content between 20 and 50,000 characters.';
    if(!in_array($data['status'],['draft','published'],true)) $errors[]='Choose a valid publishing status.';
    if(filter_var($data['sort_order'],FILTER_VALIDATE_INT)===false || (int)$data['sort_order']<0 || (int)$data['sort_order']>9999) $errors[]='Sort order must be a whole number between 0 and 9999.';
    if($section==='services') {
        $data['icon']=input('icon'); $data['accent']=input('accent');
        if(!in_array($data['icon'],['search','megaphone','sparkles','code','grid'],true)) $errors[]='Select a valid icon.';
        if(!in_array($data['accent'],['yellow','green','purple','orange'],true)) $errors[]='Select a valid accent colour.';
    } else {
        $data['category']=input('category');
        if(str_length($data['category'])<2 || str_length($data['category'])>60) $errors[]='Category must contain 2–60 characters.';
        if($section==='projects') {
            foreach(['client','project_year','website_url'] as $key) $data[$key]=input($key);
            if(str_length($data['client'])<2 || str_length($data['client'])>100) $errors[]='Client name must contain 2–100 characters.';
            if(!preg_match('/^\d{4}$/',$data['project_year']) || (int)$data['project_year']<1970 || (int)$data['project_year']>2100) $errors[]='Choose a project year between 1970 and 2100.';
            if(!safe_external($data['website_url'])) $errors[]='Use a full http:// or https:// website URL, or leave it empty.';
        } else {
            $data['author']=input('author');
            if(str_length($data['author'])<2 || str_length($data['author'])>100) $errors[]='Author must contain 2–100 characters.';
        }
    }
    if(!$errors) {
        $newImage = null;
        try {
            if($config['image']) { $data['image']=upload_image('image',$original['image'] ?? 'images/agency.webp'); if($data['image']!==($original['image']??null)) $newImage=$data['image']; }
            if($id) $model::update($id,$data); else $id=$model::create($data);
            if($newImage && $original) delete_upload($original['image']);
            flash('success',ucfirst($config['singular']).' saved'.($data['status']==='published'?' and published.':' as a draft.'));
            redirect(url('admin/'.$section.'/edit.php?id='.$id));
        } catch(InvalidArgumentException $error) { if($newImage) delete_upload($newImage); $errors[]=$error->getMessage(); }
        catch(PDOException $error) { if($newImage) delete_upload($newImage); error_log((string)$error); $errors[]=$error->getCode()==='23000'?'This slug was just used by another entry. Choose a different slug.':'The content could not be saved. Please try again.'; }
    }
}
admin_start(($editing?'Edit ':'New ').$config['singular'],$section,'Make it yours. Save as a draft, or publish when it is ready.');
admin_errors($errors);
?>
<form method="post" enctype="multipart/form-data">
<?= csrf_field() ?>
<div class="grid xl:grid-cols-[1.7fr_1fr] gap-6"><div class="card p-6 md:p-8 space-y-6">
<?php admin_field('title','Title',$data['title'],'text',true,140); admin_field('slug','URL slug',$data['slug'],'text',true,160,'Example: brand-strategy. Use lowercase English letters, numbers and hyphens.'); admin_field('excerpt','Short summary',$data['excerpt'],'textarea',true,300,'Displayed on the listing page and used for search descriptions.'); admin_field('content','Full content',$data['content'],'textarea',true,50000,'Separate paragraphs with a blank line. Text is safely displayed as plain content; HTML is not executed.'); ?>
</div><div class="space-y-6"><div class="card p-6 space-y-5"><h2 class="text-lg font-bold">Publishing</h2><?php admin_select('status','Status',['draft'=>'Draft — hidden from website','published'=>'Published — visible on website'],$data['status']); admin_field('sort_order','Sort order',$data['sort_order'],'number',true,4,'Lower values appear first for services and projects.'); ?><button type="submit" class="btn btn-primary w-full">Save <?= e($config['singular']) ?> <?= icon('check','w-4 h-4') ?></button><a class="btn btn-neutral w-full" href="<?= e(url('admin/'.$section.'/index.php')) ?>">Back to list</a></div>
<div class="card p-6 space-y-5"><h2 class="text-lg font-bold">Details</h2><?php if($section==='services'): ?><?php admin_select('icon','Icon',['search'=>'Search / SEO','megaphone'=>'Megaphone / Marketing','sparkles'=>'Sparkles / Creative','code'=>'Code / Web design','grid'=>'Grid / Strategy'],$data['icon']); admin_select('accent','Accent colour',['yellow'=>'Yellow','green'=>'Green','purple'=>'Purple','orange'=>'Orange'],$data['accent']); ?><?php else: ?><?php admin_field('category','Category',$data['category'],'text',true,60); if($section==='projects') { admin_field('client','Client',$data['client'],'text',true,100); admin_field('project_year','Year',$data['project_year'],'number',true,4); admin_field('website_url','Website URL (optional)',$data['website_url'],'url',false,500); } else admin_field('author','Author',$data['author'],'text',true,100); admin_image('image','Cover image',$data['image']); ?><?php endif ?></div></div></div></form>
<?php admin_end(); ?>
