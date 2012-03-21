<?php
//
// index.php - The bootup script for the application
// (c) Alem

// Script-timing
$timer_start = microtime(true);

// Defines path, DB configurations, etc.
require_once('../config/main.php');

// Require all lib/ files
foreach ( array( 'database', 'query','model','controller','debugger','session' ) as $classname ){
	require_once( SERVER_ROOT . DEFAULT_LIBRARY_PATH . $classname  . '.php' );
}

// Get the controller, method and variable from URL
// each delimited by the forward slash '/'
if ( isset( $argv[1] ) )
	define( 'URI', $argv[1] );
else
	define( 'URI', $_SERVER['QUERY_STRING'] );

$parsed_request = explode( '/', URI, 3);
$request_size = count( $parsed_request );

define( 'CONTROLLER' ,  $parsed_request[0] );
$request['controller'] = CONTROLLER;
if ( $request_size > 1 ){
	define( 'METHOD' ,  $parsed_request[1] );
	$request['method'] = METHOD;
}
if( $request_size > 2 ) {
	define( 'VARIABLE' ,  $parsed_request[2] );
	$request['variable'] = VARIABLE;
}

// Instantiate appropriate controller based on request.
$application = new Controller();
$application -> useController($request);

// Script-timing completion
$timer_end = microtime(true);
$time = $timer_end - $timer_start;
Debugger::instantiate() -> record['Script Time'] = $time;
Debugger::instantiate() -> record['Memory Usage'] = ( memory_get_usage() / 1000 ) . ' kb';
Debugger::instantiate() -> record['Memory Peak Usage'] = ( memory_get_peak_usage() / 1000 ) . ' kb';
Debugger::instantiate() -> record['CPU Usage'] = getrusage(); 

//Logging Output
Debugger::instantiate() -> display();

?>
