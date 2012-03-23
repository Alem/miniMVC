<?php

/**
 * Router class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */


class Router{

	/**
	 * @var array The position of each URI type in the query string. ie: example.com/controller/method/variable
	 *
	 */

	public $URI_map = array(
		0 => 'controller',
		1 => 'method',
		2 => 'variable'
	);


	/**
	 * getRequest - Defines URI as HTTP request string or command line arguement
	 *
	 * @return string The URI/Query String
	 */

	function getRequest(){

		global $argc,$argv; // Had to, argc and argv are not superglobals.

		if ( isset( $argv[1] ) )
			define( 'URI', $argv[1] );
		else
			define( 'URI', $_SERVER['QUERY_STRING'] );
		return URI;
	}


	/**
	 * getRequest - Defines URI as HTTP request string or command line arguement
	 *
	 * INCOMPLETE
	 *
	 */

	function getSubDomain(){
		$urlInfo = parse_url( $url );
		$subdomain = substr( $urlInfo['host'], 0, strpos( $urlInfo['host'], '.' ) );

		if ( isset( $subdomain ) )
			define ( 'SUBDOMAIN', $subdomain );
	}


	/**
	 * formatRequest - Returns formatted request array defining the Controller, Method and Variables.
	 *
	 * Parses the request and splits it up using the URI_SEPARATOR delimiter.
	 *
	 * @return array The contoller-friendly request array.
	 *
	 */

	function formatRequest() {

		$this -> getRequest();
		$split_URI = explode( URI_SEPARATOR, URI, 3 );
		$parameters = count( $split_URI );

		foreach ( $this -> URI_map as $place => $type ){
			if ( $parameters > $place ){
				define ( strtoupper( $type ), $split_URI[ $place ] );
				$request[ $type ] = strtolower ( $split_URI[ $place ] );
			}else
				$request[ $type ] = null;
		}

		return $request;
	}

}


?>
