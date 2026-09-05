// Progressive enhancement: primary navigation and forms also work without JavaScript.
(() => {
  let activeAbortController = null;

  // স্ক্রিপ্ট এক্সিকিউট হেল্পার (নতুন পেজের জাভাস্ক্রিপ্ট কার্যকর করার জন্য)
  const executeScripts = (targetContainer) => {
    const scripts = targetContainer.querySelectorAll('script');
    scripts.forEach((oldScript) => {
      const newScript = document.createElement('script');
      Array.from(oldScript.attributes).forEach((attr) => newScript.setAttribute(attr.name, attr.value));
      if (oldScript.src) {
        newScript.src = oldScript.src;
      } else {
        newScript.textContent = oldScript.textContent;
      }
      oldScript.parentNode.replaceChild(newScript, oldScript);
    });
  };

  // স্ট্যান্ডার্ড SPA রাউটার ও মসৃণ ট্রানজিশন হ্যান্ডলার
  const navigateTo = async (url, pushState = true) => {
    // দ্রুত পরপর ক্লিক করলে আগের রিকোয়েস্ট ক্যানসেল করা
    if (activeAbortController) {
      activeAbortController.abort();
    }
    activeAbortController = new AbortController();

    const mainContent = document.querySelector('main') || document.querySelector('[data-page-content]') || document.body;

    try {
      // ১. পেজ আউট অ্যানিমেশন
      mainContent.classList.add('page-fade-out');
      mainContent.classList.remove('page-fade-in');

      const [res] = await Promise.all([
        fetch(url, {
          signal: activeAbortController.signal,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }),
        new Promise((resolve) => setTimeout(resolve, 180))
      ]);

      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

      const htmlText = await res.text();
      const parser = new DOMParser();
      const newDoc = parser.parseFromString(htmlText, 'text/html');

      // ডকুমেন্ট মেটাডাটা ও টাইটেল সিঙ্ক
      if (newDoc.title) {
        document.title = newDoc.title;
      }

      // মূল কন্টেন্ট অংশ রিপ্লেসমেন্ট
      const newContent = newDoc.querySelector('main') || newDoc.querySelector('[data-page-content]') || newDoc.body;
      if (mainContent && newContent) {
        mainContent.innerHTML = newContent.innerHTML;
        executeScripts(mainContent);
      }

      // ন্যাভবার অ্যাক্টিভ স্টেট সিঙ্ক
      const targetPath = new URL(url, window.location.origin).pathname;
      document.querySelectorAll('.nav-link').forEach((link) => {
        const linkPath = new URL(link.href, window.location.origin).pathname;
        const isActive = linkPath === targetPath;
        link.classList.toggle('active', isActive);
        if (isActive) {
          link.setAttribute('aria-current', 'page');
        } else {
          link.removeAttribute('aria-current');
        }
      });

      // ব্রাউজার হিস্ট্রি পুশ
      if (pushState) {
        window.history.pushState(null, '', url);
      }

      window.scrollTo(0, 0);

      // মোবাইল ড্রয়ার মেনু স্বয়ংক্রিয়ভাবে বন্ধ করা
      const mobileMenu = document.querySelector('[data-mobile-menu]');
      const menuBtn = document.querySelector('[data-menu-toggle]');
      if (mobileMenu && !mobileMenu.hidden) {
        mobileMenu.hidden = true;
        menuBtn?.setAttribute('aria-expanded', 'false');
      }
    } catch (err) {
      if (err.name !== 'AbortError') {
        window.location.href = url;
      }
    } finally {
      // ২. পেজ ইন অ্যানিমেশন
      requestAnimationFrame(() => {
        mainContent.classList.remove('page-fade-out');
        mainContent.classList.add('page-fade-in');
      });
      activeAbortController = null;
    }
  };

  // লিঙ্ক ক্লিক ইন্টারসেপ্টর (ইন্টারনাল লিঙ্ক ও ক্যাটাগরি ফিল্টারিং)
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href]');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('tel:') || href.startsWith('mailto:')) {
      return;
    }

    if (link.hostname !== window.location.hostname || link.target === '_blank') {
      return;
    }

    // পোর্টফোলিও ফিল্টার, ন্যাভবার এবং সাধারণ পেজ লিঙ্ক হ্যান্ডলিং
    if (
      link.closest('header nav') ||
      link.classList.contains('brand') ||
      link.closest('[aria-label="Filter projects"]') ||
      link.closest('[data-portfolio-page]')
    ) {
      e.preventDefault();
      navigateTo(link.href, true);
    }
  });

  // ব্রাউজার হিস্ট্রি বাটন (Back/Forward) লিসেনার
  window.addEventListener('popstate', () => {
    navigateTo(window.location.href, false);
  });

  // মোবাইল মেনু কন্ট্রোল
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
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeMenu();
        menuButton.focus();
      }
    });
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
  }

  // অ্যাডমিন সাইডবার কন্ট্রোল
  const adminToggle = document.querySelector('[data-admin-toggle]');
  const sidebar = document.querySelector('[data-admin-sidebar]');
  if (adminToggle && sidebar) {
    adminToggle.addEventListener('click', () => {
      const expanded = sidebar.classList.toggle('is-open');
      adminToggle.setAttribute('aria-expanded', String(expanded));
    });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        sidebar.classList.remove('is-open');
        adminToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ফর্ম সাবমিশন
  document.querySelectorAll('[data-confirm]').forEach((form) => form.addEventListener('submit', () => { /* auto-allowed */ }));

  // ইমেজ আপলোড প্রিভিউ
  document.querySelectorAll('[data-image-input]').forEach((input) => {
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

  // অটোমেটিক স্লাগ জেনারেটর
  const title = document.querySelector('[data-slug-source]');
  const slug = document.querySelector('[data-slug-target]');
  if (title && slug) {
    let auto = !slug.value;
    slug.addEventListener('input', () => { auto = !slug.value; });
    title.addEventListener('input', () => {
      if (auto) slug.value = title.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    });
  }

  // টেস্টমোনিয়াল স্ক্রল কন্ট্রোল
  document.querySelectorAll('[data-testimonial-controls]').forEach((controls) => {
    const rail = document.getElementById(controls.dataset.testimonialControls);
    controls.querySelectorAll('button').forEach((button) =>
      button.addEventListener('click', () => {
        rail?.scrollBy({
          left: Number(button.dataset.direction) * 340,
          behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'
        });
      })
    );
  });

  // পাসওয়ার্ড ভিজিবিলিটি টগল
  document.querySelectorAll('[data-password-toggle]').forEach((button) =>
    button.addEventListener('click', () => {
      const field = document.getElementById(button.dataset.passwordToggle);
      if (!field) return;
      const show = field.type === 'password';
      field.type = show ? 'text' : 'password';
      button.textContent = show ? 'Hide' : 'Show';
      button.setAttribute('aria-pressed', String(show));
    })
  );
})();