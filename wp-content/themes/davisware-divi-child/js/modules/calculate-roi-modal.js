export const initCalculateRoiModal = () => {
  const calculateROI = document.querySelector('.calculate-roi-modal');
  const modalClose = document.querySelector('.modal-close');
  const btnClose = document.querySelector('.calculate-roi-btn');

  if (!calculateROI && !modalClose && !btnClose) {
    return;
  }

  window.addEventListener('scroll', () => {
    const scroll = window.scrollY;
    if (scroll > 1000) {
      calculateROI.classList.add('modal-active');
    } else {
      calculateROI.classList.remove('modal-active');
    }
  });

  function getCookie(name) {
    const nameEQ = `${name}=`;
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') {
        c = c.substring(1, c.length);
      }
      if (c.indexOf(nameEQ) === 0) {
        return c.substring(nameEQ.length, c.length);
      }
    }
    return null;
  }

  function checkCookie() {
    if (getCookie('calculateROI')) {
      calculateROI.classList.add('disabled');
    } else {
      calculateROI.classList.remove('disabled');
    }
  }
  checkCookie();

  modalClose.addEventListener('click', () => {
    setTimeout(() => {
      calculateROI.classList.remove('disabled');
    }, 1 * 24 * 60 * 60 * 1000);
    const d = new Date();
    d.setTime(d.getTime() + 1 * 24 * 60 * 60 * 1000);
    const expires = `; expires=${d.toGMTString()}`;
    document.cookie = `calculateROI=1${expires}; path=/`;
    calculateROI.classList.add('disabled');
  });

  btnClose.addEventListener('click', () => {
    setTimeout(() => {
      calculateROI.classList.remove('disabled');
    }, 7 * 24 * 60 * 60 * 1000);
    const d = new Date();
    d.setTime(d.getTime() + 7 * 24 * 60 * 60 * 1000);
    const expires = `; expires=${d.toGMTString()}`;
    document.cookie = `calculateROI=1${expires}; path=/`;
    calculateROI.classList.add('disabled');
  });
};
