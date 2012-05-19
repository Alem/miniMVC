<?php
/**
 * Application configuration file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 *
 * The Application configuration file is responsible for setting:
 * 	> The prefix for methods directly accessible by HTTP
 * 	> Inclusion of database and application configuration files.
 * 	> Application defaults
 * 	> Site information.
 */


/* 
 * ----------------------------------------------------------------------
 * Settings: Error reporting
 * ----------------------------------------------------------------------
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);


/*
 * ----------------------------------------------------------------------
 * Settings: Logging and Debug Display level
 * ----------------------------------------------------------------------
 * Logging levels: error, warn, info, debug
 *
 * Debug Display:
 * 	0 = none 
 * 	1 = display
 */
const DEBUG_LEVEL =  1;
const LOGGER_ERROR =  true;
const LOGGER_WARN  =  true;
const LOGGER_INFO  =  true;
const LOGGER_DEBUG =  true;


/* 
 * ----------------------------------------------------------------------
 * Settings: Base Path
 * ----------------------------------------------------------------------
 */
define('SERVER_ROOT', dirname( dirname( dirname( dirname(__FILE__) )  ) ) .'/');
const WEB_ROOT 	= 'http://localhost/miniMVC/applications/default/public_html/';
const BASE_HREF = 'http://localhost/miniMVC/applications/default/public_html/';


/* 
 * ----------------------------------------------------------------------
 * Settings: Path Defaults
 * ----------------------------------------------------------------------
 */
// Framework paths
const DEFAULT_SYSTEM_PATH 	= 'system/';
const DEFAULT_APPS_PATH 	= 'applications/';

// Application Paths
const APP_PATH 			= 'default/';
const DEFAULT_PUBLIC_PATH 	= 'public_html/';
const DEFAULT_APP_CONFIG_PATH 	= 'config/';
const DEFAULT_CONTROLLER_PATH 	= 'controllers/';
const DEFAULT_LIBRARY_PATH 	= 'libraries/';
const DEFAULT_LOG_PATH 		= 'logs/';
const DEFAULT_MODEL_PATH 	= 'models/';
const DEFAULT_MODULE_PATH 	= 'modules/';
const DEFAULT_VIEW_PATH 	= 'views/';

// Application View paths
const DEFAULT_CONTENT_PATH 	= 'content/';
const DEFAULT_TEMPLATE_PATH 	= 'template/';
const DEFAULT_SHARED_PATH 	= 'shared/';

// Public_html paths
const DEFAULT_CACHE_PATH 	= 'cache/';
const DEFAULT_MEDIA_PATH 	= 'media/';



/* 
 * ----------------------------------------------------------------------
 * Settings: Delimiters for the URI and Variables
 * ----------------------------------------------------------------------
 * ex: '/' for site.com/controller/request/variable
 */
const URI_SEPARATOR = '/';
const VAR_SEPARATOR = '/';



/* 
 * ----------------------------------------------------------------------
 * Prefix for methods directly accessible by HTTP
 * ----------------------------------------------------------------------
 * Prefix for controller methods if they are to be accessed via HTTP (POST/GET).
 */
const HTTP_ACCESS_PREFIX = 'action';


/*
 * ----------------------------------------------------------------------
 * Load Database settings
 * ----------------------------------------------------------------------
 */
require_once('database.php');

/*
 * ----------------------------------------------------------------------
 * Application defaults
 * ----------------------------------------------------------------------
 */
const DEFAULT_CONTROLLER =  'test';
const DEFAULT_METHOD =  'index';
const DEFAULT_TEMPLATE =  'bootstrap-single';
const DEFAULT_MODULES =  'base/menu,base/cache,base/helper';
const DEFAULT_JAVASCRIPT =  'jquery,bootstrap';
const DEFAULT_CSS =  'bs/bootstrap-superhero';

/*
 * ----------------------------------------------------------------------
 * Site information
 * ----------------------------------------------------------------------
 */
const SITE_NAME = 'miniMVC';
const SITE_TAG = 'An Upstart App-starter';
const SITE_EMAIL = 'info@alemmedia.com';
const SITE_ADMIN = 'admin';
#const DEFAULT_LOGO_PATH =  'media/img/logo.png';
const META_DESCRIPTION = 'miniMVC is a super lightweight MVC framework written in PHP.';
const META_KEYWORDS = 'miniMVC, PHP MVC';
const COMPANY = 'Alemmedia';
const COMPANY_WEBSITE = 'http://Alemmedia.com';

?>
