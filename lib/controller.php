<?php

class Controller{

	function __construct(){
	}

	// useController - Setup controller
	//
	// $request: The Request URI passed from index.php
	// This loads the controller, its method and inputs the variables 
	// if they have been set in the request.  

	function useController($request){

		// Make request request a controller object.
		$this -> request = $request;

		// Set default request controller to default controller set in config.php 
		$this -> request['controller'] = ( empty( $request['controller'] ) ) ? DEFAULT_CONTROLLER : $request['controller'];

		// Define the controller filename and classname using the name given in the request. 
		// If method exists execute, otherwise call index() method.
		// Set parameter to null if not defined in request. 
		// Set controller properties and assign default model;
		// If request['variable'] has a + sign in it, treat as paramaters seperated by +'s
		// and pass to function using call_user_func_array

		$controller_file = SERVER_ROOT . '/controllers/' . $this -> request['controller'] . '.php';
		$controller_class = ( $this -> request['controller'] ."Controller" );
		$controller_name = strtolower($this -> request['controller']);

		if ( file_exists($controller_file) ) { 
			require_once($controller_file);

			$controller = new $controller_class; 
			$controller -> name = $controller_name;
			$controller -> classname = $controller_class;
			$controller -> filename = $controller_file;
			$controller -> useModel();

			if ( isset($request['method'] ) && ( method_exists($controller, $this -> request['method'])) ) {
				if ( isset($this -> request['variable']) ) {
					if ( preg_match('/\+/', $this -> request['variable'] ) ) {
						call_user_func_array( 
								Array($controller,$request['method']) , 
								explode('+', $request['variable'] )
								);
					}else{
						$controller -> { $this -> request['method'] }( $this -> request['variable'] );
					}
				} else {
					$controller -> { $this -> request['method'] }();
				}
			} else {
				$controller -> index();
			}
		} else {
			echo "$controller_file does not exist";
		}
	}

	// useView - Displays views. 
	//
	// $view: The name of the view file. Defaults to the controller's class name
	// 	(lowercase without 'Controller') as a dir and index as the view filename.
	// $data: The data to send. Defaults to the controller's model's data. 
	// $template: The template file the view will be incorporated into. Defaults to views/tpl/template.php

	function useView($view = null, $data = null, $template = 'template'){
		$view = ( isset($view) ) ?  $view : $this-> name . '/' . 'index';
		$data = ( isset($data) ) ?  $data : $this -> model -> data ;
		require_once( SERVER_ROOT . '/views/tpl/' . $template . '.php');
	}

	// useModel - Defines model.
	//
	// $model: The name of the model file.
	// Defines a model object as a property of the controller.

	function useModel($model = null){
		$model = ( isset($model) ) ?  $model : $this -> name;
		require_once( SERVER_ROOT . '/models/' . $model . '.php');
		$this -> model = new $model;
	}
}

?>
