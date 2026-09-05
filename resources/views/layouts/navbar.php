<?php $nav = ['index'=>'Home','about'=>'About us','services'=>'Services','portfolio'=>'Our work','blog'=>'Blog','contact'=>'Contact']; ?>
<header class="bg-white border-b border-gray-100/70">
  <div class="container-page flex items-center justify-between gap-6 h-24">
    <a class="brand" href="<?= e(page_url()) ?>" aria-label="<?= e(setting('site_name','BrandBuzz')) ?> home"><span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span><?= e(setting('site_name','BrandBuzz')) ?><span>.</span></a>
    <nav class="hidden lg:flex items-center gap-7" aria-label="Main navigation">
      <?php foreach($nav as $file=>$label): ?><a class="nav-link <?= ($active ?? '') === $file ? 'active' : '' ?>" <?= ($active ?? '') === $file ? 'aria-current="page"' : '' ?> href="<?= e(page_url($file)) ?>"><?= e($label) ?></a><?php endforeach ?>
    </nav>
    <a href="<?= e(page_url('contact')) ?>" class="hidden lg:inline-flex btn btn-primary">Let's talk <?= icon('arrow','w-4 h-4') ?></a>
    <button type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-navigation" class="lg:hidden p-3 rounded-lg border border-gray-200" aria-label="Toggle navigation"><?= icon('menu') ?></button>
  </div>
  <nav id="mobile-navigation" data-mobile-menu class="lg:hidden container-page pb-6" aria-label="Mobile navigation" hidden>
    <div class="grid grid-cols-2 gap-2"><?php foreach($nav as $file=>$label): ?><a class="nav-link px-3 <?= ($active ?? '') === $file ? 'active' : '' ?>" href="<?= e(page_url($file)) ?>"><?= e($label) ?></a><?php endforeach ?></div>
  </nav>
  <noscript><nav class="container-page flex flex-wrap gap-4 lg:hidden pb-5" aria-label="Navigation without JavaScript"><?php foreach($nav as $file=>$label): ?><a href="<?= e(page_url($file)) ?>"><?= e($label) ?></a><?php endforeach ?></nav></noscript>
</header>
