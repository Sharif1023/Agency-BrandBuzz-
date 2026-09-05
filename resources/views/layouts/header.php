<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f45b12">
  <?= seo_tags($title ?? '', $description ?? '', $canonical ?? '') ?>
  <link rel="icon" type="image/svg+xml" href="<?= e(asset('images/favicon.svg')) ?>">
  <link rel="preload" href="<?= e(asset('fonts/InterVariable.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= e(asset('css/tailwind.css')) ?>">
  <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</head>
<body>
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 btn btn-primary">Skip to content</a>
