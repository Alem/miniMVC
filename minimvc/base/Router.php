<?php
/**
 * Router class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Router class processes the URI request and
 * defines the Controller, method, and variable requested,
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.base
 */
class Router
{
	/**
	 * The position of each URI type in the query string.
	 * This array maps where the controller, method, and variables are expected to be in.
	 * 	ie: example.com/controller_unitname/method/variable
	 *
	 * @var array
	 */
	public $uri_map = array(
		0 => 'controller_unitname',
		1 => 'method',
		2 => 'variable'
	);

	/**
	 * The complete, mapped, uri
	 * @var string
	 */
	public $uri = null;

	/**
	 * The requested unmapped uri
	 * @var string
	 */
	public $raw_uri = null;

	/**
	 * The unit name of the controller.
	 * @var string
	 *
	 * ex: For /foo/method/var, foo 
	 */
	public $controller_unitname = null;

	/**
	 * The controller to be routed to
	 * @var string
	 */
	public $controller = null;

	/**
	 * The method of the controller to be routed to
	 * @var string
	 */
	public $method = null;

	/**
	 * The variable(s) of the method of the  controller to be routed to
	 * @var string
	 */
	public $variable = null;

	/**
	 * Set to true if the url was route-matched in router::route(). Default false
	 * @var bool
	 */
	public $remapped = false;

	/**
	 * construct() - Calls the Router:: getRawURI and parseURI() functions
	 *
	 * @param string $uri_override 		Used to manually set the raw_uri
	 * @param array  $remaps 		Array of remaps, following match => replacement format
	 * @param bool 	 $auto_start 		If true, automatically runs entire routing process.
	 */
	public function __construct(  $uri_override = null, array $remaps = null, $auto_start = true )
	{
		if ( $auto_start )
		{
			$this->getRawURI( $uri_override )
				->route( $remaps )
				->parseURI();
		}
	}

	/**
	 * getRawURI - Defines URI using GET[uri] or command line arguement
	 *
	 * @param string $uri_override 		Used to manually set the raw_uri
	 * @return string The URI/Query String
	 */
public function getRawURI( $uri_override = null )
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
	 * @param array $remaps The array of remaps
	 * @return Router 	The Router object.
	 */
	public function route( array $remaps = null )
	{
		if( $remaps === null )
		{
			$config = new Config();
			$routes = $config->fetch('routes');
			$remaps = $routes['remaps'];
		}

		if( $remaps !== null )
		{
			foreach( $remaps as $match => $replacement )
			{
				$match = str_replace( array(':any',':num'), array( '.+', '[0-9]+' ), $match );
				$pattern = '#^' . $match . '$#';

				if( preg_match( $pattern, $this->raw_uri ) )
				{
					if( strpos( $match, '(' ) !== false && strpos( $replacement, '$' ) !== false )
						$this->uri = preg_replace( $pattern, $replacement, $this->raw_uri );
					else
						$this->uri = $replacement;

					$this->remapped = true;
					break;
				}
			}
		}

		if( $remaps === null || $this->remapped === false )
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
