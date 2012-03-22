<?php
// Config.php
//
// Main configuration file for miniMVC.
// Should be relatively constant among various projects.


// Set application to use
define ( 'APP_NAME', 'default' );

// Error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
define('DEBUG_LEVEL', 1 );

// Load Database settings
require_once('database.php');

//// Base Path
define('SERVER_ROOT', dirname( dirname(__FILE__) ) .'/');
define('WEB_ROOT', 'http://localhost/miniMVC/public_html/');
define('BASE_HREF','http://localhost/miniMVC/public_html/');

//// Path Defaults
define('DEFAULT_APPLICATION_PATH', 'apps/' . APP_NAME . '/' );
define('DEFAULT_LIBRARY_PATH', 'base/');
define('DEFAULT_CONTROLLER_PATH', 'controllers/');
define('DEFAULT_MODEL_PATH', 'models/');
define('DEFAULT_VIEW_PATH', 'views/content/');
define('DEFAULT_TEMPLATE_PATH', 'views/template/');
define('DEFAULT_MODULE_PATH', 'modules/');
define('DEFAULT_LOG_PATH', 'logs/');
define('DEFAULT_PUBLIC_PATH', 'public_html/');
define('DEFAULT_CACHE_PATH', 'cache/');
define('DEFAULT_MEDIA_PATH', 'media/');

// What delimits the URI, ie. '/' for site.com/controller/request/variable
define('URI_SEPARATOR','/');

// Load Application specific settings
require_once('apps/' . APP_NAME . '.php');
?>
