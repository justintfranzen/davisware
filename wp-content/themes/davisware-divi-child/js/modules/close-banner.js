export const initCloseBanner = () => {
  const closeBanner = document.querySelector('.close-banner');
  const topBanner = document.querySelector('.top-banner-menu');

  if (!closeBanner) {
    return;
  }

  closeBanner.addEventListener('click', () => {
    topBanner.classList.add('banner-close');
  });
};
