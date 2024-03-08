const textSwitch = (switcherSpans, switcher) => {
  const lastActive = [...switcherSpans].reduce(
    (activeSpanIndex, span, index) => {
      if (span.classList.contains('active')) {
        span.classList.remove('active');
        return index;
      }
      return activeSpanIndex;
    },
    -1,
  );
  const activeSpan = lastActive + 1 < switcherSpans.length ? lastActive + 1 : 0;
  switcherSpans[activeSpan].classList.add('active');
  const width = switcherSpans[activeSpan].offsetWidth;
  switcher.style.width = `${width}px`;
};

const loadTextSwitcher = () => {
  const switcher = document.querySelector('.dvsw-text-switcher');
  const switcherSpans = switcher?.querySelectorAll('.dvsw-text-switcher span');
  if (!switcher || !switcherSpans?.length) {
    return;
  }
  switcher.classList.add('initialized');
  textSwitch(switcherSpans, switcher);
  setInterval(() => {
    textSwitch(switcherSpans, switcher);
  }, 1500);
};

export const initTextSwitcher = () => {
  window.addEventListener('load', loadTextSwitcher);
};
