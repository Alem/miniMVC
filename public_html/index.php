<?php
/**
 * Index entry script.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 *
 * Index.php is the bootup script for the application.
 * It loads the main configuration file, the system classes,
 * parses the request, and instantiates the root controller 
 * and runs the request.
 * 
 * It also performs simple timing of the application run for debugging.
 * 
 * Debug data is output ( if set ) at the end of the script.
 */


/*
 * ----------------------------------------------------------------------
 * Script-timing
 * ----------------------------------------------------------------------
 */
$timer_start = microtime(true);


/*
 * ----------------------------------------------------------------------
 * Defines path, DB configurations, etc.
 * ----------------------------------------------------------------------
 */
require_once('../config/main.php');


/*
 * ----------------------------------------------------------------------
 * Require all lib/ files
 * ----------------------------------------------------------------------
 */
foreach ( explode( ',' , LIBRARY_CLASSES ) as $classname )
	require_once( SERVER_ROOT . DEFAULT_SYSTEM_PATH . $classname  . '.php' );


/*
 * ----------------------------------------------------------------------
 * Get the controller, method and variable from URL
 * ----------------------------------------------------------------------
 */
$request = new Request();
$request -> process();


/*
 * ----------------------------------------------------------------------
 * Open appropriate controller based on request.
 * ----------------------------------------------------------------------
 */
$application = new Controller();
$application -> useController( CONTROLLER ) ->  useMethod ( HTTP_ACCESS_PREFIX . METHOD  ,  VARIABLE );


/*
 * ----------------------------------------------------------------------
 * Script-timing completion
 * ----------------------------------------------------------------------
 */
$timer_end = microtime(true);
$time = $timer_end - $timer_start;


/* 
 * ----------------------------------------------------------------------
 * Debug Recording
 * ----------------------------------------------------------------------
 */
Debug::open() -> record['Script Time'] = $time;
Debug::open() -> record['Memory Usage'] = ( memory_get_usage() / 1000 ) . ' kb';
Debug::open() -> record['Memory Peak Usage'] = ( memory_get_peak_usage() / 1000 ) . ' kb';


/*
 * ----------------------------------------------------------------------
 * Logging Output
 * ----------------------------------------------------------------------
 */
Debug::open() -> display();
?>
