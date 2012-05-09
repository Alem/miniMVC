<?php

class Config extends Template{

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
		$this -> fileCache() -> create( $this -> scaffold( 'app' ), 'app' );
		$this -> fileCache() -> create( $this -> scaffold( 'database' ), 'database' );
	}

	public function undo()
	{
		echo 'Removing application and database config templates' . "\n";
		$this -> fileCache() -> clear( 'app' );
		$this -> fileCache() -> clear( 'database' );
		echo 'Removing: ' .  $this -> fileCache() -> path . "\n";
		rmdir( $this -> fileCache() -> path );
	}


	public function scaffold( $type )
	{

		$name =& $this -> name;

		$app = <<<APP
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
 * Settings: Debug Output level
 * ----------------------------------------------------------------------
 * 0 = none 
 * 1 = username 'admin' only
 * 2 = everyone
 */
const DEBUG_LEVEL =  2;


/* 
 * ----------------------------------------------------------------------
 * Settings: Base Path
 * ----------------------------------------------------------------------
 */
define('SERVER_ROOT', dirname( dirname( dirname( dirname(__FILE__) )  ) ) .'/');
const WEB_ROOT 	= 'http://localhost/miniMVC/applications/$name/public_html/';
const BASE_HREF = 'http://localhost/miniMVC/applications/$name/public_html/';


/* 
 * ----------------------------------------------------------------------
 * Settings: Path Defaults
 * ----------------------------------------------------------------------
 */
// Framework paths
const DEFAULT_LOG_PATH 		= 'logs/';
const DEFAULT_SYSTEM_PATH 	= 'system/';
const DEFAULT_APPS_PATH 	= 'applications/';

// Application Paths
const APP_PATH 			= '$name/';
const DEFAULT_PUBLIC_PATH 	= 'public_html/';
const DEFAULT_APP_CONFIG_PATH 	= 'config/';
const DEFAULT_CONTROLLER_PATH 	= 'controllers/';
const DEFAULT_LIBRARY_PATH 	= 'libraries/';
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
const DEFAULT_CONTROLLER =  'main';
const DEFAULT_METHOD =  'index';
const DEFAULT_TEMPLATE =  'bootstrap-single';
const DEFAULT_MODULES =  'base/menu,base/cache,base/helper';
const DEFAULT_JAVASCRIPT =  'jquery/jquery,bs/bootstrap';
const DEFAULT_CSS =  'bs/bootstrap-superhero';

/*
 * ----------------------------------------------------------------------
 * Site information
 * ----------------------------------------------------------------------
 */
const SITE_NAME = '$name';
const SITE_TAG = 'A miniMVC Application';
const SITE_EMAIL = 'info@localhost';
const SITE_ADMIN = 'admin';
#const DEFAULT_LOGO_PATH =  '';
const META_DESCRIPTION = '';
const META_KEYWORDS = '';
const COMPANY = '';
const COMPANY_WEBSITE = '';

?>



APP;

		$database = <<<DATABASE
<?php


//// Database Configuration
const DB_SERVER =  'localhost';
const DB_USERNAME =  'test';
const DB_PASSWORD =  'test';
const DB_DATABASE =  'test';



?>
DATABASE;


		return $$type;

	}
}

?>
