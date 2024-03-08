export const initFooterToggle = () => {
  const categoryToggle = document.querySelectorAll('.category');
  const categoryLinks = document.querySelectorAll(
    '.footer-links .category-links',
  );
  const maxWidth = window.matchMedia('(max-width: 980px)');

  if (maxWidth.matches) {
    for (let i = 0; i < categoryToggle.length; i++) {
      categoryToggle[i].addEventListener('click', () => {
        categoryLinks[i].classList.toggle('show-category');
      });
    }
  }
};
