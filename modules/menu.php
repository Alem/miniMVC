<?php


class Menu {

	function __construct(){

		if (!isset($_SESSION))
			session_start();

		$this -> nav['Home'] = '';
		$this -> nav['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$this -> nav['Submit'] = DEFAULT_CONTROLLER . '/form';
		$this -> nav['About'] = DEFAULT_CONTROLLER . '/about';

	#	$this -> sidebar['Home'] = '';
	#	$this -> sidebar['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
	#	if ( !isset( $_SESSION['logged_in'] ))
	#		$this -> sidebar['Login'] = 'user';
	#	else
	#		$this -> sidebar['Logout'] = 'user/logout';
	#	$this -> sidebar['About'] = DEFAULT_CONTROLLER . '/about';
	}


}

?>
