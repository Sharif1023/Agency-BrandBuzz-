<section class="bg-peach section-space">
  <div class="container-page grid md:grid-cols-2 gap-10 lg:gap-24 items-center">
    <div class="relative"><img src="<?= e(media(setting('process_image','images/process.webp'))) ?>" alt="Creative strategist working from an orange armchair" class="w-full aspect-square object-contain rounded-4xl mix-blend-multiply" width="1254" height="1254" loading="lazy"></div>
    <div>
      <p class="eyebrow">How we work</p><h2 class="section-title">Simple <span class="text-brand-500">solutions!</span></h2>
      <p class="text-muted mt-4 max-w-md">Growing a business takes enough work. We make the next step clear, from our first conversation to launch day.</p>
      <ol class="space-y-5 mt-7">
      <?php $steps = [['Let’s connect','Tell us about your brand, your goals and what comes next.'],['Build a plan','A clear scope, timeline and approach, made for you.'],['Bring it to life','We create, share, refine and keep you in the loop.'],['Launch & grow','Go live, learn from the results and keep improving.']]; foreach($steps as $i=>$step): ?>
        <li class="flex gap-4"><span class="step-number"><?= $i+1 ?></span><div><h3 class="font-semibold text-base"><?= e($step[0]) ?></h3><p class="text-sm text-muted mt-1"><?= e($step[1]) ?></p></div></li>
      <?php endforeach ?>
      </ol>
      <div class="flex gap-3 mt-8"><a href="<?= e(page_url('contact')) ?>" class="btn btn-primary">Get started <?= icon('arrow','w-4 h-4') ?></a><a href="<?= e(page_url('about')) ?>" class="btn btn-outline">Meet the agency</a></div>
    </div>
  </div>
</section>
