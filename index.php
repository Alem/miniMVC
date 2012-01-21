<?php
// 
// This is the main controller for the application
// (c) Alem

// Error reporting
error_reporting('-1');
ini_set('display_errors',1);

// Defines path, DB configurations, etc.
require_once('config.php');

// Contains essential controller and model classes
require_once('lib/controller.php');
require_once('lib/model.php');

// Get the controller, method and variable from URL
// each delimited by the forward slash '/'
$parsed_request = explode( '/', $_SERVER['QUERY_STRING'] );
$request['controller'] = $parsed_request[0];
$request_size = count( $parsed_request );

if ( $request_size > 1 ){
	$request['method'] = $parsed_request[1];
}

if( $request_size > 2 ){
	$request['variable'] = $parsed_request[2];
}

// Instantiate appropriate controller based on request.
$application = new Controller();
$application -> useController($request);

?>
