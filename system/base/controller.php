<?php
/**
 * Controller class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The controller class is the parent for all application controllers.
 *
 * It grants the ability to run a method or  load & assign a model, view,
 * module or even another controller.
 *
 * The entire application is initiated as an instance of this class.
 *
 */
class Controller{


	/**
	 * @var string The basename of the controller ( Lowercase, no suffix )
	 */
	public $name;


	/**
	 * @var string Stores information about resources. (i.e. paths of Model, Views)
	 *
	 */
	public $loaded = array();


	/**
	 * __construct
	 */
	public function __construct( ){

	}


	/**
	 * useMethod - Calls appropriate method of controller.
	 *
	 * The default/fallback method is 'index()', and variable
	 * is only passed to the method if set. 
	 *
	 * If the supplied variable contains the VAR_SEPARATOR then it is treated as multiple separate variables
	 * and passed as an array using call_user_func_array.
	 *
	 * @param string $method 	The method to call
	 * @param mixed  $variable 	The variable(s) to be passed to the controller, as a string/array.
	 * @param object $controller 	The controller object. Defaults to the current controller.
	 */
	public function useMethod ( $method, $variable = null , $controller = null ) {

		if ( !isset( $controller ) )
			$controller =& $this;

		if ( !( isset( $method )  &&  method_exists( $controller, $method ) ) )
			$controller -> { HTTP_ACCESS_PREFIX . DEFAULT_METHOD }();
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
	 * prg - Post Redirect Get
	 * 
	 * A simple fix for prevent Database modification by re-send on a browser 'back' or 'refresh'
	 *
	 * @param string $method 	The controller method to call; the page to redirect to (ex. index)
	 * @param mixed  $variables 	The variables to be passed to the method.
	 * @param string $controller 	The controller the method belongs to. Defaults to the URI requested controller if $method is specified.
	 */
	public static function prg( $method = null, $variables = null, $controller = null ){

		if ( !isset( $controller ) && isset( $method )  )
			$controller = CONTROLLER;

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


	/**
	 * useController - Loads the named controller as a property of the controller that called it.
	 *
	 * Uses the Load::toObject method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the controller to load
	 * @return Controller 		The loaded controller.
	 * @uses   load::toObject 	Assigns instance of the named controller to current controller.
	 */
	public function useController( $name ){

		$controller = load::toObject ( $this, 'controller', $name );

		if ( isset( $controller ) )
			$controller -> name = $name;
		else
			$this -> prg( null, null, DEFAULT_CONTROLLER );

		return $controller;
	}


	/**
	 * model - Loads the named model as a property of the controller that called it.
	 *
	 * Uses the Load::toObject method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the model to load. Defaults to the name of the controller.
	 * @return Model 		The loaded model.
	 * @uses   load::toObject 	Assigns the model instance to the controller.
	 */
	public function model( $name = null ){
		if ( !isset( $name ) )
			$name = $this -> name;

		return load::toObject( $this,  'model', $name );
	}


	/**
	 * module - Loads the named module as a property of the controller that called it.
	 *
	 * Uses the Load::toObject method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * If the class exists in a subdirectory, the name should follow the form: 'subdir/name'
	 * 	ex. for applications/default/modules/base/menu.php   The name would be 'base/menu'
	 *
	 * @param  string $name 	The name of the module to load.
	 * @param  bool   $instantiate 	If set to true, the module is assumed to be a class by the 
	 * 					same name and instantiated. Otherwise simply include.
	 * @return object 		The loaded module.
	 * @uses   load::toObject 	Assigns the module instance to the controller.
	 */
	public function module( $name, $instantiate = true ){
		$module = load::toObject( $this, 'module', $name, $instantiate );
		if (isset ( $module ) )
			$module -> owner =& $this; //todo Remove this
		return $module;
	}


	/**
	 * library - Loads the named library as a property of the controller that called it.
	 *
	 * Uses the Load::toObject method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the library to load.
	 * @param  bool   $instantiate 	If set to true, the module is assumed to be a class by the 
	 * 					same name and instantiated. Otherwise simply include.
	 * @return object 		The loaded library.
	 * @uses   load::toObject 	Assigns the library instance to the controller.
	 */
	public function library( $name, $instantiate = true ){
		return $library = load::toObject( $this, 'library', $name, $instantiate );
	}


	/**
	 * template - Simply sets the template to be used by the view.
	 *
	 * The template comprises the larger HTML structure the view will be loaded into.
	 *
	 * @param string $name 		The name of the template to set. Defaults to the constant DEFAULT_TEMPLATE set in the application config
	 * @return Controller 		The current controller.
	 * @uses   load::path()		Returns file path for the template.
	 */
	public function template( $name ){
		$this -> loaded['template']['path'] = Load::path('template', $name );
		return $this;
	}

	
	/**
	 * view - Sets the view to be incorporated into the template then includes the template.
	 *
	 * The template requires the view path to be set before it is included. Once defined, 
	 * the template can be included and it will then include the view.
	 *
	 * @param sting  $view 			The name of the view to load. Defaults to the value of DEFAULT_METHOD set in the application config.
	 * @param string $controller_name 	The name of the controller the view belongs to. Defaults to the current controller.
	 * @param object $model 			The model to be passed to the view for interaction/reading. Defaults to the current model.
	 * @return object 			The current object.
	 * @uses   load::path()			Returns file path for the view.
	 *
	 * @todo Determine if needless object copying/cloning occuring
	 */
	public function view($view = DEFAULT_METHOD, $controller_name = null, $model = null){

		if ( !isset( $controller_name ) )
			$controller_name = $this -> name;

		if ( !isset( $model ) )
			$model = $this -> model();

		$this -> loaded['view']['main']['path'] = Load::path('view', $controller_name . '/'. $view );
		
		if (  !isset( $this -> loaded['template']['path'] ) )
			$this -> template ( DEFAULT_TEMPLATE );

		require_once( $this -> loaded['template']['path'] );

		return $this;
	}


}

?>
