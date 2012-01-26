<?php

class Controller{

	public $name;
	public $classname;
	public $filename;
	
	function __construct(){
	}

	// useController - Process request and setup controller 
	//
	// Recieves URL request, constructs the appropriate controller and calls appropriate method.
	//
	// The default/fallback controller is config constant DEFAULT_CONTROLLER, the default/fallback method is 'index()', 
	// and the parameter is simply not passed to the method if not set. If the supplied variable has '+' signs 
	// then it is treated as multiple '+' seperated parameters and passed as an array using call_user_func_array.
	//
	// This defines the child controller's name, classname, filename properties and assigns its model.
	//
	// $request: The Request URI passed from index.php
	// This loads the controller, its method and inputs the variables 
	// if they have been set in the request.  
	// $assign: If set to true, will assign the newly created controller as a property of the controller that called it.

	function useController($request, $assign = null){

		$this -> request = $request;
		$this -> request['controller'] = ( empty( $request['controller'] ) ) ? DEFAULT_CONTROLLER : $request['controller'];
		$controller_file = SERVER_ROOT . DEFAULT_CONTROLLER_PATH . $this -> request['controller'] . '.php';
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

			if ( $assign === true ){
				$this -> $controller_name = $controller;
			}
		} else {
			echo "$controller_file does not exist";
		}
	}

	// useView - Displays views. 
	//
	// This method is used by controllers to output specified html files with any included data.
	//
	// The default view is the controller's index view (ex. views/example/index.php),
	// the default template is views/tpl/"DEFAULT_TEMPLATE".php, where DEFAULT_TEMPLATE 
	// is a config.php constant.
	//
	// $view: The name of the view file. Defaults to the controller's class name
	// 	(lowercase without 'Controller') as a dir and index as the view filename.
	// $template: The template file the view will be incorporated into. Defaults to DEFAULT_TEMPLATE.

	function useView($view = null, $controller = null, $template = DEFAULT_TEMPLATE){
		$controller = ( isset($controller) ) ?  $controller : $this -> name;
		$view = ( isset($view) ) ?  $controller.'/'.$view : $this-> name . '/' . 'index';
		require_once( SERVER_ROOT . DEFAULT_TEMPLATE_PATH . $template . '.php');
	}

	// useModel - Defines model.
	//
	// This method is used by controllers to set the appropriate model as the object property 'model'.
	// The model defaults to the model file matching the controller class' name.
	//
	// $model: The name of the model file.

	function useModel($model = null){
		$model = ( isset($model) ) ?  $model : $this -> name;
		require_once( SERVER_ROOT . DEFAULT_MODEL_PATH . $model . '.php');
		$this -> model = new $model;
	}

	// prg - Post Redirect Get
	//
	// A simple fix for prevent Database modification re-send on a browser 'back' or 'refresh'
	//
	// $method - The controller method to call AKA the page to redirect to (ex. index)
	// $controller - The controller the method belongs to. Defaults to the current controller.

	function prg( $method, $controller = null ){
		$controller = ( isset($controller) ) ?  $controller : $this -> name;
		header("Location: ?" . $controller . '/' . $method, 303);
	}
}

?>
