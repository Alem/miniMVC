<?php

/**
 * Controller class file.
 *
 * @author Zersenay Alem <info@alemmedia.com>
 */


class Controller{


	/**
	 * @var string The basename of the controller ( Lowercase, no suffix )
	 */
	public $name;


	/**
	 * @var string The classname of the controller ( Capitalized, 'Controller' suffix )
	 */
	public $classname;


	/**
	 * @var string The filename of the controller ( lowercase, no suffix )
	 */
	public $filename;


	/**
	 * @var string Stores information about resources. (i.e. paths of Model, Views)
	 */
	public $resources = array();


	/**
	 * Constructor
	 *
	 */
	public function __construct( ){

	}


	/**
	 * useMethod - Calls appropriate method of controller.
	 *
	 * The default/fallback method is 'index()', and the parameter is simply not passed to the method if not set. 
	 * If the supplied variable contains the VAR_SEPARATOR then it is treated as multiple
	 * seperated parameters and passed as an array using call_user_func_array.
	 *
	 * @param string $method     The method to call
	 * @param mixed  $variable   The variable(s) to be passed to the controller, as a string/array.
	 * @param object $controller The controller object. Defaults to the current controller.
	 *
	 */

	public function useMethod ( $method, $variable = null , $controller = null ) {

		if ( !isset( $controller ) )
			$controller =& $this;

		if ( !( isset( $method )  &&  method_exists( $controller, $method ) ) )
			$controller -> { DEFAULT_METHOD }();
		else{
			if ( !isset( $variable ) ) 
				$controller -> { $method }();

			else{
				if ( strpos( $variable , VAR_SEPARATOR ) === false ) 
					$controller -> $method( $variable );
				else{
					call_user_func_array( 
						array(
							$controller, 
							$method
						), 
						explode( VAR_SEPARATOR , $variable )
					);
				}
			}
		} 
	}


	/**
	 * useController - Recieves URL request, constructs the appropriate controller and calls appropriate method. 
	 *
	 * The default/fallback controller is config constant DEFAULT_CONTROLLER, 
	 *
	 * This defines the child controller's name, classname, filename properties and assigns its model.
	 *
	 * @param string $name The controller name. Defaults to the DEFAULT_CONTROLLER set in the application config.
	 * @param bool $load_model If set to true, the appropriate model will be automatically loaded
	 * @param bool $load_module If set to true, the default modules will be automatically loaded
	 * @return object
	 *
	 */

	public function useController( $name , $load_model = true, $load_modules = true ){

		if( empty( $name ) )
			$name = DEFAULT_CONTROLLER;

		$this -> resource['controller'][ $name ]['class'] = $name . "Controller";
		$this -> resource['controller'][ $name ]['path'] = SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_CONTROLLER_PATH . $name . '.php';

		if ( !file_exists( $this -> resource['controller'][ $name ]['path'] ) ) 
			$this -> prg();
		else{
			require_once( $this -> resource['controller'][ $name ]['path']  );
			$controller = new $this -> resource['controller'][ $name ]['class']; 

			$controller -> name = $name;

			if ( $load_model )
				$controller -> useModel( null, false );
			if ( $load_modules )
				$controller -> useModule( null, true, true );

			return $this -> $name = $controller;
		} 
	}


	/**
	 * useView - Displays views. 
	 *
	 * This method is used by controllers to output specified html files with any included data.
	 *
	 * The default view is the controller's index view (ex. views/example/index.php),
	 * the default template is views/tpl/"DEFAULT_TEMPLATE".php, where DEFAULT_TEMPLATE is a config.php constant.
	 *
	 * @param string $view       The name of the view file. Defaults to the controller's class base name as dir and index as view filename.
	 * @param string $controller The name of the controller. Defaults to the current controller.
	 * @param string $template   The template file the view will be incorporated into. Defaults to DEFAULT_TEMPLATE.
	 * @param string $model      The model the view will read data from. Defaults to the current controller's model.
	 * @return object The current object
	 *
	 */

