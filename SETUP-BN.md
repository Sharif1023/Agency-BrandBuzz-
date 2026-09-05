# BrandBuzz Agency — বাংলা Setup Guide

আপনার দেওয়া reference-এর কমলা–সাদা design অনুসরণ করে তৈরি PHP + MySQL + Tailwind CSS agency website। ZIP-এ frontend, admin CMS, database, local font, তিনটি original 3D illustration এবং compiled CSS আছে। চালাতে Node.js বা Composer লাগবে না।

## ১. যা লাগবে

- PHP 8.1 বা নতুন।
- MySQL 8+ অথবা MariaDB 10.6+।
- PHP extensions: PDO, pdo_mysql, fileinfo, session; mbstring recommended।
- XAMPP/Apache অথবা PHP-support থাকা cPanel hosting।
- `storage/` ও `public_assets/uploads/` PHP-এর জন্য writable থাকতে হবে। সাধারণত folder permission 755 যথেষ্ট; hosting owner/group ভিন্ন হলে provider-এর নির্দেশ অনুযায়ী 775 ব্যবহার করুন।

## ২. XAMPP-এ চালানো

1. ZIP extract করুন। পুরো `BrandBuzz-Agency` folder রাখুন:
   `C:\xampp\htdocs\BrandBuzz-Agency`
2. XAMPP Control Panel থেকে **Apache** ও **MySQL** start করুন।
3. Browser-এ `http://localhost/phpmyadmin` খুলুন।
4. `brandbuzz_agency` নামে database তৈরি করুন; collation দিন `utf8mb4_unicode_ci`।
5. Database select করে **Import** → `database/agency.sql` → **Go** দিন। এই SQL-এ sample content আছে, কোনো default admin password নেই।
6. `.env.example` copy করে `.env` নাম দিন। Windows Explorer-এ file extension দেখিয়ে নিশ্চিত করুন নামটি `.env`, `.env.txt` নয়। নিচের values মিলিয়ে দিন:

```dotenv
APP_ENV=production
APP_URL=http://localhost/BrandBuzz-Agency
APP_TIMEZONE=Asia/Dhaka
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brandbuzz_agency
DB_USERNAME=root
DB_PASSWORD=
INSTALL_KEY=এখানে-নিজের-গোপন-কমপক্ষে-৩২-অক্ষরের-key-দিন
SESSION_TIMEOUT=3600
UPLOAD_MAX_MB=5
```

উপরের `INSTALL_KEY`-এর নমুনা বাক্যটি ব্যবহার করবেন না। একটি random key তৈরি করতে project folder-এ terminal খুলে চালান:

```bat
C:\xampp\php\php.exe tools\generate-key.php
```

যে key দেখাবে, সেটি `.env`-এ `INSTALL_KEY=`-এর পরে বসান। Terminal না থাকলে password manager দিয়ে অন্তত ৩২ অক্ষরের random key তৈরি করতে পারেন। Password বা key-তে space বা `#` থাকলে `.env` value-টি double quote দিয়ে লিখুন।

7. খুলুন: `http://localhost/BrandBuzz-Agency/install.php`
8. সব check ঠিক থাকলে **Installation key**, আপনার নাম, admin email ও কমপক্ষে ১২ অক্ষরের password দিয়ে account তৈরি করুন। Password-এর সর্বোচ্চ দৈর্ঘ্য ৭২ bytes।
9. Admin login: `http://localhost/BrandBuzz-Agency/admin/login.php`
10. Website: `http://localhost/BrandBuzz-Agency/`

প্রথম admin তৈরির পর installer নিজে lock হয়। পুনরায় এটি খুললে sign-in link দেখাবে।

## ৩. cPanel-এ চালানো

