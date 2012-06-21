<?php
/**
 * Response class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Response class is used to construct HTTP responses.
 *
 *
 * Response, in combination with the Request class,
 * also allows simple RESTful API functionality.
 *
 * A controller can determine the request method
 * and retrieve the relevant parameters using the request class
 * then deliver a direct HTTP response using the Response object.
 *
 * ------------------------------
 * Example:
 *
 * switch( $request->method )
 * 	case 'get':
 * 		$requested_data = $model->getFoo( $request->getFoo['foo'] );
 * 		$data = json_encode( $requested_data );
 * 		$response->send( 200, $data, 'json' );
 * 		break;
 * ------------------------------
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.server
 */
class Response
{

	/**
	 * Numeric status code and their respective messages.
	 * @var array
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
	 * Chosen  content type for response data
	 * @var array
	 */
	public $content_type = 'html';

	/**
	 * Avialable content types for response data
	 * @var array
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
	 * send() - Sends HTTP header and body.
	 *
	 * @param integer $status_code 	The numeric HTTP status code
	 * @param mixed   $body 	The data comprising the response body
	 * @param string  $content_type The content type of the response data
	 * @param string  $more_headers Optional additonal headers
	 */
	public function send( $status_code = 200, $body = '', $content_type = null, $more_headers = array() )
	{
		if( !isset( $content_type ) )
			$content_type = $this->content_type;

		$status = 'HTTP/1.1 ' . $status_code . ' ' . $this->status_codes[$status_code];
		$content = 'Content-type: ' . $this->supported_formats[$content_type];

		header( $status );
		header( $content );

		if( !empty( $more_headers ) )
		{
			foreach( $more_headers as $header)
				header( $header );
		}

		echo $body;
	}

}

?>
