<?php if(($active ?? '') !== 'contact') view('home/cta'); ?>
<footer class="bg-peach pt-24 overflow-hidden">
  <div class="container-page grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-10 pb-14">
    <div class="col-span-2 sm:col-span-1">
      <a class="brand" href="<?= e(page_url()) ?>"><span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span><?= e(setting('site_name','BrandBuzz')) ?><span>.</span></a>
      <p class="mt-4 text-sm text-muted max-w-xs"><?= e(setting('footer_text','Good ideas. Thoughtful strategy. A little buzz for your business.')) ?></p>
      <div class="flex gap-4 mt-5">
        <?php foreach(['facebook'=>'Facebook','instagram'=>'Instagram','linkedin'=>'LinkedIn'] as $key=>$label): ?>
          <?php if(setting($key) && safe_external(setting($key))): ?>
            <a class="text-xs font-semibold text-brand-700 hover:underline" href="<?= e(setting($key)) ?>" target="_blank" rel="noopener noreferrer"><?= e($label) ?></a>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    </div>
    <div>
      <h3 class="text-sm font-bold mb-4">Company</h3>
      <ul class="space-y-2 text-sm text-muted">
        <?php foreach(['about'=>'About us','portfolio'=>'Our work','blog'=>'Journal','contact'=>'Contact'] as $key=>$label): ?>
          <li><a class="hover:text-brand-600" href="<?= e(page_url($key)) ?>"><?= e($label) ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>
    <div>
      <h3 class="text-sm font-bold mb-4">What we do</h3>
      <ul class="space-y-2 text-sm text-muted">
        <?php foreach(array_slice(Service::published(),0,4) as $item): ?>
          <li><a class="hover:text-brand-600" href="<?= e(page_url('services',['slug'=>$item['slug']])) ?>"><?= e($item['title']) ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>
    <div>
      <h3 class="text-sm font-bold mb-4">Say hello</h3>
      <a class="text-sm break-words hover:text-brand-600" href="mailto:<?= e(setting('contact_email','hello@example.com')) ?>"><?= e(setting('contact_email','hello@example.com')) ?></a>
      <p class="text-sm text-muted mt-3"><?= e(setting('contact_address','Dhaka, Bangladesh')) ?></p>
      <p class="text-sm text-muted mt-3"><?= e(setting('office_hours','Sunday–Thursday · 10am–6pm')) ?></p>
    </div>
  </div>
  <div class="container-page flex flex-wrap justify-between gap-4 pb-6 text-xs text-muted">
    <p>© <?= date('Y') ?> <?= e(setting('site_name','BrandBuzz')) ?>. All rights reserved.</p>
    <a href="<?= e(url('admin/login.php')) ?>" class="hover:text-brand-600">Admin sign in</a>
  </div>
  <div class="footer-wave" aria-hidden="true"></div>
</footer>
</body></html>