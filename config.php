<?php

// Error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
define('DEBUG', true);

//// Base Path
define('WEB_ROOT', 'http://localhost');
define('SERVER_ROOT', dirname(__FILE__) .'/');
define('DEFAULT_PUBLIC_PATH','public_html/');

//// Path Defaults
// Framework: found in SERVER_ROOT
define('DEFAULT_CONTROLLER_PATH', 'controllers/');
define('DEFAULT_MODEL_PATH', 'models/');
define('DEFAULT_VIEW_PATH', 'views/');
define('DEFAULT_TEMPLATE_PATH', 'views/tpl/');
define('DEFAULT_MODULE_PATH', 'modules/');
define('DEFAULT_LOG_PATH', 'logs/');
// Public: found in DEFAULT_PUBLIC_PATH
define('DEFAULT_CACHE_PATH', 'cache/');
define('DEFAULT_MEDIA_PATH', 'media/');

//// Database Configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'test');

//// Application defaults
define('VAR_SEPARATOR','/');
define('DEFAULT_CONTROLLER', 'test');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_TEMPLATE', 'bootstrap-single');
define('DEFAULT_MODULES', 'menu,cache');
define('DEFAULT_JAVASCRIPT', 'jquery,bootstrap');
define('DEFAULT_CSS', 'bootstrap');

//// Site information
define ('SITE_NAME','miniMVC');
define ('SITE_TAG','An Upstart App-starter');
define ('SITE_EMAIL','info@alemmedia.com');
define ('SITE_ADMIN','admin');
#define ('DEFAULT_LOGO_PATH', 'media/img/logo.png');
define ('META_DESCRIPTION','miniMVC is a super lightweight MVC framework written in PHP.');
define ('META_KEYWORDS','miniMVC, PHP MVC');
define ('COMPANY','Alemmedia');
define ('COMPANY_WEBSITE','http://Alemmedia.com');

?>
