<?php if(setting('show_testimonials','1') === '1'): ?>
<section class="container-page pb-16 md:pb-24">
  <div class="text-center mb-9"><p class="eyebrow">A little client love</p><h2 class="section-title">What clients <span class="text-brand-500">say.</span></h2><?php if(setting('show_sample_labels','1')==='1'): ?><?php if(setting('demo_labels','1')==='1'): ?><p class="text-muted mt-3 text-sm">Illustrative client feedback</p><?php endif ?><?php endif ?></div>
  <div class="testimonial-rail" id="testimonials">
    <?php for($i=1;$i<=3;$i++): ?>
    <figure class="card p-6">
      <div class="flex items-center gap-3"><span class="w-11 h-11 flex items-center justify-center rounded-full bg-brand-50 text-brand-700 font-semibold"><?= e(strtoupper(substr(setting('testimonial_'.$i.'_name','Client'),0,1))) ?></span><figcaption><p class="font-semibold text-sm"><?= e(setting('testimonial_'.$i.'_name','Sample client')) ?></p><p class="text-xs text-muted"><?= e(setting('testimonial_'.$i.'_role','Business owner')) ?></p></figcaption></div>
      <blockquote class="mt-5 text-sm leading-relaxed text-muted">“<?= e(setting('testimonial_'.$i.'_quote','A clear plan, thoughtful ideas and a team that kept us involved along the way.')) ?>”</blockquote>
      <div class="flex gap-1 text-amber-500 mt-5" aria-label="5 out of 5 stars"><?php for($star=0;$star<5;$star++) echo icon('star','w-4 h-4 fill-current'); ?></div>
    </figure>
    <?php endfor ?>
  </div>
  <div data-testimonial-controls="testimonials" class="flex justify-center gap-3 mt-3 md:hidden"><button type="button" data-direction="-1" class="btn btn-neutral btn-small" aria-label="Previous testimonial">Previous</button><button type="button" data-direction="1" class="btn btn-neutral btn-small" aria-label="Next testimonial">Next</button></div>
</section>
<?php endif ?>
