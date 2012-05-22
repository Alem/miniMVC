<?php
/**
 * Router class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Router class processes the URI request and 
 * defines the Controller, method, and variable requested,
 */
class Router
{
	/**
	 * @var array The position of each URI type in the query string. 
	 *
	 * This array maps where the controller, method, and variables are expected to be in.
	 * 	ie: example.com/controller/method/variable
	 */
	public $uri_map = array(
		0 => 'controller',
		1 => 'method',
		2 => 'variable'
	);


	/**
	 * construct() - Calls the Router:: setURI and parseURI() functions
	 */
	public function __construct( array $routes = null )
	{
		$this -> setURI()
			-> route( $routes )
			-> parseURI();
	}


	/**
	 * defineURI - Defines URI using GET[uri] or command line arguement
	 *
	 * @return string The URI/Query String
	 */
	function setURI()
	{
		if ( isset( $_SERVER['argv'] ) )
			$this -> raw_uri = $_SERVER['argv'][1];

		elseif ( isset ( $_GET['uri'] ) )
			$this -> raw_uri = $_GET['uri'];

		else
			$this -> raw_uri = null;

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

		$this -> uri_parts  = explode( URI_SEPARATOR, $this -> uri , 3 );
		$parameters = count( $this -> uri_parts );

		foreach ( $this -> uri_map as $position => $type )
		{
			if ( ( $parameters > $position ) && ( $this -> uri_parts[$position] !== '' ) )
				$this -> $type = $this -> uri_parts[ $position ];
			else
			{
				$config = new Config();
				$settings = $config -> load ('application');

				if( $type === 'controller' )
					$this -> $type = $settings['default_controller'];
				elseif( $type === 'method' )
					$this -> $type = $settings['default_method'];
				else
					$this -> $type = null;
			}
		}
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
			$routes = $config -> load ('routes');
		}

		$this -> uri = $this -> raw_uri;

		if( $routes !== null )
		{
			foreach ( $routes as $match => $replacement )
			{
				$match = str_replace( array(':any',':num'), array( '.+', '[0-9]+' ), $match );

				if ( preg_match( '#^' . $match . '$#', $this -> raw_uri ) )
				{
					if ( strpos( $replacement, '$' ) ==! false && strpos( $match, '(' ) ==! false )
						$replacement = preg_replace( '#^' . $match .'$#', $replacement, $this -> uri );
					$this -> uri = $replacement;
				}
			}
		}

		return $this;

	}

}
?>
