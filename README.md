# BrandBuzz Agency

A complete PHP + MySQL agency website and admin CMS, following the supplied orange-and-white agency reference. Bengali installation instructions: **SETUP-BN.md**.

## Included

- Responsive homepage, about, services/detail, portfolio/detail/category filter, blog/detail/search/pagination, contact form.
- Administrator authentication, request-based inactivity expiry, login throttling, session rotation and password-change session invalidation.
- Service, project and blog CRUD with draft/published visibility, validated slugs and pagination.
- Homepage editor, local image uploads, site/SEO/contact/social settings and testimonial fields.
- Contact inbox with search, filtering, statuses and deletion. Reply link opens the administrator's email application; no SMTP service is configured.
- PDO prepared statements, escaped output, CSRF on mutations, upload MIME/dimension/size validation, randomly named uploads and guarded one-time account installation.
- Compiled local Tailwind CSS, local Inter font and three original 3D illustrations. No CDN, Composer or Node process is required at runtime.

## Quick start

1. Use PHP 8.1+ with `pdo_mysql`, `fileinfo` and sessions, plus MySQL 8+ / MariaDB 10.6+.
2. Create a UTF-8 database and import `database/agency.sql`.
3. Copy `.env.example` to `.env`, set the database values and set `APP_URL` to the **project root URL**, or leave it empty for automatic path detection.
4. Generate a private setup key with `php tools/generate-key.php`; save it as `INSTALL_KEY` in `.env`.
5. Put the complete project under Apache (XAMPP/cPanel), or run `php -S localhost:8000 router.php` for local development.
6. Open `/install.php`, enter the private key, and create your administrator account. No default administrator password is included.
7. Open `/admin/login.php`. Replace the demo projects/testimonials and set real contact details before launch.

The project root is the web root for this requested sibling `public/`, `admin/`, `public_assets/` structure. The root `index.php` redirects to the public homepage. Keep all `.htaccess` files. Never serve only `public/` without separately configuring the sibling admin and assets routes.

## Build assets

Node 20.19+ or 22.12+ is needed only when changing the Tailwind design/build tools.

```bash
npm ci
npm run build
npm run check:js
```

`npm run dev` watches the Tailwind input. `npm run build:vite` creates an optional Vite bundle in `public_assets/build/`; default PHP layouts load `public_assets/css/tailwind.css` and `public_assets/js/app.js` directly. Source and lockfile are included; `node_modules` is intentionally excluded from the ZIP.

The custom palette and responsive layouts use Tailwind's [v3 configuration and CLI](https://v3.tailwindcss.com/docs/installation). Authentication uses PHP's [password hashing API](https://www.php.net/manual/en/function.password-hash.php); uploads use PHP's [uploaded-file handling](https://www.php.net/manual/en/function.move-uploaded-file.php).

## Layout

- `public/`: the six page entry points; detail pages use `?slug=`.
- `app/config/`: environment, PDO configuration and admin content map.
- `app/controllers/`: public page and contact request handlers.
- `app/models/`: base PDO model plus User, Service, Project, Blog, Setting and Contact.
- `app/helpers/`: auth, upload, SEO, escaping, URL, CSRF and admin form helpers.
- `resources/views/`: shared layout and home/about/services/portfolio/blog/contact templates.
- `admin/`: login, overview, hero manager, CRUD screens, settings and inbox.
- `public_assets/`: precompiled CSS, plain JavaScript, WebP artwork, local font and writable uploads.
- `database/agency.sql`: non-destructive schema and sample content. No default user or sample enquiry records.
- `storage/`: private application errors, rate-limit counters and installation lock.
- `tools/`: CLI setup-key generator.
- `tests/`: opt-in model integration checks for an isolated test database.

## Behaviour and boundaries

Public content is escaped plain text; blank lines create paragraphs. Uploaded HTML, SVG and executable files are not accepted. A project URL can only use HTTP(S). Contact messages are stored in MySQL and are visible only to the administrator. Rate limits are per client IP in private files; for multiple application servers, use a shared rate-limit store before scaling horizontally. Forwarded IP headers are not trusted by default.

This deliverable is an agency CMS. It does not implement customer accounts, payments, newsletter subscriptions or an SMTP password-reset flow. Sample case studies and testimonials are clearly identified as demo content. Static headings/process text can be edited in their PHP view files.

Only `storage/` and `public_assets/uploads/` need writable permissions. Use HTTPS with `APP_ENV=production` on a live server. Set your explicit public `APP_URL` for canonical SEO tags. Back up MySQL plus uploaded files before changing a live installation. A successful contact submission means it was saved to the inbox; it does not claim that email was sent.

## Nginx deployment

Apache/cPanel is the primary documented target. Nginx ignores `.htaccess`: copy the intent of the following rules into your existing server block and use your actual PHP-FPM socket/path. The project root must be set by your deployment.

```nginx
index index.php;
location ~ /\. { deny all; }
location ~* ^/(app|resources|database|storage|tests|tools|node_modules)(/|$) { deny all; }
location ~* ^/(package(-lock)?\.json|tailwind\.config\.js|vite\.config\.js|postcss\.config\.js|router\.php|README.*|SETUP.*|TESTING.*)$ { deny all; }
location ~* \.(sql|log|env|bak|ini|lock|md)$ { deny all; }
location / { try_files $uri $uri/ =404; }
location ^~ /public_assets/uploads/ {
    types { image/jpeg jpg jpeg; image/png png; image/webp webp; image/gif gif; }
    default_type application/octet-stream;
    add_header X-Content-Type-Options nosniff;
    autoindex off;
    if ($uri !~* \.(jpe?g|png|webp|gif)$) { return 403; }
    try_files $uri =404;
}
location ~ \.php$ {
    try_files $uri =404;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
}
```

For a subfolder deployment, prefix the protected location paths accordingly. The sample is a configuration reference; adapt it to the hosting provider's existing server configuration.

## Credits

Original 3D artwork generated for this project. Inter is bundled under the SIL Open Font License; see `public_assets/fonts/LICENSE-Inter.txt`. UI icons are simple inline stroke SVGs. Demo portfolio entries represent concepts, not completed client work or claimed performance results.
