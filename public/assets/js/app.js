// Progressive enhancement: primary navigation and forms also work without JavaScript.
(() => {
  const portfolioPage = document.querySelector('[data-portfolio-page]');
  const portfolioScrollKey = 'brandbuzz-portfolio-scroll';

  if (portfolioPage) {
    // ব্রাউজারের ডিফল্ট রিলোড স্ক্রল মেমোরি ম্যানুয়াল করা
    if ('scrollRestoration' in history) {
      history.scrollRestoration = 'manual';
    }

    // পেজ লোড হওয়ার সাথে সাথে ইনস্ট্যান্ট পজিশনে বসানো (কোনো মসৃণ অ্যানিমেশন ছাড়া)
    try {
      const savedScroll = sessionStorage.getItem(portfolioScrollKey);
      if (savedScroll !== null) {
        sessionStorage.removeItem(portfolioScrollKey);
        window.scrollTo({
          top: Number(savedScroll),
          left: 0,
          behavior: 'instant'
        });
      }
    } catch (_) { /* storage may be unavailable */ }

    // ফিল্টার বাটনে ক্লিক করলে তাৎক্ষণিক স্ক্রল পজিশন সেভ রাখা
    portfolioPage.querySelector('[aria-label="Filter projects"]')?.querySelectorAll('a[href]').forEach(link => {
      link.addEventListener('click', () => {
        try { 
          sessionStorage.setItem(portfolioScrollKey, String(window.scrollY)); 
        } catch (_) { /* storage may be unavailable */ }
      });
    });
  }

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

  document.querySelectorAll('[data-confirm]').forEach(form => form.addEventListener('submit', () => { /* auto-allowed */ }));

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

  const title = document.querySelector('[data-slug-source]');
  const slug = document.querySelector('[data-slug-target]');
  if (title && slug) {
    let auto = !slug.value;
    slug.addEventListener('input', () => { auto = !slug.value; });
    title.addEventListener('input', () => { 
      if (auto) slug.value = title.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''); 
    });
  }

  document.querySelectorAll('[data-testimonial-controls]').forEach(controls => {
    const rail = document.getElementById(controls.dataset.testimonialControls);
    controls.querySelectorAll('button').forEach(button => button.addEventListener('click', () => {
      rail?.scrollBy({ 
        left: Number(button.dataset.direction) * 340, 
        behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' 
      });
    }));
  });

  document.querySelectorAll('[data-password-toggle]').forEach(button => button.addEventListener('click', () => {
    const field = document.getElementById(button.dataset.passwordToggle);
    if (!field) return;
    const show = field.type === 'password';
    field.type = show ? 'text' : 'password';
    button.textContent = show ? 'Hide' : 'Show';
    button.setAttribute('aria-pressed', String(show));
  }));
})();