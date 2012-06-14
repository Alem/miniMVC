<?php
/**
 * Router class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Router class processes the URI request and
 * defines the Controller, method, and variable requested,
 */
class Router
{
	/**
	 * @var array 	The position of each URI type in the query string.
	 *
	 * This array maps where the controller, method, and variables are expected to be in.
	 * 	ie: example.com/controller_unitname/method/variable
	 */
	public $uri_map = array(
		0 => 'controller_unitname',
		1 => 'method',
		2 => 'variable'
	);

	/**
	 * @var string 	The complete, mapped, uri
	 */
	public $uri = null;

	/**
	 * @var string 	The requested unmapped uri
	 */
	public $raw_uri = null;

	/**
	 * @var string 	The unit name of the controller.
	 *
	 * ex: For /foo/method/var, foo 
	 */
	public $controller_unitname = null;

	/**
	 * @var string 	The controller to be routed to
	 */
	public $controller = null;

	/**
	 * @var string 	The method of the controller to be routed to
	 */
	public $method = null;

	/**
	 * @var string 	The variable(s) of the method of the  controller to be routed to
	 */
	public $variable = null;

	/**
	 * @var bool 	Set to true if the url was route-matched in router::route(). Default false
	 */
	public $route_matched = false;

	/**
	 * construct() - Calls the Router:: getRawURI and parseURI() functions
	 *
	 * @param array  $Routes 		Array of routes, following match => replacement format
	 * @param string $uri_override 		Used to manually set the raw_uri
	 * @param bool 	 $auto_start 		If true, automatically runs entire routing process.
	 */
	public function __construct( array $routes = null, $uri_override = null, $auto_start = true )
	{
		if ( $auto_start )
		{
		$this->getRawURI( $uri_override )
			->route( $routes )
			->parseURI();
		}
	}

	/**
	 * getRawURI - Defines URI using GET[uri] or command line arguement
	 *
	 * @param string $uri_override 		Used to manually set the raw_uri
	 * @return string The URI/Query String
	 */
	function getRawURI( $uri_override = null )
	{
		if(  isset( $uri_override ) )
			$this->raw_uri = $uri_override;

		elseif( isset( $_SERVER['argv'] ) &&  isset( $_SERVER['argv'][1] )  )
			$this->raw_uri = $_SERVER['argv'][1];

		elseif( isset( $_GET['uri'] ) )
			$this->raw_uri = $_GET['uri'];

		else
			$this->raw_uri = null;

		return $this;
	}

	/**
	 * route - Maps request uri to a pre-defined set of resources.
	 *
	 * @param array $routes The array of routes
	 * @return Router 	The Router object.
	 */
	public function route( array $routes = null )
	{
		if( $routes === null )
		{
			$config = new Config();
			$routes = $config->fetch('routes');
		}

		if( $routes !== null )
		{
			foreach( $routes as $match => $replacement )
			{
				$match = str_replace( array(':any',':num'), array( '.+', '[0-9]+' ), $match );
				$pattern = '#^' . $match . '$#';

				if( preg_match( $pattern, $this->raw_uri ) )
				{
					if( strpos( $match, '(' ) !== false && strpos( $replacement, '$' ) !== false )
						$this->uri = preg_replace( $pattern, $replacement, $this->raw_uri );
					else
						$this->uri = $replacement;

					$this->route_matched = true;
					break;
				}
			}
		}

		if( $routes === null || $this->route_matched === false )
			$this->uri = $this->raw_uri;

		return $this;

	}

	/**
	 * parseURI - Defines the Controller, Method and Variables from the request.
	 *
	 * Splits up the URI request using the URI_SEPARATOR delimiter,
	 * and defines the Controller, method, and variable
	 * referencing Router::uri_map to determine the placement.
	 *
	 * @return Router 	The Router object.
	 */
	public function parseURI()
	{

		if( $this->uri !== null )
		{
			$this->uri_parts  = explode( URI_SEPARATOR, $this->uri , 3 );
			$parameters = count( $this->uri_parts );

			foreach( $this->uri_map as $position => $type )
			{
				if( ( $parameters > $position ) && ( $this->uri_parts[$position] !== '' ) )
					$this->$type = $this->uri_parts[ $position ];
				else
					$this->$type = null;
			}

			$this->controller = ucwords( $this->controller_unitname ) . 'Controller';
		}

		if( $this->controller_unitname === null || $this->method === null )
		{
			$config = new Config();
			$settings = $config->fetch('application');

			if( $this->controller === null )
				$this->controller = $settings['default_controller'];

			if( $this->method === null )
				$this->method = $settings['default_method'];
		}


		return $this;
	}

}
?>
