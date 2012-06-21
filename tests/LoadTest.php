<?php
/**
 * @todo
 */
require_once('../minimvc/base/Load.php');
require_once('mockCore.php');

class LoadTest extends PHPUnit_Framework_TestCase 
{


	/**
	 * Test setting of default paths using core constants,
	 * test overiding of default paths using custom paths.
	 */
	public function testPath()
	{
		
		$load = new Load();
		$mockServerRoot = dirname(__FILE__); 

		$this->assertTrue( 
			$load->path( 'system' ) === $mockServerRoot . 'minimvc/'
		);
		$this->assertTrue( 
			$load->path( 'system', 'test', '.ext' ) === $mockServerRoot . 'minimvc/test.ext'
		);

		$mock_paths = array(
			'system' 	=> $mockServerRoot . 'atypical_absolute_location/',
		);
		$load->setPaths( $mock_paths );

		$this->assertTrue( 
			$load->path( 'system' ) === $mockServerRoot . 'atypical_absolute_location/' 
		);
		$this->assertTrue( 
			$load->path( 'system', 'test', '.ext' ) === $mockServerRoot . 'atypical_absolute_location/test.ext' 
		);
	}

}

?>