	public function useView($view = null, $controller = null, $template = DEFAULT_TEMPLATE, $model = null){

		if ( !isset($controller) ) 
			$controller =& $this -> name;

		if ( !isset($view) )
			$view = 'index';

		if ( !isset($model) && isset ( $this -> model ) )  
			$model =& $this -> model;

		$this -> template_path = SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_TEMPLATE_PATH . $template . '.php';
		$this -> view_path = SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_VIEW_PATH .$controller . '/' . $view . '.php';

		if ( isset( $template ) )
			require_once( $this -> template_path );
		else
			require( $this -> view_path );

		return $this;
	}


	/**
	 * useModel - Loads model as property.
	 *
	 * This method is used by controllers to set the appropriate model as the object property 'model'.
	 * The model defaults to the model file matching the controller class' name.
	 *
	 * @param string $model The name of the model file.
	 * @param bool $eponym If set to true, uses name of model to assign model as property.
	 * @return object The current objects new object property
	 *
	 */

	public function useModel( $model = null , $eponym = true ){

		$model = ( isset($model) ) ?  $model : $this -> name;
		$this -> resource['model'][$model]['path'] = SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_MODEL_PATH . $model . '.php';

		if ( file_exists( $this -> resource['model'][$model]['path']  ) ) {
			require_once( $this -> resource['model'][$model]['path'] );

			if ( $eponym )
				return $this -> $model = new $model;
			else
				return $this -> model = new $model;
		}
	}


	/**
	 * useModule - Defines module.
	 *
	 * This method is used by controllers to load the modules as an object property.
	 * The module defaults to the list DEFAULT_MODULES from config.php, 
	 * additional modules can be added by supplying their names.
	 *
	 * @param mixed $modules An string or array containing the name/names of any additional module.
	 * @param bool $assign If set to true, will assign the module as a property of the controller that called it.
	 * @param bool $load_defaults If set to true, will load the default modules set in the application config file.
	 *
	 */

	public function useModule( $modules = null, $assign = true , $load_defaults = false ){

		if ( $modules == null && DEFAULT_MODULES == null)
			return false;

		if ( defined( 'DEFAULT_MODULES' ) && ( $load_defaults ) )
			$loadable_modules = explode( ',', DEFAULT_MODULES );

		elseif ( is_array ( $modules ) )
			$loadable_modules =& $modules;

		else
			$loadable_modules[] = $modules;

		foreach( $loadable_modules as $module_path ){
			$sub_path =  explode( '/' , $module_path );
			$module   =& $sub_path[1];

			$this -> resource['module'][$module]['group'] =&  $sub_path[2];
			$this -> resource['module'][$module]['path']  =  SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_MODULE_PATH . $module_path . '.php';

			require_once( $this -> resource['module'][$module]['path'] );

			if ( $assign ){
				$this -> $module = new $module;
				$this -> $module -> controller =& $this;
			}
		}

		return $this;
	}


	/**
	 * prg - Post Redirect Get
	 * 
	 * A simple fix for prevent Database modification by re-send on a browser 'back' or 'refresh'
	 *
	 * @param string $method The controller method to call AKA the page to redirect to (ex. index)
	 * @param mixed $variables The variables to be passed to the method.
	 * @param string $controller The controller the method belongs to. Defaults to the current controller if $method is specified.
	 *
	 */

	public function prg( $method = null, $variables = null, $controller = null ){

		if ( !isset( $controller ) && isset( $method )  )
			$controller =& $this -> name;

		if ( isset( $method ) ){
			$location = $controller .URI_SEPARATOR  . $method;

			if ( is_array( $variables ) )
				$location .= URI_SEPARATOR . implode( VAR_SEPARATOR, $variables);

			elseif ( isset( $variables) )
				$location .= URI_SEPARATOR . $variables;
		}else
			$location =& $controller;

		header( 'Location: ' . WEB_ROOT . $location, 303 );
	}


}

?>
