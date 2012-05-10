<?php
/**
 * RestService class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * RestService, in combinration with the Request class,
 * allows simple RESTful API functionality.
 *
 * A controller can determine the request method 
 * and retrieve the relevant parameters using the request class 
 *
 * Then deliver a direct HTTP response using the RestService object.
 *
 * switch ( $request -> method )
 * 	case 'get':
 * 		$requested_data = $model -> getFoo( $request -> getFoo['foo'] );
 * 		$data = json_encode( $requested_data );
 * 		$RestService -> sendResponse ( 200, $data, 'json' );
 * 		break;
 *
 * todo Determine if this should remain the RestService object 
 * or become an extension of the Controller class
 */
class RestService
{

	/**
	 * @var array Numeric status code and their respective messages.
	 */
	public $status_codes = array(  
		100 => 'Continue',  
		101 => 'Switching Protocols',  
		200 => 'OK',  
		201 => 'Created',  
		202 => 'Accepted',  
		203 => 'Non-Authoritative Information',  
		204 => 'No Content',  
		205 => 'Reset Content',  
		206 => 'Partial Content',  
		300 => 'Multiple Choices',  
		301 => 'Moved Permanently',  
		302 => 'Found',  
		303 => 'See Other',  
		304 => 'Not Modified',  
		305 => 'Use Proxy',  
		306 => '(Unused)',  
		307 => 'Temporary Redirect',  
		400 => 'Bad Request',  
		401 => 'Unauthorized',  
		402 => 'Payment Required',  
		403 => 'Forbidden',  
		404 => 'Not Found',  
		405 => 'Method Not Allowed',  
		406 => 'Not Acceptable',  
		407 => 'Proxy Authentication Required',  
		408 => 'Request Timeout',  
		409 => 'Conflict',  
		410 => 'Gone',  
		411 => 'Length Required',  
		412 => 'Precondition Failed',  
		413 => 'Request Entity Too Large',  
		414 => 'Request-URI Too Long',  
		415 => 'Unsupported Media Type',  
		416 => 'Requested Range Not Satisfiable',  
		417 => 'Expectation Failed',  
		500 => 'Internal Server Error',  
		501 => 'Not Implemented',  
		502 => 'Bad Gateway',  
		503 => 'Service Unavailable',  
		504 => 'Gateway Timeout',  
		505 => 'HTTP Version Not Supported'  
	);  


	/**
	 * @var array Chosen  content type for response data
	 */
	public $content_type = 'html';


	/**
	 * @var array Avialable content types for response data
	 */
	public $supported_formats = array(
		'xml' => 'application/xml',
		'json' => 'application/json',
		'jsonp' => 'application/javascript',
		'serialized' => 'application/vnd.php.serialized',
		'php' => 'text/plain',
		'html' => 'text/html',
		'csv' => 'application/csv'
	);


	/**
	 * sendResponse() - Sends HTTP header and body.
	 *
	 * @param integer $status 	The numeric HTTP status code
	 * @param mixed   $body 	The data comprising the response body
	 * @param string  $content_type The content type of the response data
	 */
	public function sendResponse( $status = 200, $body = '', $content_type = null )
	{
		if( !isset( $content_type ) )
			$content_type = $this -> content_type;

		$http_header = <<<HTTP_HEADER
HTTP/1.1 $status_code {$this->status_codes[$status_code]}
Content-type: {$this->supported_formats[$content_type]}
HTTP_HEADER;

		header( $http_header );
		echo $body;
	}

}

?>
