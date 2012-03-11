<?php


class Menu {


	function __construct(){
		$this -> nav();	
		$this -> sec_nav();	
		$this -> sidebar();	
	}


	function nav(){
		$this -> nav['Home'] = '';
		$this -> nav['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$this -> nav['Submit'] = DEFAULT_CONTROLLER . '/form';
		$this -> nav['About'] = DEFAULT_CONTROLLER . '/about';

		$this -> children['nav'][''] = array();

		foreach ( $this -> nav as $name => $href ){
			if( ( $href == URI ) 
				|| (
					isset( $this -> children['nav'][$name] ) 
					&& in_array( CONTROLLER,  $this -> children['nav'][$name] ) 
				) 
			) 
			$this -> nav_active = $name;
		}
	}


	function sec_nav(){
		if ( in_array( CONTROLLER, array ( '') ) ){
		}

		if ( isset( $this -> sec_nav ) ){
			foreach ( $this -> sec_nav as $name => $href ){
				if( ( $href == URI ) ) 
					$this -> sec_nav_active = $name;
			}
		}
	}


	function sidebar(){
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
