<?php
/**
 * Core configuration file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Core configuration file is responsible for setting system paths, delimiters.
 */

/*
 * ----------------------------------------------------------------------
 * Settings: Path Defaults
 * ----------------------------------------------------------------------
 */
// Framework paths
define( 'SERVER_ROOT', 			dirname(__FILE__) );
define( 'DEFAULT_SYSTEM_PATH', 		'minimvc/' );
define( 'DEFAULT_APPS_PATH', 		'/' );

// Application Paths
define( 'APP_PATH', 			'/' );
define( 'DEFAULT_PUBLIC_PATH', 		'/' );
define( 'DEFAULT_APP_CONFIG_PATH', 	'/' );
define( 'DEFAULT_CONTROLLER_PATH', 	'/' );
define( 'DEFAULT_LIBRARY_PATH',		'/' );
define( 'DEFAULT_LOG_PATH', 		'/' );
define( 'DEFAULT_MODEL_PATH', 		'/' );
define( 'DEFAULT_MODULE_PATH', 		'/' );
define( 'DEFAULT_VIEW_PATH', 		'/' );
define( 'DEFAULT_CACHE_PATH', 		'/' );
define( 'DEFAULT_DATA_PATH', 		'/' );
define( 'DEFAULT_REQUIRE_PATH', 	'/' );

// Application View paths
define( 'DEFAULT_CONTENT_PATH',		'/' );
define( 'DEFAULT_MESSAGE_PATH', 	'/' );
define( 'DEFAULT_TEMPLATE_PATH', 	'/' );
define( 'DEFAULT_SHARED_PATH', 		'/' );
define( 'DEFAULT_ERROR_PATH', 		'/' );

// Public_html paths
define( 'DEFAULT_MEDIA_PATH', 		'/' );

/*
 * ----------------------------------------------------------------------
 * Settings: Delimiters for the URI and Variables
 * ----------------------------------------------------------------------
 * ex: '/' for site.com/controller/request/variable
 */
define( 'URI_SEPARATOR', 		'/' );
define( 'VAR_SEPARATOR', 		'/' );

?>

