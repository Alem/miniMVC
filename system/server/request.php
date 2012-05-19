<?php
/**
 * Request class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The request class provides simplified access to 
 * HTTP request related data, while filtering internally-used
 * routing-related data. ( The GET parameter 'uri' )
 */
class Request
{


	/**
	 * @var string 	Simply a convenience wrapper for $_SERVER['REQUEST_METHOD']
	 *
	 * @see Request::populate
	 */
	public $method 	= null;

	/**
	 * @var array 	Holds array of variables passed by the HTTP GET method; Populated by Request::populate.
	 *
	 * Also the key 'uri' unset by Request::populate
	 * @see Request::populate
	 */
	public $get 	= array();


	/**
	 * @var array 	Holds array of variables passed by the HTTP POST method; Populated by Request::populate.
	 *
	 * @see Request::populate
	 */
	public $post 	= array();


	/**
	 * @var array 	Holds array of variables passed by the HTTP PUT method; Populated by Request::populate.
	 *
	 * @see Request::populate
	 */
	public $put 	= array();


	/**
	 * @var array 	Holds array of variables passed by the HTTP DELETE method; Populated by Request::populate.
	 *
	 * @see Request::populate
	 */
	public $dele 	= array();


	/**
	 * @var array 	Holds array of variables passed by the HTTP OPTIONS method; Populated by Request::populate.
	 *
	 * @see Request::populate
	 */
	public $options = array();


	/**
	 * @var string 	Holds the URI-filtered query string; Populated by Request::populate.
	 *
	 * @see Request::populate
	 */
	public $query_string = null;


	/**
	 * __construct() - Calls Request::populate to populate object properties
	 */
	public function __construct()
	{
		$this -> populate();
	}


	/**
	 * populate() - Populates the HTTP Request Method property containers with their appropriate parameters.
	 *
	 * Request::post simply uses the post superglobal
	 * Request::get must have the 'uri' key filtered as it is used by the framework for routing purposes ( uri = controller/method/var ).
	 * Request:: put,delete, and options do not have PHP superglobals and must be read directly from the php://input stream of raw request body data.
	 */
	public function populate() {
		$this -> post = $_POST;

		$this -> get  = $_GET;
		unset( $this -> get['uri'] );

		if ( isset( $_SERVER['QUERY_STRING'] ) )
		{
			$ampersand_pos = strpos ( $_SERVER['QUERY_STRING'], '&' );
			if ( $ampersand_pos !== false )
				$this -> query_string = substr ($_SERVER['QUERY_STRING'], $ampersand_pos + 1 );
		}

		if ( isset( $_SERVER['REQUEST_METHOD'] ) )
		{
			$this -> method =  $_SERVER['REQUEST_METHOD'];

			switch ( $_SERVER['REQUEST_METHOD'] )
			{
			case 'PUT':
				parse_str( file_get_contents('php://input'), $this -> put );
				break;

			case 'DELETE':
				parse_str( file_get_contents('php://input'), $this -> dele );
				break;

			case 'OPTIONS':
				parse_str( file_get_contents('php://input'), $this -> options );
				break;
			}
		}
	}


	/**
	 * fetchFromArray() - Fetches key value from a supplied array. Returns false if not found.
	 *
	 * @return mixed 	Returns key value or false if not found.
	 */
	public function fetchFromArray( $array, $value )
	{
		if ( isset ( $array[ $value ] ) )
			return $array [ $value ];
		else
			return false;
	}


	/**
	 * get() -  Fetches key value from get array. Returns false if not found.
	 */
	public function get( $value = null )
	{
		return $this -> fetchFromArray( $this -> get, $value );
	}


	/**
	 * post() -  Fetches key value from post array. Returns false if not found.
	 */
	public function post( $value = null )
	{
		return $this -> fetchFromArray( $this -> post, $value );
	}


	/**
	 * put() -  Fetches key value from put array. Returns false if not found.
	 */
	public function put( $value = null )
	{
		return $this -> fetchFromArray( $this -> put, $value );
	}


	/**
	 * dele() -  Fetches key value from dele array. Returns false if not found.
	 */
	public function dele( $value = null )
	{
		return $this -> fetchFromArray( $this -> dele, $value );
	}


	/**
	 * options() -  Fetches key value from options array. Returns false if not found.
	 */
	public function options( $value = null )
	{
		return $this -> fetchFromArray( $this -> options, $value );
	}


	/**
	 * isAjax() - Check if request was made via AJAX
	 *
	 * Determines if request was made via AJAX by 
	 * checking the request header for HTTP_X_REQUESTED_WITH 
	 *
	 * @return bool 	Returns true if AJAX request, otherwise false.
	 */
	public function isAjax()
	{
		return ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}


	/**
	 * userIP() - Determines the user's IP address
	 *
	 * Grab users IP using any set $_SERVER variable (found in HTTP request header).
	 * Can optionally return SQL formatted long Int IP address
	 *
	 * If HTTP_X_FORWARDED_FOR is set, returns the last of the bunch
	 *
	 * @param  bool    $long_int 	If set returns long-int sql-formatted IP number.
	 * @return mixed 		The IP address
	 */
	public function userIP( $long_int = false )
	{

		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];

		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = array_pop(explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']));

		else
			$ip = $_SERVER['REMOTE_ADDR'];


		if( $long_int == true )
			return ip2long($ip);
		else
			return $ip;
	}


	/**
	 * subdomain
	 * Todo
	 */
	public function subdomain()
	{
		$url_info = parse_url( $url );
		$subdomain = substr( $url_info['host'], 0, strpos( $url_info['host'], '.' ) );

		if ( isset( $subdomain ) )
			return $subdomain;
		else
			return null;
	}

}
?>
