<?php


class Menu {

	function __construct(){

		$this -> nav['Home'] = '';
		$this -> nav['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$this -> nav['Submit'] = DEFAULT_CONTROLLER . '/form';
		$this -> nav['About'] = DEFAULT_CONTROLLER . '/about';

		$this -> sidebar['Home'] = '';
		$this -> sidebar['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		if( Session::open() -> get('logged_in') )
			$this -> sidebar['Logout'] = 'user/logout';
		else
			$this -> sidebar['Login'] = 'user';
		$this -> sidebar['About'] = DEFAULT_CONTROLLER . '/about';
	}


}

?>
