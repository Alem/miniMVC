<?php

/**
 * Controller class file.
 *
 * The controller class is the parent for all application controllers.
 *
 * It grants the ability to run a method or  load & assign a model, view,
 * module or even another controller.
 *
 * The entire application is initiated as an instance of this class.
 *
 * @author Z. Alem <info@alemmedia.com>
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
	 *
	 */
	public $loaded = array();


	/**
	 * Constructor
	 *
	 */
	public function __construct( ){

	}


	/**
	 * useMethod - Calls appropriate method of controller.
	 *
	 * The default/fallback method is 'index()', and the parameter is simply 
	 * not passed to the method if not set. 
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
	 * @param string $method The controller method to call AKA the page to redirect to (ex. index)
	 * @param mixed $variables The variables to be passed to the method.
	 * @param string $controller The controller the method belongs to. Defaults to the current controller if $method is specified.
	 *
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


	public function useController( $name ){

		$controller = load::toObject ( $this, 'controller', $name );

		if ( isset( $controller ) )
			$controller -> name = $name;
		else
			$this -> prg( null, null, DEFAULT_CONTROLLER );

		return $controller;
	}


	public function model( $name = null ){
		if ( !isset( $name ) )
			$name = $this -> name;

		return load::toObject( $this,  'model', $name );
	}

	public function module( $name, $instantiate = true ){
		$module = load::toObject( $this, 'module', $name, $instantiate );
		if (isset ( $module ) )
			$module -> owner =& $this; //todo Remove this
		return $module;
	}


	public function library( $name, $instantiate = true ){
		return $library = load::toObject( $this, 'library', $name, $instantiate );
	}



	public function template( $name ){
		$this -> loaded['template']['path'] = Load::path('template', $name );
		return $this;
	}

	
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