1. cPanel → **MySQL Databases** থেকে database ও database user তৈরি করুন। User-কে সেই database-এর permissions দিন।
2. phpMyAdmin-এ সেই database select করে `database/agency.sql` import করুন।
3. File Manager থেকে ZIP upload ও extract করুন। নতুন subfolder হলে পুরো project রাখুন `public_html/BrandBuzz-Agency/`-এ। Domain-এর মূল ঠিকানায় চাইলে project folder-এর **ভেতরের সব ফাইল ও folder** খালি `public_html`-এ রাখুন।
4. File Manager settings-এ **Show Hidden Files** চালু করুন। `.htaccess` ফাইলগুলোসহ পুরো structure রাখতে হবে।
5. `.env.example` থেকে `.env` তৈরি করুন এবং hosting-এর database name, user ও password দিন। cPanel database name ও username-এ account prefix থাকে; যেমন `account_brandbuzz`।
6. `APP_URL` দিন আপনার exact HTTPS URL:

```dotenv
APP_ENV=production
APP_URL=https://your-domain.com/BrandBuzz-Agency
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=account_brandbuzz
DB_USERNAME=account_brandbuzzuser
DB_PASSWORD="your database password"
INSTALL_KEY="your own random private installation key"
```

Domain root-এ extract করলে `APP_URL=https://your-domain.com` হবে। Host যদি আলাদা DB hostname দেয়, সেটি ব্যবহার করুন।

7. **Select PHP Version / MultiPHP Manager** থেকে PHP 8.1+ এবং প্রয়োজনীয় extensions নিশ্চিত করুন।
8. নিজের domain-এর `/install.php` বা `/BrandBuzz-Agency/install.php` খুলে admin account তৈরি করুন।
9. Admin settings-এ নিজের brand, email, phone, address ও social links বসান। Sample portfolio ও testimonial বদলে দিন।

এই structure-এ web root হলো সম্পূর্ণ project folder; শুধু `public/` folder upload করলে admin ও assets পাওয়া যাবে না। Apache-এ `.htaccess` internal configuration, SQL ও storage directory-তে web access বন্ধ করে। Nginx ব্যবহার করলে README-এর আলাদা configuration প্রযোজ্য।

## ৪. PHP built-in server দিয়ে local run

MySQL চালু রেখে database import ও `.env` setup করার পর project folder-এ:

```bash
php -S localhost:8000 router.php
```

এভাবে চালালে `.env`-এ `APP_URL=http://localhost:8000` দিন অথবা value খালি রাখুন। তারপর `http://localhost:8000/install.php` খুলুন। `router.php` ব্যবহার করবেন, যাতে private file-এ সরাসরি access বন্ধ থাকে।

## ৫. Admin panel থেকে যা করতে পারবেন

| Menu | কাজ |
| --- | --- |
| Overview | Services, projects, posts ও নতুন message-এর count; recent enquiries |
| Homepage | Hero text, তিনটি illustration, about copy, CTA বদলানো |
| Services | Add, edit, delete, icon/colour, sort order, draft/publish |
| Projects | Add, edit, delete, cover upload, category, client, year, external URL |
| Blog posts | Add, edit, delete, cover upload, category, author, draft/publish |
| Messages | Enquiry পড়া, search/filter, New/Read/Archived status, delete |
| Settings | Brand, SEO description, contact, socials, testimonials, password |

- **Draft** public website-এ দেখাবে না।
- নিজের বাস্তব projects ও testimonial বসানোর পর Settings-এর **Show demo labels** checkbox বন্ধ করে save করুন।
- Services ও projects-এ ছোট sort order আগে দেখাবে। Blog newest-created-first দেখাবে।
- Cover image: JPG, PNG, WebP বা GIF; default limit 5 MB।
- Content editor plain text গ্রহণ করে; দুই paragraph-এর মাঝে একটি blank line দিন। HTML সরাসরি execute হবে না।
- Contact message database-এ save হয়। **Automatic email পাঠানো হয় না**। Message খুলে **Reply by email** চাপলে আপনার device-এর email app খুলবে।
- Payment gateway, public customer registration ও newsletter system এই agency CMS-এর অংশ নয়।
- এক ঘণ্টা server request না থাকলে admin session expire হবে। Password বদলালে অন্য active sessions sign out হবে।

