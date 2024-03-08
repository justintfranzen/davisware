# Davisware
Live Site: https://www.davisware.com/

Production: https://davisware.wpengine.com/

QA: https://daviswareqa.wpengine.com/

Dev: https://daviswaredev.wpengine.com/

Site is hosted on WPEngine. The 3 branches `production`, `qa` & `dev` all push directly to the corresponding environment via GitHub actions. These branches are locked, PRs are needed to merge code into them.

## Setup:
Set up your local dev environment & add these to your wp-config.php:

```
define('WP_SITEURL','https://davisware.test');
define('WP_HOME','https://davisware.test');

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'WP_DEBUG_LOG', true );
define( 'SCRIPT_DEBUG', true );

define( 'LOCAL_DEV_ENV', true);
define( 'DEV_ENV', false);
define( 'QA_ENV', false);
define( 'PRODUCTION_ENV', false);
```

In the root folder (`public_html`) run:
```
npm install && npm run dev
```

## Other Commands:
Run a once time build
```
npm run build
```
Run in dev mode (watches files and rebuilds)
```
npm run dev
```
Run the linter
```
npm run lint
```
Run the linter & resolve issues
```
npm run lint:fix
```