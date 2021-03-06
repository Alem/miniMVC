<?php
/**
 * Controller class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The controller class is the parent for all application controllers.
 *
 * It grants the ability to run a method or  load & assign a model, view,
 * module or even another controller.
 *
 * The entire application is initiated as an instance of this class.
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.components
 */
class Controller
{

	/**
	 * The name of the Controller object's unit and controller. ( Lowercase, no suffix )
	 *
	 * Unit name for 'foo': foo
	 * Controller name for 'foo': fooController
	 *
	 * @var string
	 */
	public $name = array(
		'unit' => null,
		'controller' => null
	);


	/**
	 * Holds load object.
	 * @var object
	 */
	public $load = null;


	/**
	 * Holds data related to loaded resources.(i.e. objects of Model, paths of Views)
	 * @var string
	 */
	public $loaded = array();


	/**
	 * __construct
	 *
	 * Runs defineName, defining the Controller::name property.
	 */
	public function __construct()
	{
		$this->defineName();
	}


	/**
	 * defineName() - Returns unit name of controller
	 *
	 * @return string 	Unit name of controller ( Basename, without 'Controller' )
	 */
	public function defineName()
	{
		$this->name['controller'] 	= get_called_class();
		$controller_pos 		= strpos( $this->name['controller'], 'Controller');
		$this->name['unit'] 		= strtolower( substr( $this->name['controller'], 0, $controller_pos ) );
		return $this->name['unit'];
	}


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
	public function useMethod( $method, $variable = null , Controller $controller = null )
	{
		if( !isset( $controller ) )
			$controller =& $this;

		if( method_exists( $controller, $method ) === false )
			return false;
		else
		{
			if( !isset( $variable ) )
				$controller->{ $method }();

			else
			{
				if( strpos( $variable , VAR_SEPARATOR ) === false )
					$controller->$method( $variable );
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
	 * prg - Post Redirect Get
	 *
	 * A simple fix for prevent Database modification by re-send on a browser 'back' or 'refresh'
	 *
	 * @param string $method 	The controller method to call; the page to redirect to(ex. index)
	 * @param mixed  $variables 	The variables to be passed to the method.
	 * @param string $controller 	The controller the method belongs to. Defaults to the URI requested controller if $method is specified.
	 * @param string $web_root 	The root of the web server
	 */
	public function prg( $method = null, $variables = null, $controller = null, $web_root = null )
	{
		if( !isset( $controller ) && isset( $method )  )
			$controller =  $this->defineName();

		if( isset( $method ) )
		{
			$location = $controller . URI_SEPARATOR . $method;

			if( is_array( $variables ) )
				$location .= URI_SEPARATOR . implode( VAR_SEPARATOR, $variables);

			elseif( isset( $variables) )
				$location .= URI_SEPARATOR . $variables;
		}
		else
			$location = $controller;

		if( !isset( $web_root ) )
		{
			$config = new Config();
			$settings = $config->fetch('application');
			$web_root = $settings['web_root'];
		}

		header( 'Location: ' . $web_root . $location, 303 );
	}


	/*
	 * Methods below provide convenience wrappers for the factory class 'Load'
	 */

	/**
	 * Load - Returns load object 
	 *
	 * Returns single instance of Load object for a controller.
	 * Optionally receives the parameter $paths to override
	 * default path settings for the Load object.
	 *
	 * @param array $paths 	The array of default file paths
	 * @return Load 	The load object
	 */
	public function load( $paths = null )
	{
		if( !isset( $this->load ) )
			$this->load = new Load();

		if( $paths !== null )
			$this->load->paths = $paths + $this->load->paths;

		return $this->load;
	}

	/**
	 * loadToSelf - Uses load::component to load an instance of the named class to the controller
	 *
	 * Instantiates the named class if not already assigned to the supplied object and returns the new object property.
	 * Otherwise it returns the named object property.
	 *
	 * Note: Assumes all components have '.php' extension. If the file does not, it is better to 
	 * use $this->load()->component() or load::component() directly.
	 *
	 * @param string $type 		The type of component
	 * @param string $name 		The name of the component
	 * @param  bool  $instantiate 	If set to true instantiates class assuming file name matches class name
	 * @return object 		The instance of the component
	 */
	public function loadToSelf( $type , $name, $instantiate = true )
	{
		if( !isset( $this->loaded[ $type ][$name] ) )
			$this->loaded[$type][$name] = $this->load()->component( $type , $name, '.php', $instantiate );

		return $this->loaded[$type][$name];
	}



	/**
	 * useController - Loads the named controller as a property of the controller that called it.
	 *
	 * Uses the Controller::loadToSelf method which uses the Load::component() method to
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the controller to load
	 * @return Controller 		The loaded controller.
	 * @uses   Controller::loadToSelf 	Assigns instance of the named controller to current controller.
	 */
	public function useController( $name )
	{
		$controller = $this->loadToSelf( 'controller', $name );
		return $controller;
	}


	/**
	 * model - Loads the named model as a property of the controller that called it.
	 *
	 * Uses the Controller::loadToSelf method which uses the Load::component() method to
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the model to load. Defaults to the name of the controller.
	 * @return Model 		The loaded model.
	 * @uses   Controller::loadToSelf 	Assigns the model instance to the controller.
	 */
	public function model( $name = null )
	{
		if( !isset( $name ) )
			$name = ucwords($this->name['unit']);

		return $this->loadToSelf( 'model', $name );
	}


	/**
	 * module - Loads the named module as a property of the controller that called it.
	 *
	 * Uses the Controller::loadToSelf method which uses the Load::component() method to
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * If the class exists in a subdirectory, the name should follow the form: 'subdir/name'
	 * 	ex. for applications/default/modules/base/menu.php   The name would be 'base/menu'
	 *
	 * @param  string $name 	The name of the module to load.
	 * @param  bool   $instantiate 	If set to true, the module is assumed to be a class by the
	 * 					same name and instantiated. Otherwise simply include.
	 * @return object 		The loaded module.
	 * @uses   Controller::loadToSelf 	Assigns the module instance to the controller.
	 */
	public function module( $name, $instantiate = true )
	{
		return $module = $this->loadToSelf( 'module', $name, $instantiate );
	}


	/**
	 * library - Loads the named library as a property of the controller that called it.
	 *
	 * Uses the Controller::loadToSelf method which uses the Load::component() method to
	 * load an object of the named class to the controller IF not already loaded.
	 *
	 * @param  string $name 	The name of the library to load.
	 * @param  bool   $instantiate 	If set to true, the module is assumed to be a class by the
	 * 					same name and instantiated. Otherwise simply include.
	 * @return object 		The loaded library.
	 * @uses   Controller::loadToSelf 	Assigns the library instance to the controller.
	 */
	public function library( $name, $instantiate = true )
	{
		return $library = $this->loadToSelf( 'library', $name, $instantiate );
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
	public function template( $name = null )
	{
		if( $name === null )
		{
			$config = new Config();
			$settings = $config->fetch('application');
			$name = $settings['default_template'];
		}

		$this->loaded['template']['path'] = $this->load()->path('template', $name, '.php' );
		return $this;
	}


	/**
	 * view - Sets the view to be incorporated into the template then includes the template.
	 *
	 * The template requires the view path to be set before it is included. Once defined,
	 * the template can be included and it will then include the view.
	 *
	 * @param sting  $type 			The type of the view to load.
	 * @param sting  $view 			The name of the view
	 * @param array  $data 			The data to be passed to the view for interaction/reading.
	 * @param bool 	 $direct_include 	If set to true, includes the view directly, rather than loading the template then view.
	 * @return Controller 			The current controller.
	 * @uses   load::path()			Returns file path for the view.
	 */
	public function view( $type, $view = null, $data = null, $direct_include = false )
	{
		$this->loaded['view'][$type][$view]['path'] = $this->load()->path( $type , $view, '.php' );

		if( $direct_include === true )
			require( $this->loaded['view'][$type][$view]['path'] );

		return $this;
	}


	/**
	 * content() - Uses controller:view() to load content view type
	 *
	 * @param sting  $content 		The name of the content to load.
	 * @param string $unit_name 		The name of the unit the view belongs to. Defaults to the current controller.
	 * @param array  $data 			The data to be passed to the view for interaction/reading. Should only be used if including directly
	 * @param bool 	 $direct_include 	If set to true, includes the view directly, rather than loading the template then view.
	 * @return Controller 			The current controller.
	 */
	public function content( $content, $unit_name = null, $data = null, $direct_include = false )
	{
		if( !isset( $unit_name ) )
			$unit_name = $this->name['unit'];

		$path = $unit_name . '/' . $content;

		$this->view( 'content', $path, $data, $direct_include );

		return $this;
	}


	/**
	 * error() - Uses controller:view() to load error view type
	 *
	 * @param sting  $error 		The name of the error to load.
	 * @param array  $data 			The data to be passed to the view for interaction/reading. Should only be used if including directly
	 * @param bool 	 $direct_include 	If set to true, includes the view directly, rather than loading the template then view.
	 * @return Controller 			The current controller.
	 */
	public function error( $error, $data = null, $direct_include = false )
	{
		$this->view( 'error', $error, $data, $direct_include );

		return $this;
	}


	/**
	 * message() - Uses controller:view() to load message view type
	 *
	 * @param sting  $message 		The name of the message to load.
	 * @param array  $data 			The data to be passed to the view for interaction/reading. Should only be used if including directly
	 * @param bool 	 $direct_include 	If set to true, includes the view directly, rather than loading the template then view.
	 * @return Controller 			The current controller.
	 */
	public function message( $message, $data = null, $direct_include = false )
	{
		$this->view( 'message', $message, $data, $direct_include );

		return $this;
	}


	/**
	 * renderLoaded() - Includes files whose files are registered in Controller::loaded[view]
	 *
	 * @param array  $data 			The data to be passed to the view for interaction/reading.
	 * @param string $type 			The type of the view to load
	 * @param string $name 			The name of the view to load
	 * @return Controller 		The current controller.
	 */
	public function renderLoaded( $data = null, $type = null, $name = null )
	{
		foreach( $this->loaded['view'] as $loaded_type => $views )
		{
			if( ( $type !== null ) && ( $loaded_type != $type ) )
				continue;

			foreach( $views as $view )
			{
				if( ( $name !== null ) && ( $view != $name ) )
					continue;

				require( $view['path'] );
			}
		}

		return $this;
	}


	/**
	 * render() - Outputs template
	 *
	 * Render includes the template which typically uses
	 * renderLoaded to include views set by the controller.
	 * Or it may directly include views by calling the a view setting function
	 * with the $direct_include directive set to true.
	 *
	 * @param array  $data 			The data to be passed to the view for interaction/reading. Defaults to model data.
	 * @return Controller 			The current controller.
	 */		
	public function render( $data = null ){
		if( !isset( $this->loaded['template']['path'] ) )
			$this->template();

		if ( $data === null )
			$data = $this->model()->data;

		require_once( $this->loaded['template']['path'] );

		return $this;
	}


}

?>
