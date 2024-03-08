export const initHeader = () => {
  const mobileMenuToggle =
    document.getElementsByClassName('mobile-menu-toggle');
  if (!mobileMenuToggle?.length) {
    return;
  }

  mobileMenuToggle[0].addEventListener('click', () => {
    mobileMenuToggle[0].classList.toggle('close');
    const mobileMenu = document.querySelectorAll('.mobile-menu');
    mobileMenu.forEach((element) => {
      element.classList.toggle('open');
    });
  });

  const subMenuToggle = document.querySelectorAll('.menu-item-has-children a');
  for (let i = 0; i < subMenuToggle.length; i++) {
    subMenuToggle[i].addEventListener('click', () => {
      subMenuToggle[i].classList.toggle('section-active');
      const menu = subMenuToggle[i].parentNode.querySelector(':scope > ul');
      menu.classList.toggle('open-menu');
    });
  }

  const parentItemLink = document.querySelectorAll('.parent-link a');
  for (let i = 0; i < parentItemLink.length; i++) {
    parentItemLink[i].addEventListener('click', () => {
      const parentMenu =
        parentItemLink[i].parentNode.querySelector(':scope > ul');
      parentMenu.classList.toggle('open-menu-single');
      parentMenu.classList.toggle('top-border');
    });
  }
};
