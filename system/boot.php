<?php
/**
 * Boot script file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Boot script is required by all application 'index.php' files.
 *
 * It manages the benchmarking, loads the system classes, configures the logger,
 * parses the request, and instantiates the root controller,
 * runs the request and outputs the debug.
 *
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
	'auth/accessControl',
	'base/benchmark',
	'base/config',
	'base/load',
	'base/router',
	'components/controller',
	'components/model',
	'lib/file',
	'cache/icache',
	'cache/fileCache',
	'database/database',
	'database/dbQuery',
	'database/queryBuilder',
	'log/logger',
	'server/request',
	'server/response',
	'session/session',
	'util/arrayUtility',
	'web/html',
	'web/element',
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
if (
	empty( $requested_controller )
	|| (
		$requested_controller->useMethod
		(
			$default_settings['http_access_prefix'] . $router->method  ,
			$router->variable
		)
	) === false
)
{
	$response = new Response();
	$response->send( '404' );
	$application->error( '404', array( 'config' => $default_settings) );
}

/*
 * ----------------------------------------------------------------------
 * Debug: Recording
 * ----------------------------------------------------------------------
 */
$benchmark = new Benchmark();

Logger::debug('Memory Usage', $benchmark->memoryUsage() );
Logger::debug('Memory Peak Usage', $benchmark->peakMemoryUsage()  );
Logger::debug('Application', 'Complete');

/*
 * ----------------------------------------------------------------------
 * Debug: Output
 * ----------------------------------------------------------------------
 */
Logger::showDebug();

?>
