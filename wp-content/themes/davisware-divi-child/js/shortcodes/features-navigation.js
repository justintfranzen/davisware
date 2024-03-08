export const initFeaturesNavigation = () => {
  const maxDesktopWidth = window.matchMedia('(min-width: 981px)');
  if (maxDesktopWidth.matches) {
    const navigationHolder = document.getElementsByClassName(
      'dvsw-features-navigation',
    );
    if (!navigationHolder.length) {
      return;
    }

    // This block here removes the WP classes and reclones the node in order to stop the divi
    // Event listeners from attaching to it
    const navLinksLi = document.querySelectorAll(
      '.dvsw-features-navigation>div>ul>li',
    );
    const navLinks = document.querySelectorAll(
      '.dvsw-features-navigation>div>ul>li>a',
    );
    navigationHolder[0]?.children[0]?.children[0]?.classList?.remove('menu');
    let foundActive = false;

    navLinksLi.forEach((link) => {
      link.classList.remove(
        'menu-item',
        'menu-item-type-custom',
        'menu-item-has-children',
        'menu-item-object-custom',
      );
      if (
        link.classList.contains('current-menu-ancestor') &&
        foundActive === false
      ) {
        link.classList.add('active');
        foundActive = true;
      }
    });

    // Set first item to active if none found
    navigationHolder[0].classList.add('activated');
    if (!foundActive) {
      navLinksLi[0].classList.add('active');
    }

    // Bind tab event listeners

    navLinks.forEach((link) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        navLinksLi.forEach((linkLi) => {
          linkLi.classList.remove('active');
        });
        link.parentElement.classList.add('active');
      });
    });

    const featuresNav = document.querySelector('.feature-links');

    if (!featuresNav) {
      return;
    }

    window.addEventListener('scroll', () => {
      const scroll = window.scrollY;
      if (scroll > 530) {
        featuresNav.classList.add('feature-links-sticky');
      } else {
        featuresNav.classList.remove('feature-links-sticky');
      }
    });
  }

  const maxMobileWidth = window.matchMedia('(max-width: 980px)');
  if (maxMobileWidth.matches) {
    const navigationHolder = document.getElementsByClassName(
      'dvsw-features-navigation',
    );
    if (!navigationHolder.length) {
      return;
    }

    // This block here removes the WP classes and reclones the node in order to stop the divi
    // Event listeners from attaching to it
    const navLinksLi = document.querySelectorAll(
      '.dvsw-features-navigation>div>ul>li',
    );
    const navLinks = document.querySelectorAll(
      '.dvsw-features-navigation>div>ul>li>a',
    );
    navigationHolder[0]?.children[0]?.children[0]?.classList?.remove('menu');
    let foundActive = false;

    navLinksLi.forEach((link) => {
      link.classList.remove(
        'menu-item',
        'menu-item-type-custom',
        'menu-item-has-children',
        'menu-item-object-custom',
      );
      if (
        link.classList.contains('current-menu-ancestor') &&
        foundActive === false
      ) {
        link.classList.add('active');
        foundActive = true;
      }
    });

    // Set first item to active if none found
    navigationHolder[0].classList.add('activated');
    if (!foundActive) {
      navLinksLi[0].classList.add('active');
    }

    navLinks.forEach((link) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        link.parentElement.classList.toggle('active');
      });
    });
  }
};
