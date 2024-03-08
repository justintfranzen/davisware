import skrollr from 'skrollr';

// -----------------------------------------------------------------------------

export const initScrollySection = () => {
  const viewWidth = window.innerWidth;
  const scrolly = document.querySelector('.dvsw-scrolly-section');

  if (!scrolly || viewWidth <= 980) {
    return;
  }
  const height = scrolly.children.length * 100;
  const compHeight = height - 100;
  scrolly.setAttribute(
    'data-100-top',
    'transform: translateX(0%); position: relative; overflow: hidden; opacity: 1;',
  );
  scrolly.setAttribute(
    'data-top',
    'transform: translateX(0%); position: fixed; overflow: visible; opacity: 1;',
  );
  scrolly.setAttribute(
    'data-bottom',
    `transform: translateX(-${compHeight}%); position: fixed; opacity: 1;`,
  );
  scrolly.setAttribute(
    'data-top-bottom',
    `transform: translateX(-${compHeight}%); position: fixed; opacity: 0;`,
  );

  const styleElem = document.head.appendChild(document.createElement('style'));
  styleElem.innerHTML = `.scrolly-after:before, .scrolly-section {height: ${height};}`;
  skrollr.init({
    forceHeight: false,
  });
};
