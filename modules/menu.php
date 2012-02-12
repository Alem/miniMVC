<?php


class Menu {

	function __construct(){
		if (!isset($_SESSION))
			session_start();
	}

	// Returns navigation hrefs and names as an array.
	function nav(){
		$nav['Home'] = '';
		$nav['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$nav['Submit'] = DEFAULT_CONTROLLER . '/form';
		/*
		if ( !isset( $_SESSION['logged_in'] ))
			$nav['Login'] = 'user';
		else
			$nav['Profile'] = 'user';
		 */
		$nav['About'] = DEFAULT_CONTROLLER . '/about';
		return $nav;
	}

	// Returns sidebar hrefs and names as an array.
	function sidebar(){
		#if ( $_SERVER['QUERY_STRING'] != 'user'){
		$sidebar['Home'] = '';
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
