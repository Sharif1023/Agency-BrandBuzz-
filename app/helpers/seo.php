<?php
declare(strict_types=1);
function seo_tags(string $title = '', string $description = '', string $canonical = ''): string {
    $name = setting('site_name', 'BrandBuzz');
    $full = $title ? $title . ' | ' . $name : $name . ' — Digital marketing & creative agency';
    $description = $description ?: setting('meta_description', 'Clear strategy, thoughtful design and digital marketing for ambitious brands.');
    $out = '<title>' . e($full) . '</title><meta name="description" content="' . e($description) . '"><meta property="og:title" content="' . e($full) . '"><meta property="og:description" content="' . e($description) . '"><meta property="og:type" content="website">';
    if ($canonical && preg_match('#^https?://#', $canonical)) $out .= '<link rel="canonical" href="' . e($canonical) . '">';
    return $out;
}
