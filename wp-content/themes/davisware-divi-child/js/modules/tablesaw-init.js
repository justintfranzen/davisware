export const tablesawInit = () => {
  /*! Tablesaw - v3.1.2 - 2019-03-19
   * https://github.com/filamentgroup/tablesaw
   * Copyright (c) 2019 Filament Group; Licensed MIT */
  const globalPatch = document.getElementById('global-patch-port');

  if (globalPatch) {
    // eslint-disable-next-line func-names
    (function (win) {
      // DOM-ready auto-init of plugins.
      // Many plugins bind to an "enhance" event to init themselves on dom ready, or when new markup is inserted into the DOM
      // Use raw DOMContentLoaded instead of shoestring (may have issues in Android 2.3, exhibited by stack table)
      if (!('Tablesaw' in win)) {
        throw new Error('Tablesaw library not found.');
      }
      // eslint-disable-next-line no-undef
      if (!('init' in Tablesaw)) {
        throw new Error(
          'Your tablesaw-init.js is newer than the core Tablesaw version.',
        );
      }
      // eslint-disable-next-line no-undef
      Tablesaw.init();
    })(typeof window !== 'undefined' ? window : this);
  }
};
