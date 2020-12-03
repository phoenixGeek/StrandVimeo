<?php
define('ALTUMCODE', 1);
define('ROOT_PATH', realpath(__DIR__ . '/..') . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('THEME_PATH', ROOT_PATH . 'themes/altum/');
define('THEME_URL_PATH', 'themes/altum/');
define('ASSETS_PATH', THEME_PATH . 'assets/');
define('ASSETS_URL_PATH', THEME_URL_PATH . 'assets/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
define('UPLOADS_URL_PATH', 'uploads/');

/* Config file */
require_once APP_PATH . 'config/config.php';

/* Establish cookie / session on this path specifically */
define('COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', SITE_URL));
session_set_cookie_params(null, COOKIE_PATH);
session_start();

/* Starting to include the required files */
require_once APP_PATH . 'includes/debug.php';
require_once APP_PATH . 'includes/product.php';

/* Load the traits */
require_once APP_PATH . 'traits/Paramsable.php';

/* Require the core files */
require_once APP_PATH . 'core/App.php';
require_once APP_PATH . 'core/Router.php';
require_once APP_PATH . 'core/Controller.php';
require_once APP_PATH . 'core/Model.php';
require_once APP_PATH . 'core/View.php';
require_once APP_PATH . 'core/Language.php';
require_once APP_PATH . 'core/Title.php';
require_once APP_PATH . 'core/Database.php';

/* Load the models */
require_once APP_PATH . 'models/User.php';

/* Load some helpers */
require_once APP_PATH . 'helpers/ThemeStyle.php';
require_once APP_PATH . 'helpers/Event.php';
require_once APP_PATH . 'helpers/Link.php';
require_once APP_PATH . 'helpers/Date.php';

require_once APP_PATH . 'helpers/Response.php';
require_once APP_PATH . 'helpers/DataTable.php';
require_once APP_PATH . 'helpers/links.php';
require_once APP_PATH . 'helpers/strings.php';
require_once APP_PATH . 'helpers/notifications.php';
require_once APP_PATH . 'helpers/others.php';

/* Autoload for vendor */
require_once ROOT_PATH . 'vendor/autoload.php';
require_once ROOT_PATH . 'vendor/ffmpeg/autoload.php';
require_once ROOT_PATH . 'vendor/vimeo/autoload.php';
