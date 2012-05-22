<?php

class Configs extends Template{

	public $configs = array( 'core', 'application', 'database', 'logger', 'routes' );


	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> fileCache() -> path = GIMIMVC_ROOT . 'applications/' . $this -> name . '/config/';
	}


	public function generate()
	{
		echo 'Creating: ' .  $this -> fileCache() -> path . "\n";
		mkdir( $this -> fileCache() -> path , 0777 , true );
		echo 'Creating application and database config templates' . "\n";
		foreach( $this -> configs as $config )
			$this -> fileCache() -> create( $this -> scaffold( $config ), $config );
	}

	public function undo()
	{
		echo 'Removing application and database config templates' . "\n";
		foreach( $this -> configs as $config )
			$this -> fileCache() -> clear( $config );
		echo 'Removing: ' .  $this -> fileCache() -> path . "\n";
		rmdir( $this -> fileCache() -> path );
	}


	public function scaffold( $type )
	{

		$name =& $this -> name;

		$core = <<<CORE
<?php
/**
 * Application configuration file.
 *
 * @author Z. Alem <info@alemmedia.com>
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
define( 'DEFAULT_APPS_PATH', 'applications/' );

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

// Application View paths
define( 'DEFAULT_CONTENT_PATH', 'content/' );
define( 'DEFAULT_TEMPLATE_PATH', 'template/' );
define( 'DEFAULT_SHARED_PATH', 'shared/' );

// Public_html paths
define( 'DEFAULT_CACHE_PATH', 'cache/' );
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
	'default_controller'	=> 'main',
	'default_method' 	=> 'index',
	'default_template' 	=> 'bootstrap-single',
/*
 * ----------------------------------------------------------------------
 * Site information
 * ----------------------------------------------------------------------
 */
	'default_template' 	=> 'bootstrap-single',
	'default_javascript'	=>  array( 'jquery,bootstrap' ),
	'default_css' 		=>  array( 'bs/bootstrap-superhero' ),
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
		return $$type;

	}
}

?>
