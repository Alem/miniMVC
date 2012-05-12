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


class Menu 
{

	public $menus = array();
	public $hierarchy = array();
	public $active = array();

	function __construct()
	{
		$this -> router = new Router();
		$this -> nav();	
	}

	function display( $menu )
	{
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



	function nav()
	{

		$this -> menus['nav']['Home'] = null;
		$this -> menus['nav']['Gallery'] = DEFAULT_CONTROLLER . '/gallery';
		$this -> menus['nav']['Submit'] =  DEFAULT_CONTROLLER . '/form';
		$this -> menus['nav']['About'] =  DEFAULT_CONTROLLER . '/about';
		$this -> set_active ( 'nav', true);
	}


	function sec_nav()
	{
		if ( in_array( $this -> router -> controller, $this -> hierarchy['nav']['Home'] ) )
		{
		}
	}


	function sidebar()
	{
	}		


	function breadcrumb()
	{
		if ( isset( $this -> router -> controller) )
			$this -> menus['breadcrumb'][ucwords( $this -> router -> controller )] = $this -> router -> controller;
		if ( isset( $this -> router -> method ) )
			$this -> menus['breadcrumb'][ucwords( $this -> router -> method )] = $this -> router -> controller . '/' . $this -> router -> method;
		if ( isset( $this -> router -> variable) )
			$this -> menus['breadcrumb'][ucwords( $this -> router -> variable )] = $this -> router -> controller . '/' . $this -> router -> method . '/' . $this -> router -> variable;

		end( $this -> menus['breadcrumb'] );
		if ( !empty ( $this -> breadcrumb ) )
			$this -> active['breadcrumb'] = ucwords ( key( $this->breadcrumb ) );
	}


	function set_active( $menu, $has_children = false )
	{
		foreach ( $this -> menus[$menu] as $name => $href )
		{
			if( 
				$href == $this -> router -> uri  
				|| (
					$has_children
					&& isset( $this -> hierarchy[$menu][$name] ) 
					&& in_array( $this -> router -> controller,  $this -> hierarchy[$menu][$name] ) 
				) 
			) 
			$this -> active[$menu] = $name;
		}
	}

}

?>
