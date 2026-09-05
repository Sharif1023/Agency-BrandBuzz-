<section class="page-intro">
    <div class="container-page">
        <p class="eyebrow">Selected work</p>
        <h1>Ideas with a little<br><span class="text-brand-500">extra spark.</span></h1>
        <p class="mt-6 text-lg text-muted max-w-xl">Explore our approach to brand strategy, digital experiences and thoughtful campaigns.</p>
    </div>
</section>

<section id="portfolio" class="container-page section-space" data-portfolio-page>
    <div class="flex flex-wrap gap-3 mb-10" aria-label="Filter projects">
        <a class="pill <?= $category === '' ? 'active' : '' ?>" href="<?= e(page_url('portfolio')) ?>#portfolio">All work</a>
        <?php foreach($categories as $item): ?>
            <a class="pill <?= $category === $item ? 'active' : '' ?>" href="<?= e(page_url('portfolio',['category'=>$item])) ?>#portfolio"><?= e($item) ?></a>
        <?php endforeach ?>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-7">
        <?php foreach($projects as $project): ?>
            <a class="card overflow-hidden group" href="<?= e(page_url('portfolio',['slug'=>$project['slug']])) ?>">
                <div class="portfolio-cover">
                    <img src="<?= e(media($project['image'])) ?>" alt="<?= e($project['title']) ?>" width="800" height="600" loading="lazy">
                </div>
                <div class="p-6">
                    <p class="eyebrow mb-3"><?= e($project['category']) ?></p>
                    <h2 class="text-xl font-bold"><?= e($project['title']) ?></h2>
                    <p class="text-muted text-sm mt-3"><?= e($project['excerpt']) ?></p>
                    <span class="link-arrow mt-5">View project <?= icon('arrow','w-4 h-4') ?></span>
                </div>
            </a>
        <?php endforeach ?>
    </div>

    <?php if(!$projects): ?>
        <div class="card p-12 text-center">
            <h2 class="text-2xl font-bold">No projects here yet.</h2>
            <a class="btn btn-outline mt-5" href="<?= e(page_url('portfolio')) ?>#portfolio">View all work</a>
        </div>
    <?php endif ?>

    <?php if(setting('show_sample_labels','1')==='1'): ?>
        <?php if(setting('demo_labels','1')==='1'): ?>
            <p class="text-xs text-muted mt-8">Concept projects · Demo portfolio</p>
        <?php endif ?>
    <?php endif ?>
</section>