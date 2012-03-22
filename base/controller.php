<?php

/**
 * Controller class file.
 *
 * @author Zersenay Alem <info@alemmedia.com>
 * @link http://www.alemmedia.com/
 * @copyright Copyright &copy; 2008-2012 Alemmedia
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
	 * Constructor
	 *
	 * Auto-loads the appropriate model and module, determines and assigns the base name for the controller as the
	 * class property 'name'. Only executes for extended/non-root controllers, not for the application controller.
	 * Can be disabled by false set $autoload
	 * 
	 * @param bool $model If set to true, the appropriate model will be automatically loaded
	 * @param bool $module If set to true, the default modules will be automatically loaded
	 *
	 */

	function __construct( $model = true, $module = true ){
		if ( get_parent_class($this)){
			$this -> name = strtolower(str_replace( 'Controller', '', get_class($this) ));
			$this -> useModel( null, false );
			$this -> useModule( null, true, true );
		}
	}


	/**
	 * useController - Recieves URL request, constructs the appropriate controller and calls appropriate method. 
	 *
	 * The default/fallback controller is config constant DEFAULT_CONTROLLER, the default/fallback method is 'index()',
	 * and the parameter is simply not passed to the method if not set. 
	 * If the supplied variable contains the VAR_SEPARATOR then it is treated as multiple
	 * seperated parameters and passed as an array using call_user_func_array.
	 *
	 * This defines the child controller's name, classname, filename properties and assigns its model.
	 *
	 * @param mixed $request The Request URI array passed from index.php or a string naming the controller to be loaded 
	 * @param bool $assign If set to true, will assign the newly created controller as a property of the controller that called it.
	 * @return object
	 *
	 */

	function useController($request, $assign = false){

		if ( !is_array( $request ) )
			$request = array( 'controller' => $request );
		elseif( empty( $request['controller'] ) ) {
			$request['controller'] = DEFAULT_CONTROLLER;
		}

		$controller_name = strtolower( $request['controller'] );
		$controller_class = $controller_name . "Controller";
		$controller_file = SERVER_ROOT . DEFAULT_APPLICATION_PATH .  DEFAULT_CONTROLLER_PATH . $controller_name . '.php';

		if ( !file_exists( $controller_file ) ) 
			$this -> prg();
		else{
			require_once( $controller_file );
			$controller = new $controller_class; 
			if ( $assign )
				return $this -> $controller_name = $controller;

			if ( !( isset( $request['method'] )  &&  method_exists( $controller, $request['method'] ) ) )
				$controller -> { DEFAULT_METHOD }();
			else{
				if ( !isset( $request['variable'] ) ) 
					$controller -> { $request['method'] }();
				else{
					if ( strpos( $request['variable'] , VAR_SEPARATOR ) === false ) 
						$controller -> { $request['method'] }( $request['variable'] );
					else{
						call_user_func_array( 
							array(
								$controller, 
								$request['method']
							), 
							explode( VAR_SEPARATOR , $request['variable'] )
						);
					}
				}
			} 
		} 

		return $this;
	}


	/**
	 * useView - Displays views. 
	 *
	 * This method is used by controllers to output specified html files with any included data.
	 *
	 * The default view is the controller's index view (ex. views/example/index.php),
	 * the default template is views/tpl/"DEFAULT_TEMPLATE".php, where DEFAULT_TEMPLATE is a config.php constant.
	 *
	 * @param string $view The name of the view file. Defaults to the controller's class base name as dir and index as view filename.
	 * @param string $template The template file the view will be incorporated into. Defaults to DEFAULT_TEMPLATE.
	 * @return object The current object
	 *
	 */

	function useView($view = null, $controller = null, $template = DEFAULT_TEMPLATE, $model = null){
		$controller = ( isset($controller) ) ?  $controller : $this -> name;
		$view = ( isset($view) ) ?  $controller.'/'.$view : $this-> name . '/' . 'index';

		if ( !isset($model) && isset ( $this -> model ) )  
			$model =& $this -> model;

		if ( $template )
			require_once( SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_TEMPLATE_PATH . $template . '.php');
		else
			require( SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_VIEW_PATH . $view . '.php');

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

	function useModel( $model = null , $eponym = true ){
		$model = ( isset($model) ) ?  $model : $this -> name;
		if ( file_exists(  SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_MODEL_PATH . $model . '.php' ) ) {
			require_once( SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_MODEL_PATH . $model . '.php');
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
	 * @param mixed $module An string or array containing the name/names of any additional module.
	 * @param bool $assign If set to true, will assign the module as a property of the controller that called it.
	 * @param bool $load_defaults If set to true, will load the default modules set in the application config file.
	 *
	 */

	function useModule( $modules = null, $assign = true , $load_defaults = false ){

		if ( $modules == null && DEFAULT_MODULES == null)
			return false;

		if ( defined( 'DEFAULT_MODULES' ) && ( $load_defaults ) )
			$loadable_modules = explode( ',', DEFAULT_MODULES );
		elseif ( is_array ( $modules ) )
			$loadable_modules =& $modules;
		else
			$loadable_modules[] = $modules;

		foreach( $loadable_modules as $module_path ){
			require_once( SERVER_ROOT .  DEFAULT_APPLICATION_PATH . DEFAULT_MODULE_PATH . $module_path . '.php');
			$sub_path = explode( '/' , $module_path );

			$module =& $sub_path[1];
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
	 * @param mixed $variable The variables to be passed to the method.
	 * @param string $controller The controller the method belongs to. Defaults to the current controller if $method is specified.
	 *
	 */

	function prg( $method = null, $variables = null, $controller = null ){
		if ( !isset($controller) && isset( $method )  )
			$controller =& $this -> name;

		if ( isset( $method ) ){
			$location = $controller . '/' . $method;
			if ( is_array( $variables ) )
				$location .= '/' . implode( VAR_SEPARATOR, $variables);
			elseif ( isset( $variables) )
				$location .= '/' . $variables;
		}else
			$location =& $controller;

		header("Location: " . WEB_ROOT . $location, 303);
	}


}

?>
