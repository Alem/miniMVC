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
class Controller
{


	/**
	 * @var string The basename of the controller ( Lowercase, no suffix )
	 */
	public $name;


	/**
	 * @var object Holds load object.
	 */
	public $load = null;


	/**
	 * @var string Stores data related to loaded resources. (i.e. objects of Model, paths of Views)
	 */
	public $loaded = array();


	/**
	 * useMethod - Calls appropriate method of controller.
	 *
	 * The default/fallback method is 'index()', and variable
	 * is only passed to the method if set. 
	 *
	 * If the supplied variable contains the VAR_SEPARATOR
	 * then it is treated as multiple separate variables
	 * and passed as an array using call_user_func_array.
	 *
	 * @param string 	$method 	The method to call
	 * @param mixed 	$variable 	The variable(s) to be passed to the controller as string/array.
	 * @param Controller 	$controller 	The controller object. Defaults to the current controller.
	 */
	public function useMethod ( $method, $variable = null , Controller $controller = null )
	{
		if ( !isset( $controller ) )
			$controller =& $this;

		if ( method_exists( $controller, $method ) === false )
			return false;
		else
		{
			if ( !isset( $variable ) ) 
				$controller -> { $method }();

			else
			{
				if ( strpos( $variable , VAR_SEPARATOR ) === false ) 
					$controller -> $method( $variable );
				else
				{
					call_user_func_array( 
						array(
							$controller, 
							$method
						), 
						explode( VAR_SEPARATOR , $variable )
					);
				}
			}

			return true;
		} 
	}


	/**
	 * fetchName() - Returns name of controller
	 *
	 * @return string 	Name of controller
	 */
	public function fetchName()
	{
		$fullname  = get_called_class();
		$controller_pos = strpos( $fullname, 'Controller');
		$shortname = strtolower( substr( $fullname, 0, $controller_pos ) );
		return $shortname;
	}


	/**
	 * prg - Post Redirect Get
	 * 
	 * A simple fix for prevent Database modification by re-send on a browser 'back' or 'refresh'
	 *
	 * @param string $method 	The controller method to call; the page to redirect to (ex. index)
	 * @param mixed  $variables 	The variables to be passed to the method.
	 * @param string $controller 	The controller the method belongs to. Defaults to the URI requested controller if $method is specified.
	 * @param string $web_root 	The root of the web server 
	 */
	public function prg( $method = null, $variables = null, $controller = null, $web_root = null )
	{
		if ( !isset( $controller ) && isset( $method )  )
			$controller = $this -> fetchName();

		if ( isset( $method ) )
		{
			$location = $controller .URI_SEPARATOR  . $method;

			if ( is_array( $variables ) )
				$location .= URI_SEPARATOR . implode( VAR_SEPARATOR, $variables);

			elseif ( isset( $variables) )
				$location .= URI_SEPARATOR . $variables;
		}
		else
			$location = $controller;

		if ( !isset ($web_root) )
		{
			$config = new Config();
			$settings = $config -> load ('application');
			$web_root = $settings['web_root'];
		}

		header( 'Location: ' . $web_root . $location, 303 );
	}


	/*
	 * Methods below provide convenience wrappers for the factory class 'Load'
	 */

	/**
	 * Load - Returns load object or Sets load object as the property 'load'
	 *
	 * @param Load $load 	The load object
	 * @return Load 	The load object
	 */
	public function load()
	{
		if ( !isset ( $this -> load ) )
			$this -> load = new Load();

		return $this -> load;
	}

	/**
	 * loader - Uses load::component to load an instance of the named class to the controller 
	 *
	 * Instantiates the named class if not already assigned to the supplied object and returns the new object property.
	 * Otherwise it returns the named object property.
	 *
	 * @param string $type 		The type of component
	 * @param string $name 		The name of the component
	 * @return object 		The instance of the component
	 */
	public function loader( $type , $name, $instantiate = true )
	{
		if ( !isset ( $this -> loaded[ $type ][$name] ) )
			$this -> loaded[$type][$name] = $this -> load() -> component( $type , $name, $instantiate );

		return $this -> loaded[$type][$name];
	}



