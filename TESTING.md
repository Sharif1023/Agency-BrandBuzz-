# Delivery verification

Validated on 5 September 2026 using PHP 8.3.6, MariaDB 10.11.14, Tailwind CSS 3.4.19 and Vite 6.4.3.

## Completed checks

- PHP syntax validation across the complete PHP source.
- Successful Tailwind CLI production CSS build, optional Vite bundle build and JavaScript syntax check.
- 122 assertions through isolated PHP CGI requests and database reads. Coverage included all six public pages, public detail pages/404s, all admin sections, local assets, search/category filtering, installation, authentication, CRUD, draft/public visibility and contact workflows.
- Real multipart image upload, safe random filenames, deletion cleanup, rejection of disguised PHP files and rejection of images exceeding the size limit.
- Missing-CSRF rejection, invalid-input rejection, HTML escaping, contact throttling, password-change session invalidation and POST-only logout.
- 24 follow-up assertions covering editable settings, unsafe social URL rejection, demo labels, homepage image replacement/cleanup, automatic subfolder URLs, idle-session expiry, private routes and dynamically selected Tailwind styles.
- The included `tests/model-smoke.php` completed its 18 database assertions and removed its temporary records.
- No application warnings or exceptions were logged during successful integration checks.

The automatic URL fallback for a blank APP_URL was corrected during testing and verified with XAMPP-style public, nested admin and installer paths.

## Scope

Requests were executed with PHP CGI against an isolated MariaDB database; this checks request parsing, sessions, uploads, controllers and stored data. No browser interaction, screenshot comparison, accessibility audit, live cPanel deployment, Apache/Nginx runtime test, load test or SMTP delivery test was performed. Responsive layouts are implemented in Tailwind/CSS; browser rendering has not been visually verified.

Test accounts, credentials, enquiries, temporary images, session files and database runtime files are excluded from the deliverable. The SQL file contains only the clean schema and documented sample agency content.
