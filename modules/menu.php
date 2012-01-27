<?php


class Menu {

	function nav(){
		$nav['Home'] = 'test';
		$nav['Gallery'] = 'test/show';
		if ( !isset( $_SESSION['logged_in'] ))
			$nav['Login'] = 'user';
		else
			$nav['Logout'] = 'user/logout';
		$nav['About'] = 'test/about';
		return $nav;
	}

	function sidebar(){
		$sidebar['Home'] = 'test';
		$sidebar['Gallery'] = 'test/show';
		if ( !isset( $_SESSION['logged_in'] ))
			$sidebar['Login'] = 'user';
		else
			$sidebar['Logout'] = 'user/logout';
		$sidebar['About'] = 'test/about';
		return $sidebar;
	}

}

?>
