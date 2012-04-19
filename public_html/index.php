<?php
// index.php - The bootup script for the application
// (c) Alem

// Script-timing
$timer_start = microtime(true);

// Defines path, DB configurations, etc.
require_once('../config/main.php');

// Require all lib/ files
foreach ( explode( ',' , LIBRARY_CLASSES ) as $classname )
	require_once( SERVER_ROOT . DEFAULT_SYSTEM_PATH . $classname  . '.php' );

// Get the controller, method and variable from URL
$request = new Request();
$request -> process();

// open appropriate controller based on request.
$application = new Controller();
$application -> useController( CONTROLLER ) ->  useMethod ( HTTP_ACCESS_PREFIX . METHOD  ,  VARIABLE );

// Script-timing completion
$timer_end = microtime(true);
$time = $timer_end - $timer_start;

// Debug Recording
Debug::open() -> record['Script Time'] = $time;
Debug::open() -> record['Memory Usage'] = ( memory_get_usage() / 1000 ) . ' kb';
Debug::open() -> record['Memory Peak Usage'] = ( memory_get_peak_usage() / 1000 ) . ' kb';

//Logging Output
Debug::open() -> display();
?>
