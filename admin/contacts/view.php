<?php
require dirname(__DIR__) . '/_init.php';
$id=(int)query('id'); $contact=Contact::find($id); if(!$contact) not_found();
if(($_SERVER['REQUEST_METHOD']??'GET')==='POST') {
    verify_csrf();
    if(input('action')==='delete') { Contact::delete($id); flash('success','Message deleted.'); redirect(url('admin/contacts/index.php')); }
    if(input('action')==='status' && in_array(input('status'),['new','read','archived'],true)) { Contact::update($id,['status'=>input('status')]); flash('success','Message status updated.'); redirect(url('admin/contacts/view.php?id='.$id)); }
    http_response_code(400); exit('Invalid action.');
}
admin_start('A message from '.$contact['name'],'contacts','Received '.date('F j, Y · g:ia',strtotime($contact['created_at'])));
?>
<div class="grid lg:grid-cols-[1.6fr_1fr] gap-6"><article class="card p-7 md:p-9"><div class="flex justify-between items-center gap-4 mb-7"><h2 class="text-xl font-bold"><?= e($contact['service']?:'General enquiry') ?></h2><?= badge($contact['status']) ?></div><div class="prose-copy break-words"><?= paragraphs($contact['message']) ?></div><a href="mailto:<?= e($contact['email']) ?>?subject=<?= e(rawurlencode('Re: Your BrandBuzz enquiry')) ?>" class="btn btn-primary mt-6"><?= icon('mail','w-4 h-4') ?>Reply by email</a><p class="field-help">Opens your email app. Replies are not sent or tracked by this website.</p></article><aside class="space-y-6"><div class="card p-6"><h2 class="font-bold text-lg mb-5">Contact details</h2><dl class="space-y-4"><?php foreach(['name'=>'Name','email'=>'Email','phone'=>'Phone','budget'=>'Budget'] as $key=>$label): ?><div><dt class="text-xs text-muted uppercase tracking-wider"><?= e($label) ?></dt><dd class="mt-1 text-sm break-words"><?= e($contact[$key]?:'Not provided') ?></dd></div><?php endforeach ?></dl></div><form method="post" class="card p-6"><?= csrf_field() ?><input type="hidden" name="action" value="status"><?php admin_select('status','Message status',['new'=>'New','read'=>'Read','archived'=>'Archived'],$contact['status']); ?><button class="btn btn-primary mt-5 w-full" type="submit">Update status</button></form><form method="post" data-confirm="Permanently delete this enquiry?"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><button class="btn btn-neutral text-red-700 w-full" type="submit"><?= icon('trash','w-4 h-4') ?>Delete message</button></form></aside></div><a class="link-arrow mt-8" href="<?= e(url('admin/contacts/index.php')) ?>">Back to messages</a>
<?php admin_end(); ?>
