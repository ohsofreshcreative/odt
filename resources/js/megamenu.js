/**
 * 3-Column MegaMenu
 * Left: Categories | Center: Subcategories | Right: Image
 */

if (typeof window.Alpine !== 'undefined') {
  initMegaMenu();
} else {
  document.addEventListener('alpine:init', () => {
    setTimeout(initMegaMenu, 100);
  });
}

function initMegaMenu() {
  console.log('🎯 MegaMenu 3-Column: Initializing...');
  
  const isDesktop = () => window.innerWidth >= 1024;

  const navItems = document.querySelectorAll('.nav-item.has-megamenu');
  console.log(`🎯 Found ${navItems.length} megamenu items`);

  navItems.forEach((navItem) => {
    const dropdown = navItem.querySelector('.megamenu-dropdown');
    if (!dropdown) return;

    const categoryItems = dropdown.querySelectorAll('.megamenu-category-item');
    const centerContent = dropdown.querySelector('.megamenu-center-content');
    const imageElement = dropdown.querySelector('.megamenu-image');

    // Hover na kategoriach (L1) - pokazuje sub-items (L2) w centrum
    categoryItems.forEach((categoryItem) => {
      const categoryLink = categoryItem.querySelector('.megamenu-category-link');
      const subItems = categoryItem.querySelector('.megamenu-subitems');
      const imageUrl = categoryItem.getAttribute('data-image');
      const imageAlt = categoryItem.getAttribute('data-image-alt') || '';

      if (!categoryLink) return;

      categoryItem.addEventListener('mouseenter', function() {
        // Usuń active ze wszystkich kategorii
        categoryItems.forEach(item => item.classList.remove('active'));
        
        // Dodaj active do tej kategorii
        categoryItem.classList.add('active');

        // Pokaż sub-items w centrum
        if (subItems && centerContent) {
          centerContent.innerHTML = '';
          const clonedSubItems = subItems.cloneNode(true);
          clonedSubItems.style.display = 'block';
          centerContent.appendChild(clonedSubItems);
        } else {
          centerContent.innerHTML = '';
        }

        // Pokaż obrazek po prawej
        if (imageUrl && imageElement) {
          imageElement.src = imageUrl;
          imageElement.alt = imageAlt;
          imageElement.style.display = 'block';
        } else if (imageElement) {
          imageElement.style.display = 'none';
        }
      });
    });

    // Aktywuj pierwszą kategorię domyślnie
    if (categoryItems.length > 0) {
      categoryItems[0].dispatchEvent(new Event('mouseenter'));
    }

    // Desktop hover logic
    if (isDesktop()) {
      let hoverTimeout;

      navItem.addEventListener('mouseenter', function() {
        clearTimeout(hoverTimeout);
        try {
          if (window.Alpine && navItem._x_dataStack) {
            const alpineData = window.Alpine.$data(navItem);
            if (alpineData) {
              alpineData.open = true;
            }
          }
        } catch (e) {
          console.warn('Alpine interaction failed:', e);
        }
      });

      navItem.addEventListener('mouseleave', function() {
        hoverTimeout = setTimeout(() => {
          try {
            if (window.Alpine && navItem._x_dataStack) {
              const alpineData = window.Alpine.$data(navItem);
              if (alpineData) {
                alpineData.open = false;
              }
            }
          } catch (e) {
            console.warn('Alpine interaction failed:', e);
          }
        }, 150);
      });
    }
  });

  console.log('🎯 MegaMenu 3-Column: Complete');
}

let resizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(initMegaMenu, 250);
});