	/**
	 * useController - Loads the named controller as a property of the controller that called it.
	 *
	 * Uses the Controller::loader method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the controller to load
	 * @return Controller 		The loaded controller.
	 * @uses   Controller::loader 	Assigns instance of the named controller to current controller.
	 */
	public function useController( $name )
	{
		$controller = $this -> loader( 'controller', $name );

		if ( isset( $controller ) )
			$controller -> name = $name;

		return $controller;
	}


	/**
	 * model - Loads the named model as a property of the controller that called it.
	 *
	 * Uses the Controller::loader method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the model to load. Defaults to the name of the controller.
	 * @return Model 		The loaded model.
	 * @uses   Controller::loader 	Assigns the model instance to the controller.
	 */
	public function model( $name = null )
	{
		if ( !isset( $name ) )
			$name = $this -> name;

		return $this -> loader( 'model', $name );
	}


	/**
	 * module - Loads the named module as a property of the controller that called it.
	 *
	 * Uses the Controller::loader method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * If the class exists in a subdirectory, the name should follow the form: 'subdir/name'
	 * 	ex. for applications/default/modules/base/menu.php   The name would be 'base/menu'
	 *
	 * @param  string $name 	The name of the module to load.
	 * @param  bool   $instantiate 	If set to true, the module is assumed to be a class by the 
	 * 					same name and instantiated. Otherwise simply include.
	 * @return object 		The loaded module.
	 * @uses   Controller::loader 	Assigns the module instance to the controller.
	 */
	public function module( $name, $instantiate = true )
	{
		return $module = $this -> loader( 'module', $name, $instantiate );
	}


	/**
	 * library - Loads the named library as a property of the controller that called it.
	 *
	 * Uses the Controller::loader method which uses the Load::component() method to 
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the library to load.
	 * @param  bool   $instantiate 	If set to true, the module is assumed to be a class by the 
	 * 					same name and instantiated. Otherwise simply include.
	 * @return object 		The loaded library.
	 * @uses   Controller::loader 	Assigns the library instance to the controller.
	 */
	public function library( $name, $instantiate = true )
	{
		return $library = $this -> loader( 'library', $name, $instantiate );
	}


	/**
	 * template - Simply sets the template to be used by the view.
	 *
	 * The template comprises the larger HTML structure the view will be loaded into.
	 *
	 * @param string $name 		The name of the template to set.
	 * @return Controller 		The current controller.
	 * @uses   load::path()		Returns file path for the template.
	 */
	public function template( $name )
	{
		$this -> loaded['template']['path'] = $this -> load() -> path('template', $name );
		return $this;
	}


	/**
	 * view - Sets the view to be incorporated into the template then includes the template.
	 *
	 * The template requires the view path to be set before it is included. Once defined, 
	 * the template can be included and it will then include the view.
	 *
	 * @param sting  $view 			The name of the view to load. 
	 * @param string $controller_name 	The name of the controller the view belongs to. Defaults to the current controller.
	 * @param Model  $data 			The data to be passed to the view for interaction/reading. Defaults to the current model.
	 * @param bool 	 $direct_include 	If set to true, includes the view directly, rather than loading the template then view.
	 * @return object 			The current object.
	 * @uses   load::path()			Returns file path for the view.
	 *
	 * @todo Determine if needless object copying/cloning occuring
	 */
	public function view( $view, $data = null, $controller_name = null, $direct_include = false )
	{

		if ( !isset( $controller_name ) )
			$controller_name = $this -> name;

		if ( !isset( $data ) && isset( $this -> model()-> data ) )
			$data = $this -> model() -> data;

		$this -> loaded['view']['main']['path'] = $this -> load() -> path('view', $controller_name . '/'. $view );

		if (  !isset( $this -> loaded['template']['path'] ) )
		{
			$config = new Config();
			$settings = $config -> load ('application');
			$this -> template ( $settings['default_template'] );
		}

		if ( $direct_include === false )
			require_once( $this -> loaded['template']['path'] );
		else
			require( $this -> loaded['view']['main']['path'] );

		return $this;
	}


}

?>
