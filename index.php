<?php

## 
## This is the main controller for the application
## (c) Alem

# Defines path, DB configurations, etc.
require_once('config.php');

# Contains Controller Class
require_once('lib/controller.php');

# Get Controller & Method from URL
$parseduri = explode( '/', $_SERVER['QUERY_STRING'] );
$uri['controller'] = $parseduri[0];
$uri['method'] = $parseduri[1];
$uri['var'] = $parseduri[2];

# Instantiate Controller class
$controller = new Controller($uri);

# Load requested controller
$controller -> useController($uri['controller']);

# Debug Index 
echo "<br/><br/><br/><br/><br/><h3>Index Debug</h3>";
echo "Controller: " . $uri['controller'] . "<br/>";
echo "Method: " . $uri['method'] . "<br/>";
echo "Variable: " . $uri['var'] . "<br/>";

?>
