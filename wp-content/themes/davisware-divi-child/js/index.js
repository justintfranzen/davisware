// Shortcodes
import { initFeaturesNavigation } from './shortcodes/features-navigation';
import { initResourceFilter } from './shortcodes/resource-filter';
import { initTextSwitcher } from './shortcodes/switch-text';

// Global Elements
import { initHeader } from './global-elements/header';
import { initHeaderNav } from './global-elements/header-nav';
import { initFooterToggle } from './global-elements/footer-links';
import { initNoReferrer } from './global-elements/no-referrer';

// Modules
import { initScrollySection } from './modules/scrolly-section';
import { initCaptura } from './modules/captura';
import { initToggle } from './modules/industry-toggle';
import { initCalculateRoiModal } from './modules/calculate-roi-modal';
import { initTopBanner } from './modules/show-banner';

// Vendor Global Patch
import { tablesaw } from './modules/tablesaw';
import { tablesawInit } from './modules/tablesaw-init';

// -----------------------------------------------------------------------------

const init = () => {
  // Shortcodes
  initFeaturesNavigation();
  initResourceFilter();
  initTextSwitcher();

  // Global Elements
  initHeader();
  initHeaderNav();
  initFooterToggle();
  initNoReferrer();

  // Modules
  initScrollySection();
  initCaptura();
  initToggle();
  initCalculateRoiModal();
  initTopBanner();

  // Vendor
  tablesaw();
  tablesawInit();
};

init();
