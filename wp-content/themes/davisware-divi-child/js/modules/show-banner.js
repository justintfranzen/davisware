export const initTopBanner = () => {
  const bannerText = document.querySelector(
    '.top-banner-menu .dvswr-banner .banner-text',
  );
  const pageBody = document.querySelector('body');
  const mainHeader = document.querySelector('#main-header');
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');

  if (!bannerText) {
    pageBody.classList.remove('banner-active');
    mainHeader.classList.remove('banner-active');
    mainHeader.classList.remove('main-header-mobile');
    mobileMenuToggle.classList.remove('banner-active');
    mobileMenu.classList.remove('banner-active-mobile-menu');
  } else {
    pageBody.classList.add('banner-active');
    mainHeader.classList.add('banner-active');
    mainHeader.classList.add('main-header-mobile');
    mobileMenuToggle.classList.add('banner-active');
    mobileMenu.classList.add('banner-active-mobile-menu');
  }
};
