<?php

# Error reporting
error_reporting('-1');
ini_set('display_errors',1);

# Base Path
define('WEB_ROOT', 'http://localhost');
define('SERVER_ROOT', dirname(__FILE__) .'/');

# Database Configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'test');
define('DB_PASSWORD', 'test');
define('DB_DATABASE', 'test');

# Site information
define ('SITE_NAME','miniMVC');
define ('SITE_TAG','An Upstart App-Starter.');
define ('SITE_EMAIL','info@alemmedia.com');
define ('COMPANY','Alemmedia');
define ('COMPANY_WEBSITE','http://Alemmedia.com');

# Application defaults
define('VARIABLE_SEPARATOR','/');
define('DEFAULT_CONTROLLER', 'test');
define('DEFAULT_TEMPLATE', 'bootstrap');
define('DEFAULT_MODULES', 'menu');

# Path defaults
define('DEFAULT_CONTROLLER_PATH', 'controllers/');
define('DEFAULT_MODEL_PATH', 'models/');
define('DEFAULT_VIEW_PATH', 'views/');
define('DEFAULT_TEMPLATE_PATH', 'views/tpl/');
define('DEFAULT_MODULE_PATH', 'modules/');
define('DEFAULT_CACHE_PATH', 'cache/');

?>
