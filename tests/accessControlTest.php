<?php

require_once('../system/auth/accessControl.php');

class AccessControlTest extends PHPUnit_Framework_TestCase 
{


	public $mock_permissions = array(

			'roles' => array(
				0	=> array('c', 'r', 'u', 'd'),
				1	=> array('', 'r', '', ''),
			),

			'actions' => array(
				'c'	=> array( 'post', 'form' ),
				'r'	=> array( 'show', 'gallery' ),
				'u' 	=> array( 'edit' ),
				'd'	=> array( 'del')
			)
		);


	public function testPermissionEnforcement()
	{
		$accessControl = new AccessControl();
		$accessControl -> permissions = $this -> mock_permissions;

		$this -> assertTrue( $accessControl -> permission( 'r', '1' ) === true );
		$this -> assertTrue( $accessControl -> permission( 'c', '1' ) !== true );

	}


	/**
	 * @depends testPermissionEnforcement
	 */
	public function testActionEnforcement()
	{
		$accessControl = new AccessControl();
		$accessControl -> permissions = $this -> mock_permissions;

		$this -> assertTrue( $accessControl -> action( 'form', '1' ) !== true );
		$this -> assertTrue( $accessControl -> action( 'show', '1' ) === true );
	}
}


?>
