<?php

class Configs extends Scaffold{

	public $configs = array( 'core', 'application', 'database', 'logger', 'routes', 'require' );

	public $undo_directory = true;

	public function initialize()
	{
		$path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . '/config/';
		$this->file( $this->configs, $path );
	}

	public function getContent( $type )
	{

		$name =& $this->name;

		$core = <<<CORE
<?php
/**
 * Application configuration file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 *
 * The Core configuration file is responsible for setting system and application
 * level file paths as well as URI delimiters.
 */

/*
 * ----------------------------------------------------------------------
 * Settings: Path Defaults
 * ----------------------------------------------------------------------
 */
// Framework paths
define('SERVER_ROOT', dirname( dirname( dirname( dirname(__FILE__) )  ) ) .'/');
define( 'DEFAULT_SYSTEM_PATH', 'system/' );
define( 'DEFAULT_APPS_PATH', '{$this->config['apps_path']}' );

// Application Paths
define( 'APP_PATH', '$name/' );
define( 'DEFAULT_PUBLIC_PATH', 'public_html/' );
define( 'DEFAULT_APP_CONFIG_PATH', 'config/' );
define( 'DEFAULT_CONTROLLER_PATH', 'controllers/' );
define( 'DEFAULT_LIBRARY_PATH', 'libraries/' );
define( 'DEFAULT_LOG_PATH' , 'logs/' );
define( 'DEFAULT_MODEL_PATH', 'models/' );
define( 'DEFAULT_MODULE_PATH', 'modules/' );
define( 'DEFAULT_VIEW_PATH', 'views/' );
define( 'DEFAULT_CACHE_PATH', 'temp/' );
define( 'DEFAULT_DATA_PATH', 'data/' );
define( 'DEFAULT_REQUIRE_PATH', 'require/' );

// Application View paths
define( 'DEFAULT_CONTENT_PATH', 'content/' );
define( 'DEFAULT_MESSAGE_PATH', 'message/' );
define( 'DEFAULT_TEMPLATE_PATH', 'template/' );
define( 'DEFAULT_SHARED_PATH', 'shared/' );
define( 'DEFAULT_ERROR_PATH','error/' );

// Public_html paths
define( 'DEFAULT_MEDIA_PATH', 'media/' );


/*
 * ----------------------------------------------------------------------
 * Settings: Delimiters for the URI and Variables
 * ----------------------------------------------------------------------
 * ex: '/' for site.com/controller/request/variable
 */
define( 'URI_SEPARATOR', '/' );
define( 'VAR_SEPARATOR', '/' );

?>



CORE;

		$application = <<<APPLICATION
<?php

return array (

/*
 * ----------------------------------------------------------------------
 * Prefix for methods directly accessible by HTTP
 * ----------------------------------------------------------------------
 * Prefix for controller methods if they are to be accessed via HTTP (POST/GET).
 */
	'http_access_prefix'	=> 'action',

/*
 * ----------------------------------------------------------------------
 * Application defaults
 * ----------------------------------------------------------------------
 */
	'base_href' 		=> 'http://localhost/miniMVC/applications/$name/public_html/',
	'web_root' 		=> 'http://localhost/miniMVC/applications/$name/public_html/',
	'default_controller'	=> 'MainController',
	'default_method' 	=> 'index',
	'default_template' 	=> 'bootstrap-single',
/*
 * ----------------------------------------------------------------------
 * Site information
 * ----------------------------------------------------------------------
 */
	'default_template' 	=> 'bootstrap-single',
	'default_javascript'	=>  array( 'js/jquery/jquery.js', 'js/bs/bootstrap.js' ),
	'default_css' 		=>  array( 'css/bs/bootstrap-superhero.css' ),
	'site_name' 		=> '$name',
	'site_tag' 		=> 'A miniMVC application',
	'site_email' 		=> 'admin@localhost',
	'site_admin' 		=> 'admin',
	'meta_description' 	=> '',
	'meta_keywords' 	=> '',
	'company' 		=> '',
	'company_website' 	=> '',
);

?>
APPLICATION;

		$database = <<<DATABASE
<?php

return array(
	'default' => array(
		'driver'	=>  'mysql',
		'host' 		=>  'localhost',
		'username'	=>  'test',
		'password'	=>  'test',
		'database'	=>  'test',
	)
);

?>
DATABASE;

		$logger = <<<LOGGER
<?php

return array(
	'error' => true,
	'warn' => true,
	'info' => true,
	'debug' => true,
	'display_debug' => true,
);

?>
LOGGER;

		$routes = <<<ROUTES
<?php
	return array(
		'about' 	=> 'main/about',
 	);
?>

ROUTES;

		$require = <<<REQUIRE
<?php
	return array(
 	);
?>

REQUIRE;
		return $$type;

	}
}

?>
