export const initToggle = () => {
  const contractors = document.querySelector('.dsm-toggle-left');
  const manufacturers = document.querySelector('.dsm-toggle-right');

  if (!contractors || !manufacturers) {
    return;
  }

  manufacturers.classList.add('lighten');

  manufacturers.addEventListener('click', () => {
    manufacturers.classList.remove('lighten');
    contractors.classList.add('lighten');
  });

  contractors.addEventListener('click', () => {
    manufacturers.classList.add('lighten');
    contractors.classList.remove('lighten');
  });
};
