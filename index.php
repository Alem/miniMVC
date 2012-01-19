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
$parsedquery = explode( '/', $_SERVER['QUERY_STRING'] );
$query['controller'] = $parsedquery[0];
$query_size = count( $parsedquery );

if ( $query_size > 1 ){
	$query['method'] = $parsedquery[1];
}

if( $query_size > 2 ){
	$query['variable'] = $parsedquery[2];
}

// Instantiate appropriate controller based on query.
$application = new Controller($query);

?>
