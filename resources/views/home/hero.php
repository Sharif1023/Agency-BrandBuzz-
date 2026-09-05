<section class="container-page relative pt-12 pb-14 md:pt-20 md:pb-24">
  <div class="grid md:grid-cols-2 items-center gap-8 lg:gap-12">
    <div>
      <p class="eyebrow"><?= e(setting('hero_eyebrow','A little spark. A bigger impact.')) ?></p>
      <h1 class="hero-title"><?= e(setting('hero_title','We create')) ?><br><span class="text-brand-500"><?= e(setting('hero_highlight','solutions')) ?></span> <?= e(setting('hero_after','for your business.')) ?></h1>
      <p class="text-muted mt-6 max-w-md text-base md:text-lg leading-relaxed"><?= e(setting('hero_description','Good ideas deserve to be seen. We bring strategy, creativity and digital know-how together to help your brand grow.')) ?></p>
      <div class="flex flex-wrap gap-4 items-center mt-8">
        <a class="btn btn-primary" href="<?= e(page_url('contact')) ?>"><?= e(setting('hero_button','Get started')) ?> <?= icon('arrow','w-4 h-4') ?></a>
        <a class="inline-flex items-center gap-3 text-sm font-semibold p-2" href="#services"><span class="w-9 h-9 rounded-full border border-brand-100 flex items-center justify-center text-brand-600"><?= icon('plus','w-4 h-4') ?></span>Explore more</a>
      </div>
      <p class="mt-8 text-sm text-muted">Thoughtful strategy. Creative people. Clear direction.</p>
    </div>
    <div class="hero-visual relative">
      <img class="art-image aspect-[3/2]" src="<?= e(media(setting('hero_image','images/hero.webp'))) ?>" alt="Two creative teammates sharing ideas with laptops on orange and tan beanbags" width="1536" height="1024" fetchpriority="high">
      <div class="dot-pattern absolute -bottom-5 right-0" aria-hidden="true"></div>
    </div>
  </div>
</section>
