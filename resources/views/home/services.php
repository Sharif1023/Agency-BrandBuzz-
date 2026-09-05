<section id="services" class="container-page pb-20 md:pb-24">
  <div class="text-center mb-11"><p class="eyebrow">What we do</p><h2 class="section-title">We provide the best <span class="text-brand-500">services.</span></h2><p class="text-muted mt-4 max-w-lg mx-auto">A thoughtful mix of strategy and creativity, built around your business.</p></div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <?php foreach(array_slice($services,0,4) as $service): ?><?php view('services/card',['service'=>$service]); ?><?php endforeach ?>
  </div>
  <?php if (!$services): ?><p class="text-center text-muted">Our services are being updated. <a class="link-arrow" href="<?= e(page_url('contact')) ?>">Tell us what you need.</a></p><?php endif ?>
</section>
