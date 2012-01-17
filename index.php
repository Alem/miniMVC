<?php

## 
## This is the main controller for the application
## (c) Alem

# Defines path, DB configurations, etc.
require_once('config.php');

# Get Controller & Method from URL
$parsedquery = explode( '/', $_SERVER['QUERY_STRING'] );
$query['controller'] = $parsedquery[0];
$query['method'] = $parsedquery[1];
$query['variable'] = $parsedquery[2];

# Contains Controller Class
require_once('lib/controller.php');

# Instantiate Controller class
$controller = new Controller($query);

# Load requested controller
$controller -> useController();

# Debug index 
echo "<br/><br/><br/><br/><br/>";
echo "<h3>Index Debug</h3>";
echo "Controller: " . $query['controller'] . "<br/>";
echo "Method: " . $query['method'] . "<br/>";
echo "Variable: " . $query['variable'] . "<br/>";

?>
