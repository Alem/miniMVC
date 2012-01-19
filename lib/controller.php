<?php

class Controller{

	// __construct - Setup controller
	//
	// $query: The Request URI passed from index.php
	// This loads the controller, its method and inputs the variables 
	// if they have been set in the query.  

	function __construct($query){

		// Make request query a controller object.
		$this->query = $query;

		// Set default query controller to 'default.php'.
		$this -> query['controller'] = ( empty( $query['controller'] ) ) ? 'default' : $query['controller'];

		// Define the controller filename and classname using the name given in the query. 
		// If method exists execute, otherwise call index() method.
		// Set parameter to null if not defined in query. 

		$controller_file = SERVER_ROOT . '/controllers/' . $this->query['controller'] . '.php';
		$controller_class = ( $this -> query['controller'] ."Controller" );

		if ( file_exists($controller_file) ) { 
			require_once($controller_file);
			$controller = new $controller_class; 
			if ( isset($query['method']) && (method_exists($controller, $this -> query['method'])) ) {
				if ( isset($this->query['variable']) ) {
					$controller -> { $this -> query['method'] }( $this -> query['variable'] );
				}else{
					$controller -> { $this -> query['method'] }();
				}
			}else{
				$controller -> index();
			}
		}else{
			echo "$controller_file does not exist";
		}
	}

	// useView - Displays views. 
	//
	// $view: The name of the view file. Defaults to the controller's class name
	// 	(lowercase without 'Controller') as a dir and index as the view filename.
	// $data: The data to send. Defaults to the controller's model's data. 
	// $template: The template file the view will be incorporated into.
	
	function useView($view=null, $data='', $template = 'template'){
		$controller_name = strtolower(str_replace('Controller','',get_class($this)));
		$view = ( isset($view) ) ?  $view : $controller_name.'/'.'index';
		$data = ( isset($data) ) ? $this -> model -> data : $data;
		require_once( SERVER_ROOT . '/views/tpl/' . $template . '.php');
	}

	// useModel - Defines model.
	//
	// $model: The name of the model file.
	// Defines the model class as a property of the controller.

	function useModel($model){
		require_once( SERVER_ROOT . '/models/' . $model . '.php');
		$this -> model = new $model;
	}
}

?>