## ৬. Tailwind design পরিবর্তন

ইতিমধ্যে তৈরি CSS ZIP-এ আছে। শুধু PHP লেখা, ছবি বা content বদলালে Node.js লাগবে না। নতুন Tailwind class যোগ করলে rebuild করুন।

Node.js 20.19+ বা 22.12+ থাকলে:

```bash
npm ci
npm run build
```

- মূল stylesheet: `resources/css/app.css`
- Tailwind configuration: `tailwind.config.js`
- Website-এ ব্যবহৃত compiled CSS: `public_assets/css/tailwind.css`
- JavaScript: `public_assets/js/app.js`
- Watch mode: `npm run dev` — এটি শুধু CSS rebuild করে; PHP server আলাদাভাবে চালাতে হবে।
- Optional Vite bundle: `npm run build:vite` — output `public_assets/build/`। Default PHP layout precompiled `css/tailwind.css` এবং `js/app.js` ব্যবহার করে।

## ৭. Common problem

**“Something went wrong” / database connection failed**

`.env` database values ও MySQL service check করুন। SQL ঠিক database-এ import হয়েছে কি না দেখুন। Details `storage/php-error.log`-এ থাকবে। Local debugging-এর সময় সাময়িক `APP_ENV=local` করতে পারেন; live server-এ `production` রাখুন।

**CSS বা image আসে না**

`APP_URL`-এর domain/subfolder ঠিক আছে কি না দেখুন। `public_assets` সম্পূর্ণ upload হয়েছে কি না check করুন। Browser hard refresh করুন। `APP_URL`-এর শেষে `/public` বা `/admin` যোগ করবেন না।

**Upload হচ্ছে না**

`public_assets/uploads` writable হতে হবে। PHP settings-এ `upload_max_filesize` কমপক্ষে `5M`, `post_max_size` কমপক্ষে `20M` দিন। একসঙ্গে তিনটি image বদলালে total request size limit খেয়াল করুন। Upload limit 5 MB হলেও ছবির সর্বোচ্চ dimension 8000px per side ও 24 megapixels।

**Session expired / 419**

Page refresh করে আবার submit করুন। Form খোলা অবস্থায় অন্য tab থেকে login/logout করলে পুরোনো token বদলে যেতে পারে।

**Sign-in attempt limit**

এক IP থেকে ১৫ মিনিটে ১০টি login attempt-এর সীমা আছে। সময় পার হলে আবার চেষ্টা করুন। Enquiry submission-এ ১০ মিনিটে ৫টি valid message-এর সীমা আছে।

**Apache 500 error**

Hosting-এ `mod_rewrite` এবং `.htaccess` overrides support আছে কি না দেখুন। Hosting provider যদি `Options` directive নিষিদ্ধ করে, provider-এর সাহায্যে equivalent server rule ব্যবহার করুন। Private folder protection অক্ষুণ্ণ রাখুন।

**Password ভুলে গেছেন**

এই kit-এ email password-reset নেই। Server administrator নতুন password hash তৈরি করে phpMyAdmin-এ `users.password` update করতে পারেন; একই সঙ্গে `session_version` এক বাড়িয়ে দিন। One-time installer পুনরায় খুলে নতুন account তৈরি করার প্রয়োজন নেই।

## ৮. কোন ফাইল কোথায়

আপনার চাওয়া `public/`, `app/`, `resources/views/`, `admin/`, `public_assets/` ও `database/` structure রাখা হয়েছে। চালু ও maintain করার জন্য অতিরিক্ত `bootstrap.php`, shared base model, admin shared form/list, `install.php`, `.env.example`, `.htaccess`, `router.php`, `tools/` এবং setup documents যুক্ত হয়েছে।

Third-party font license `public_assets/fonts/LICENSE-Inter.txt`-এ আছে। Illustration-গুলো এই project-এর জন্য তৈরি। Sample clients, testimonials ও concept project-গুলো আপনার বাস্তব business information দিয়ে বদলে দিন।
