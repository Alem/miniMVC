<?php

/**
 * Request class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */


class Request{


	/**
	 * @var array The position of each URI type in the query string. ie: example.com/controller/method/variable
	 *
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
	 * @todo Overwriting a superglobal seems like a bad idea -- replace this with constant for string and a session variable for GET?
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
	 * process - Returns formatted request array defining the Controller, Method and Variables.
	 *
	 * Parses the request and splits it up using the URI_SEPARATOR delimiter.
	 *
	 * @return array The contoller-friendly request array.
	 *
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
	 * INCOMPLETE
	 */
	function subDomain(){
		$urlInfo = parse_url( $url );
		$subdomain = substr( $urlInfo['host'], 0, strpos( $urlInfo['host'], '.' ) );

		if ( isset( $subdomain ) )
			define ( 'SUBDOMAIN', $subdomain );
	}

}

?>
