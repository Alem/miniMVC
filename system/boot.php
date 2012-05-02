<?php
/**
 * Boot script file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Boot script is required by all application 'index.php' files.
 *
 * It manages the benchmarking, loads the system classes,
 * parses the request, and instantiates the root controller,
 * runs the request and outputs the debug.
 *
 */

/*
 * ----------------------------------------------------------------------
 * Benchmark: Begin Script-timing
 * ----------------------------------------------------------------------
 */
$timer_start = microtime(true);


/*
 * ----------------------------------------------------------------------
 * Load System: Require all system/ files
 * ----------------------------------------------------------------------
 */
$system_classes = array ( 
	'auth/accessControl', 	'base/load', 		'web/request',		'db/database',
	'db/queryBuilder' , 	'base/model', 		'base/controller', 	'log/debug',
	'web/session', 		'cache/fileCache',	'web/html',		'web/element'
);

foreach ( $system_classes as $classname )
	require_once( SERVER_ROOT . DEFAULT_SYSTEM_PATH . $classname  . '.php' );


/*
 * ----------------------------------------------------------------------
 * Process Request: Declare controller, method & variable constants.
 * ----------------------------------------------------------------------
 */
$request = new Request();
$request -> process();


/*
 * ----------------------------------------------------------------------
 * Initiate Application: Load appropriate controller based on request.
 * ----------------------------------------------------------------------
 */
$application = new Controller();

if ( CONTROLLER === null )
	$application -> useController(DEFAULT_CONTROLLER) -> useMethod ( HTTP_ACCESS_PREFIX . DEFAULT_METHOD );
else{
	$requested_controller = $application -> useController( CONTROLLER ); 

	if ( empty( $requested_controller ) )
		$application -> prg( null, null, null );
	else
		$requested_controller ->  useMethod ( HTTP_ACCESS_PREFIX . METHOD  ,  VARIABLE );
}


/*
 * ----------------------------------------------------------------------
 * Benchmarking: Script-timing completion
 * ----------------------------------------------------------------------
 */
$timer_end = microtime(true);
$time = $timer_end - $timer_start;


/* 
 * ----------------------------------------------------------------------
 * Debug: Recording
 * ----------------------------------------------------------------------
 */
Debug::open() -> record['Script Time'] = $time;
Debug::open() -> record['Memory Usage'] = ( memory_get_usage() / 1000 ) . ' kb';
Debug::open() -> record['Memory Peak Usage'] = ( memory_get_peak_usage() / 1000 ) . ' kb';


/*
 * ----------------------------------------------------------------------
 * Debug: Output
 * ----------------------------------------------------------------------
 */
Debug::open() -> display();

?>
