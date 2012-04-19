<?php
/**
 * main.php -  Main configuration file for miniMVC.
 *
 */

/**
 * Error reporting 
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);


/**
 * Debug output 
 * 0 = none 
 * 1 = username 'admin' only
 * 2 = everyone
 */
const DEBUG_LEVEL =  2 ;


/**
 * Base Path
 */
define('SERVER_ROOT', dirname( dirname(__FILE__) ) .'/');
const WEB_ROOT 	= 'http://localhost/miniMVC/public_html/';
const BASE_HREF = 'http://localhost/miniMVC/public_html/';


/**
 * Path Defaults
 */
const DEFAULT_CONFIG_PATH 	= 'config/';
const DEFAULT_LOG_PATH 		= 'logs/';
const DEFAULT_SYSTEM_PATH 	= 'system/';

const DEFAULT_APPS_PATH 	= 'applications/';
const DEFAULT_APP_CONFIG_PATH 	= 'config/';
const DEFAULT_CONTROLLER_PATH 	= 'controllers/';
const DEFAULT_LIBRARY_PATH 	= 'libraries/';
const DEFAULT_MODEL_PATH 	= 'models/';
const DEFAULT_MODULE_PATH 	= 'modules/';

const DEFAULT_VIEW_PATH 	= 'views/';
const DEFAULT_CONTENT_PATH 	= 'content/';
const DEFAULT_TEMPLATE_PATH 	= 'template/';
const DEFAULT_SHARED_PATH 	= 'shared/';

const DEFAULT_PUBLIC_PATH 	= 'public_html/';
const DEFAULT_CACHE_PATH 	= 'cache/';
const DEFAULT_MEDIA_PATH 	= 'media/';


/**
 * System library classes load order
 */
const LIBRARY_CLASSES 	= 'auth/accessControl,base/load,web/request,db/database,db/queryBuilder,base/model,base/controller,log/debug,web/session,cache/fileCache,web/html,web/elements';


/**
 * Prefix for controller methods if they are to be accessed via HTTP (POST/GET).
 */
const HTTP_ACCESS_PREFIX = 'action';


/**
 * Delimits the URI
 * ex: '/' for site.com/controller/request/variable
 */
const URI_SEPARATOR = '/';

/**
 * Load Database settings
 */
require_once('database.php');


/**
 * Load Application settings
 */
const APP_PATH 	= 'default/';
require_once( SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_APP_CONFIG_PATH . 'app.php');

?>
