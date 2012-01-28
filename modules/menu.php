<?php


class Menu {

	function __construct(){
		if (!isset($_SESSION))
			session_start();
	}

	function nav(){
		#$nav['Home'] = DEFAULT_CONTROLLER;
		$nav['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$nav['Submit'] = DEFAULT_CONTROLLER . '/form';
		if ( !isset( $_SESSION['logged_in'] ))
			$nav['Login'] = 'user';
		else
			$nav['Profile'] = 'user';
		$nav['About'] = DEFAULT_CONTROLLER . '/about';
		return $nav;
	}

	function sidebar(){
		#if ( $_SERVER['QUERY_STRING'] != 'user'){
		$sidebar['Home'] = DEFAULT_CONTROLLER;
		$sidebar['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		if ( !isset( $_SESSION['logged_in'] ))
			$sidebar['Login'] = 'user';
		else
			$sidebar['Logout'] = 'user/logout';
		$sidebar['About'] = DEFAULT_CONTROLLER . '/about';
		return $sidebar;
		#}
	}

}

?>
