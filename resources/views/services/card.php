<a class="service-card group block" href="<?= e(page_url('services',['slug'=>$service['slug']])) ?>">
  <span class="service-icon accent-<?= e(in_array($service['accent'],['yellow','green','purple','orange'],true) ? $service['accent'] : 'orange') ?>"><?= icon($service['icon'],'w-6 h-6') ?></span>
  <h3 class="text-lg font-bold mb-3"><?= e($service['title']) ?></h3>
  <p class="text-sm text-muted leading-relaxed"><?= e($service['excerpt']) ?></p>
  <span class="link-arrow mt-5">Explore service <?= icon('arrow','w-4 h-4') ?></span>
</a>
