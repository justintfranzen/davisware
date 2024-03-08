export const initNoReferrer = () => {
  const externalLink = document.querySelector(
    '.dvswr-bottom-cta-button-holder a',
  );

  if (externalLink.hasAttribute('target')) {
    externalLink.setAttribute('rel', 'noreferrer noopener');
  }
};
