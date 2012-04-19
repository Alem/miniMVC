<?php

/*
 * Menu module 
 *
 * Menus are implemented as name => href array key pairs. 
 * These menu arrays are then parsed and printed by the view/template.
 *
 * The active menu item is determined by set_active which seeks 
 * an menu[MENU] href value that matches the current URI 
 * or a hierarchy[MENU] value that matches the current CONTROLLER.
 *
 */


class Menu {

	public $menus = array();
	public $hierarchy = array();
	public $active = array();

	function __construct(){
		$this -> nav();	
	//	$this -> sec_nav();	
	//	$this -> sidebar();	
	//	$this -> breadcrumb();	
	}

	function display( $menu ){
		$links = null;
		if ( isset(  $this -> menus[$menu] ) ):
			foreach( $this -> menus[$menu] as $name => $href ):
				if (	
					isset(  $this -> active[$menu] ) 
					&& ( $name == $this -> active[$menu] )
				) 
					$active = 'class = "active"';
				else
					$active =  null;
				if ( $menu == 'breadcrumb'):
					$links .= <<<links

				<span class="divider">/</span>

links;
				endif;
				$links .= <<<links
				<li $active>
				<a href= "$href">$name</a>
				</li>
links;
			endforeach;
		endif;
		return $links;
	}



	function nav(){

		$this -> menus['nav']['Home'] = null;
		$this -> menus['nav']['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$this -> menus['nav']['Submit'] =  DEFAULT_CONTROLLER . '/form';
		$this -> menus['nav']['About'] =  DEFAULT_CONTROLLER . '/about';
		$this -> set_active ( 'nav', true);
	}


	function sec_nav(){
		if ( in_array( CONTROLLER, $this -> hierarchy['nav']['Home'] ) ){
		}
	}


	function sidebar(){
	}		


	function breadcrumb(){
			if ( defined('CONTROLLER') )
				$this -> menus['breadcrumb'][ucwords( CONTROLLER )] = CONTROLLER;
			if ( defined('METHOD') )
				$this -> menus['breadcrumb'][ucwords( METHOD )] = CONTROLLER . '/' . METHOD;
			if ( defined('VARIABLE') )
				$this -> menus['breadcrumb'][ucwords( VARIABLE )] = CONTROLLER . '/' . METHOD . '/' . VARIABLE;

			end( $this -> menus['breadcrumb'] );
			if ( !empty ( $this -> breadcrumb ) )
				$this -> active['breadcrumb'] = ucwords ( key( $this->breadcrumb ) );
	}


	function set_active( $menu, $has_children = false ){
		foreach ( $this -> menus[$menu] as $name => $href ){
			if( 
				$href == URI  
				|| (
					$has_children
					&& isset( $this -> hierarchy[$menu][$name] ) 
					&& in_array( CONTROLLER,  $this -> hierarchy[$menu][$name] ) 
				) 
			) 
			$this -> active[$menu] = $name;
		}
	}

}

?>
