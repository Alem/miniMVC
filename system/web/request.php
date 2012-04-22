<?php
/**
 * Request class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The request class processes the URI request and 
 * defines the Controller, method, and variable requested,
 * and separates the URI request from search queries 
 * ( both of which end up in $_GET, $_SERVER['QUERY_STRING'] )
 */
class Request{


	/**
	 * @var array The position of each URI type in the query string. 
	 *
	 * This array maps where the controller, method, and variables are expected to be in.
	 * 	ie: example.com/controller/method/variable
	 */
	public $URI_map = array(
		0 => 'CONTROLLER',
		1 => 'METHOD',
		2 => 'VARIABLE'
	);


	/**
	 * filterURI - Filters URI from $_GET and $_SERVER
	 *
	 * Removes the 'uri' paramater ( responsible for identifying the Controller, method )
	 * from the $_GET variable and the $_SERVER variable
	 *
	 * @todo Unsetting values from the superglobal seems like a bad idea 
	 * 		-- create filtered GET session variable?
	 */
	public function filterURI(){
		unset( $_GET['uri'] );

		$ampPos = strpos ( $_SERVER['QUERY_STRING'], '&' );
		if ( $ampPos !== false )
			define ( 'FILTERED_QUERY_STRING', substr ($_SERVER['QUERY_STRING'], $ampPos + 1 ) );
	}


	/**
	 * get - Defines URI using GET[uri] or command line arguement
	 *
	 * If $_GET is used, the URI key is unset after using its value to define the URI constant
	 * leaving $_GET with the remaining 'non-routing' key => value pairs.
	 *
	 * @return string The URI/Query String
	 * @uses   Request::filterURI()
	 */
	function get(){

		global $argc, $argv; // I had to, argc and argv are not PHP superglobals.

		if ( isset( $argv[1] ) )
			define( 'URI', $argv[1] );
		else{
			if ( isset ( $_GET['uri'] ) ){
				define( 'URI', $_GET['uri'] );
				$this -> filterURI();
			}else
				define( 'URI', null );
		}
		return URI;
	}


	/**
	 * process - Defines the Controller, Method and Variables from the request.
	 *
	 * Splits up the URI request using the URI_SEPARATOR delimiter, 
	 * and defines the Controller, method, and variable
	 * referencing Request::URI_map to determine the placement.
	 *
	 * @return Request 	The request object.
	 */
	function process() {

		$this -> get();
		$URI_parts = explode( URI_SEPARATOR, URI, 3 );
		$parameters = count( $URI_parts );

		foreach ( $this -> URI_map as $position => $type ){
			if ( $parameters > $position )
				define ( $type , $URI_parts[ $position ] );
			else
				define ( $type , null );
		}
		return $this;
	}


	/**
	 * subDomain
	 * @todo Add subdomain suport?
	 */
	function subDomain(){
		$urlInfo = parse_url( $url );
		$subdomain = substr( $urlInfo['host'], 0, strpos( $urlInfo['host'], '.' ) );

		if ( isset( $subdomain ) )
			define ( 'SUBDOMAIN', $subdomain );
	}

}
?>
