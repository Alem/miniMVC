<?php
/**
 * Boot script file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Boot script is required by all application 'index.php' files.
 *
 * It manages the benchmarking, loads the system classes, configures the logger,
 * parses the request, and instantiates the root controller,
 * runs the request and outputs the debug.
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc
 */

/*
 * ----------------------------------------------------------------------
 * Benchmark: Begin Script-timing
 * ----------------------------------------------------------------------
 */
$start_time = microtime(true);

/*
 * ----------------------------------------------------------------------
 * Load System: Require all system/ files
 * ----------------------------------------------------------------------
 */
$system_classes = array (
	'components/Adaptable',
	'auth/AccessControl',
	'base/Benchmark',
	'base/Config',
	'base/Load',
	'base/Router',
	'components/Controller',
	'components/Model',
	'components/ICRUD',
	'lib/File',
	'cache/ICache',
	'cache/Cache',
	'database/Database',
	'database/DbQuery',
	'database/querybuilder/IQueryBuilder',
	'database/querybuilder/QueryBuilder',
	'database/querybuilder/adapter/QBAdapter',
	'log/Logger',
	'server/Request',
	'server/Response',
	'session/Session',
	'util/ArrayUtil',
	'web/Html',
	'web/Element',
);

foreach ( $system_classes as $classname )
	require_once( SERVER_ROOT . DEFAULT_SYSTEM_PATH . $classname  . '.php' );

/*
 * ----------------------------------------------------------------------
 * Load Configs: Loads configuration arrays needed for instantiation
 * ----------------------------------------------------------------------
 */
$config = new Config();
$logger_settings  = $config->fetch('logger');
$default_settings = $config->fetch('application');
$routes 	  = $config->fetch('routes');

/*
 * ----------------------------------------------------------------------
 * Logger Settings: Sets configuration for logger.
 * ----------------------------------------------------------------------
 */
$load = new Load();

Logger::setLogFile( $load->path( 'log', 'application', '.log' ) );
Logger::setConfig( $logger_settings );
Logger::setStartTime( $start_time );

Logger::debug('Logger', 'Instantiated');

/*
 * ----------------------------------------------------------------------
 * Load Application Requirements: Load required files based on config/require.php
 * ----------------------------------------------------------------------
 */
$requirements = $config->fetch('require');

if ( !empty( $requirements ) )
{
	$path = $load->path( 'require' );
	if( 
		isset($requirements['require_all']) 
		&& ($requirements['require_all'] === true ) 
	)
	{
		foreach( glob( $path . '*' ) as $file)
			require_once( $file );	
	}
	else
	{
		foreach ( $requirements['files'] as $file )
			require_once( $path . $file );
	}
}

/*
 * ----------------------------------------------------------------------
 * Initiate Application: Instantiate application and requested controller
 * ----------------------------------------------------------------------
 */
$router 		= new Router();
$application 		= new Controller();
$requested_controller 	= $application->useController( $router->controller );

Logger::debug('Application', 'Instantiated');
Logger::debug('Raw URI', $router->raw_uri );
Logger::debug('URI', $router->uri );

/*
 * ----------------------------------------------------------------------
 * Route Application: Route to appropriate controller based on request.
 * ----------------------------------------------------------------------
 */
if(
	empty( $requested_controller )
	|| (
		$requested_controller->useMethod
		(
			$default_settings['http_access_prefix'] . $router->method,
			$router->variable
		)
	) === false
)
{
	if( !empty( $routes['internal']['404'] ) )
	{
		$error_router = new Router( $routes['internal']['404'] );
		$application->useController( $error_router->controller )
			->useMethod( $error_router->method, $error_router->variable );
	}
	else
	{
		$response = new Response();
		$response->send( '404' );
		$application->error( '404', $data = null, $direct_include = true );
	}
}

/*
 * ----------------------------------------------------------------------
 * Debug: Recording
 * ----------------------------------------------------------------------
 */
$benchmark = new Benchmark();
$session   = new Session( false );
$request   = new Request();

Logger::debug('Memory Usage', $benchmark->memoryUsage() );
Logger::debug('Memory Peak Usage', $benchmark->peakMemoryUsage()  );
Logger::debug('Request Dump', get_object_vars( $request ) );
Logger::debug('Router Dump',  get_object_vars( $router ) );
Logger::debug('Session Dump', get_object_vars( $session ) );
Logger::debug('Application', 'Complete');

/*
 * ----------------------------------------------------------------------
 * Debug: Output
 * ----------------------------------------------------------------------
 */
Logger::showDebug();

?>
