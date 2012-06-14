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
define( 'SERVER_ROOT', 			dirname( dirname( dirname( dirname(__FILE__) )  ) ) .'/' );
define( 'DEFAULT_SYSTEM_PATH', 		'system/' );
define( 'DEFAULT_APPS_PATH', 		'applications/' );

// Application Paths
define( 'APP_PATH', 			basename( dirname( dirname( __FILE__ ) ) ) . '/' );
define( 'DEFAULT_PUBLIC_PATH', 		'public_html/' );
define( 'DEFAULT_APP_CONFIG_PATH', 	'config/' );
define( 'DEFAULT_CONTROLLER_PATH', 	'controllers/' );
define( 'DEFAULT_LIBRARY_PATH',		'libraries/' );
define( 'DEFAULT_LOG_PATH', 		'logs/' );
define( 'DEFAULT_MODEL_PATH', 		'models/' );
define( 'DEFAULT_MODULE_PATH', 		'modules/' );
define( 'DEFAULT_VIEW_PATH', 		'views/' );
define( 'DEFAULT_CACHE_PATH', 		'temp/' );
define( 'DEFAULT_DATA_PATH', 		'data/' );
define( 'DEFAULT_REQUIRE_PATH', 	'require/' );

// Application View paths
define( 'DEFAULT_CONTENT_PATH',		'content/' );
define( 'DEFAULT_MESSAGE_PATH', 	'message/' );
define( 'DEFAULT_TEMPLATE_PATH', 	'template/' );
define( 'DEFAULT_SHARED_PATH', 		'shared/' );
define( 'DEFAULT_ERROR_PATH', 		'error/' );

// Public_html paths
define( 'DEFAULT_MEDIA_PATH', 		'media/' );

/*
 * ----------------------------------------------------------------------
 * Settings: Delimiters for the URI and Variables
 * ----------------------------------------------------------------------
 * ex: '/' for site.com/controller/request/variable
 */
define( 'URI_SEPARATOR', 		'/' );
define( 'VAR_SEPARATOR', 		'/' );

?>
