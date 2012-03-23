<?php
//
// index.php - The bootup script for the application
// (c) Alem

// Script-timing
$timer_start = microtime(true);

// Defines path, DB configurations, etc.
require_once('../config/main.php');

// Require all lib/ files
foreach ( array( 'router','database', 'query','model','controller','debugger','session' ) as $classname ){
	require_once( SERVER_ROOT . DEFAULT_LIBRARY_PATH . $classname  . '.php' );
}

// Get the controller, method and variable from URL
$router = new Router();
$request = $router -> formatRequest();


// Instantiate appropriate controller based on request.
$application = new Controller();
$application -> useController( $request['controller'] ) 
		-> useMethod ( $request['method']  ,  $request ['variable'] );

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
