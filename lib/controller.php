<?php

class Controller{

	public $name;
	public $classname;
	public $filename;

	// Constructor
	//
	// Auto-loads the appropriate model and module, determines and assigns the base name for the controller as the
	// class property 'name'. Only executes for extended/non-root controllers, not for the application controller.
	// Can be disabled by false set $autoload
	//

	function __construct( $model = true, $module = true ){
		if ( get_parent_class($this)){
			$this -> name = strtolower(str_replace('Controller','',get_class($this)));
				$this -> useModel();
				$this -> useModule();
		}
	}

	// useController - Process request and setup controller 
	//
	// Recieves URL request, constructs the appropriate controller and calls appropriate method.
	//
	// The default/fallback controller is config constant DEFAULT_CONTROLLER, the default/fallback method is 'index()',
	// and the parameter is simply not passed to the method if not set. 
	// If the supplied variable contains the VAR_SEPARATOR then it is treated as multiple
	// seperated parameters and passed as an array using call_user_func_array.
	//
	// This defines the child controller's name, classname, filename properties and assigns its model.
	//
	// $request: The Request URI passed from index.php
	// This loads the controller, its method and inputs the variables 
	// if they have been set in the request.  
	//
	// $assign: If set to true, will assign the newly created controller 
	// 		as a property of the controller that called it.

	function useController($request, $assign = null){

		$this -> request = $request;
		$this -> request['controller'] = ( empty( $request['controller'] ) ) ? 
			DEFAULT_CONTROLLER : $request['controller'];
		$controller_file = SERVER_ROOT . DEFAULT_CONTROLLER_PATH . $this -> request['controller'] . '.php';
		$controller_class = ( $this -> request['controller'] ."Controller" );
		$controller_name = strtolower($this -> request['controller']);

		if ( file_exists($controller_file) ) { 
			require_once($controller_file);
			$controller = new $controller_class; 
			if ( $assign === true )
				$this -> $controller_name = $controller;

			if ( isset($request['method'] ) && ( method_exists($controller, $this -> request['method'])) ) {
				if ( isset($this -> request['variable']) ) {
					if ( strpos($this -> request['variable'], VAR_SEPARATOR ) !== false ) {
						call_user_func_array( 
							Array($controller,$request['method']) , 
							explode( VAR_SEPARATOR , $request['variable'] )
						);
					}else
						$controller -> { $this -> request['method'] }( $this -> request['variable'] );
				} else 
					$controller -> { $this -> request['method'] }();
			} elseif ($assign !== true) 
				$controller -> { DEFAULT_METHOD }();
		} else 
			echo "$controller_file does not exist";
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

	// useModule - Defines module.
	//
	// This method is used by controllers to load the modules as an object property.
	// The module defaults to the list DEFAULT_MODULES from config.php, 
	// additional modules can be added by supplying their names.
	//
	// $module: An string or array containing the name/names of any additional module.

	function useModule($modules = null){
		if (DEFAULT_MODULES == null)
			return false;
		$defaults = explode(",", DEFAULT_MODULES);
		if( isset($modules) ){
			if (is_array($modules) )
				array_merge($modules, $defaults); 
			else{
				$defaults[] = $modules; 
				$modules = $defaults;
			}
		}else
			$modules =  $defaults;
		foreach( $modules as $module ){
			require_once( SERVER_ROOT . DEFAULT_MODULE_PATH . $module . '.php');
			$this -> $module = new $module;
		}
	}


	// prg - Post Redirect Get
	//
	// A simple fix for prevent Database modification by re-send on a browser 'back' or 'refresh'
	//
	// $method - The controller method to call AKA the page to redirect to (ex. index)
	// $controller - The controller the method belongs to. Defaults to the current controller.

	function prg( $method = null, $controller = null ){
		$controller = ( isset($controller) ) ?  $controller : $this -> name;
		header("Location: ?" . $controller . '/' . $method, 303);
	}


}

?>
