export const initCaptura = () => {
  const closeCaptura = document.querySelector('.close-captura');

  if (!closeCaptura) {
    return;
  }

  closeCaptura.addEventListener('click', () => {
    closeCaptura.parentNode.closest('.captura').classList.add('close-captura');
  });
};
