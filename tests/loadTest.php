<?php
/**
 * @todo
 */
require_once('../system/base/load.php');

class LoadTest extends PHPUnit_Framework_TestCase 
{


	$mock_paths = array(
		'controller' 	=> null,
		'config' 	=> null, 
		'model' 	=> null, 
		'view'		=> null, 
		'template'	=> null, 
		'shared'	=> null, 
		'module'	=> null, 
		'library'	=> null,
		'system'	=> null, 
	);


	public function testComponent()
	{
		$load = new Load();
		$load -> paths = $this -> mock_paths;
	}

}

?>
