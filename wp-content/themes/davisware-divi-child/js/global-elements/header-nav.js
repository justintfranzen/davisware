export const initHeaderNav = () => {
  const headerNav = document.querySelector('#main-header');

  if (!headerNav) {
    return;
  }

  window.addEventListener('scroll', () => {
    const scroll = window.scrollY;
    if (scroll > 150) {
      headerNav.classList.add('header-background');
    } else {
      headerNav.classList.remove('header-background');
    }
  });
};
