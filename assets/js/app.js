// Progressive enhancement: primary navigation and forms also work without JavaScript.
(() => {
  // ১. সম্পূর্ণ সাইটের ন্যাভবার ও পেজ রিলোড ছাড়া স্মুথ পেজ ট্রানজিশন (SPA Fetch Handler)
  const navigateTo = async (url, pushState = true) => {
    try {
      // লোডিং এফেক্টের জন্য অপাসিটি কমানো
      document.body.style.opacity = '0.6';
      document.body.style.transition = 'opacity 0.15s ease';

      const res = await fetch(url);
      const htmlText = await res.text();
      const parser = new DOMParser();
      const newDoc = parser.parseFromString(htmlText, 'text/html');

      // নতুন পেজের টাইটেল আপডেট
      if (newDoc.title) {
        document.title = newDoc.title;
      }

      // মূল কন্টেন্ট এরিয়া পরিবর্তন করা (main বা body-র ভেতরের কন্টেন্ট)
      const currentMain = document.querySelector('main') || document.body;
      const newMain = newDoc.querySelector('main') || newDoc.body;

      if (currentMain && newMain) {
        currentMain.innerHTML = newMain.innerHTML;
      }

      // ন্যাভবারে অ্যাক্টিভ ক্লাস আপডেট করা
      const targetPath = new URL(url, window.location.origin).pathname;
      document.querySelectorAll('.nav-link').forEach(link => {
        const linkPath = new URL(link.href, window.location.origin).pathname;
        const isActive = linkPath === targetPath;
        link.classList.toggle('active', isActive);
        if (isActive) {
          link.setAttribute('aria-current', 'page');
        } else {
          link.removeAttribute('aria-current');
        }
      });

      // পেজ রিলোড ছাড়া URL পরিবর্তন
      if (pushState) {
        window.history.pushState(null, '', url);
      }

      // পেজ পরিবর্তন হলে একদম শুরুতে নেওয়া
      window.scrollTo(0, 0);

      // মোবাইল মেনু খোলা থাকলে বন্ধ করা
      const mobileMenu = document.querySelector('[data-mobile-menu]');
      const menuBtn = document.querySelector('[data-menu-toggle]');
      if (mobileMenu && !mobileMenu.hidden) {
        mobileMenu.hidden = true;
        menuBtn?.setAttribute('aria-expanded', 'false');
      }

    } catch (err) {
      window.location.href = url; // কোনো সমস্যা হলে সাধারণ ব্রাউজার রিলোড করবে
    } finally {
      document.body.style.opacity = '1';
    }
  };

  // ন্যাভবার এবং সাইটের ইন্টারনাল লিঙ্কে ক্লিকে রিলোড ব্লক করা
  document.addEventListener('click', (e) => {
    const link = e.target.closest('header nav a[href], a.brand[href]');
    if (!link) return;

    // এক্সটার্নাল লিঙ্ক বা হ্যাশ লিঙ্ক বাদ রাখা
    if (link.hostname !== window.location.hostname || link.getAttribute('href').startsWith('#')) {
      return;
    }

    e.preventDefault(); // ব্রাউজারের ফুল পেজ রিলোড সম্পূর্ণ বন্ধ করবে
    navigateTo(link.href, true);
  });

  // ব্রাউজারের Back / Forward বাটনে যেন কাজ করে
  window.addEventListener('popstate', () => {
    navigateTo(window.location.href, false);
  });

  // ২. পোর্টফোলিও ক্যাটাগরি ফিল্টারিং
  const portfolioPage = document.querySelector('[data-portfolio-page]');
  if (portfolioPage) {
    portfolioPage.addEventListener('click', async (e) => {
      const filterLink = e.target.closest('[aria-label="Filter projects"] a[href]');
      if (!filterLink) return;

      e.preventDefault();
      navigateTo(filterLink.href, true);
    });
  }

  // ৩. মোবাইল মেনু কন্ট্রোল
  const menuButton = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-mobile-menu]');
  if (menuButton && menu) {
    const closeMenu = () => { 
      menu.hidden = true; 
      menuButton.setAttribute('aria-expanded', 'false'); 
    };
    menuButton.addEventListener('click', () => { 
      const expanded = menuButton.getAttribute('aria-expanded') === 'true'; 
      menuButton.setAttribute('aria-expanded', String(!expanded)); 
      menu.hidden = expanded; 
    });
    document.addEventListener('keydown', event => { 
      if (event.key === 'Escape') { 
        closeMenu(); 
        menuButton.focus(); 
      } 
    });
    menu.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
  }

  // ৪. অ্যাডমিন সাইডবার কন্ট্রোল
  const adminToggle = document.querySelector('[data-admin-toggle]');
  const sidebar = document.querySelector('[data-admin-sidebar]');
  if (adminToggle && sidebar) {
    adminToggle.addEventListener('click', () => { 
      const expanded = sidebar.classList.toggle('is-open'); 
      adminToggle.setAttribute('aria-expanded', String(expanded)); 
    });
    document.addEventListener('keydown', event => { 
      if (event.key === 'Escape') { 
        sidebar.classList.remove('is-open'); 
        adminToggle.setAttribute('aria-expanded', 'false'); 
      } 
    });
  }

  // ৫. ইমেজ আপলোড প্রিভিউ
  document.querySelectorAll('[data-image-input]').forEach(input => {
    let previous;
    input.addEventListener('change', () => {
      const image = document.getElementById(input.dataset.imageInput);
      const file = input.files?.[0];
      if (!image || !file || !/^image\/(jpeg|png|webp|gif)$/.test(file.type)) return;
      if (previous) URL.revokeObjectURL(previous);
      previous = URL.createObjectURL(file); 
      image.src = previous; 
      image.hidden = false;
    });
  });

  // ৬. অটো স্লাগ
  const title = document.querySelector('[data-slug-source]');
  const slug = document.querySelector('[data-slug-target]');
  if (title && slug) {
    let auto = !slug.value;
    slug.addEventListener('input', () => { auto = !slug.value; });
    title.addEventListener('input', () => { 
      if (auto) slug.value = title.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''); 
    });
  }

  // ৭. পাসওয়ার্ড শো/হাইড
  document.querySelectorAll('[data-password-toggle]').forEach(button => button.addEventListener('click', () => {
    const field = document.getElementById(button.dataset.passwordToggle);
    if (!field) return;
    const show = field.type === 'password';
    field.type = show ? 'text' : 'password';
    button.textContent = show ? 'Hide' : 'Show';
    button.setAttribute('aria-pressed', String(show));
  }));
})();