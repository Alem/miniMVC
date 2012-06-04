<?php

require_once('../system/base/router.php');

define( 'URI_SEPARATOR', '/' );

class RouterTest extends PHPUnit_Framework_TestCase 
{

	public $mock_routes = array(
		'foo/(:any)' => 'controller/method/$1'
	);

	public $uri_override = 'foo/4';


	public function testGetRawUri()
	{
		$router = new Router( null, null, false );
		$router -> getRawURI( $this->uri_override );

		$this->assertTrue( $router->raw_uri === 'foo/4' );
	}

	public function testRoute()
	{
		$router = new Router( null, null, false );
		$router -> getRawURI( $this->uri_override );
		$router -> route( $this->mock_routes );

		$this->assertTrue( $router->uri === 'controller/method/4' );
	}

	public function testParseUr()
	{
		$router = new Router( null, null, false );
		$router -> getRawURI( $this->uri_override );
		$router -> route( $this->mock_routes );
		$router -> parseURI();

		$this->assertTrue( $router->controller 	=== 'controller' );
		$this->assertTrue( $router->method 	=== 'method' );
		$this->assertTrue( $router->variable 	=== '4' );
	}
}

?>
