<?php


class Controller extends Generator{

	function scaffold( $base_name, $user = true ) {

		$capitalized_name = ucwords ( $base_name );
		$controller_name = $capitalized_name . 'Controller'; 
		$external_fetch = null;

		if ( $user ){
			$user = ", Session::open() -> get( 'user_id' ) ";
			$user_single = " Session::open() -> get( 'user_id' ) ";
		}

		if ( isset( Database::open() -> filtered_columns ) ){
			foreach ( Database::open() -> filtered_columns  as $column ){
				if (  ( preg_match( '/_id/', $column))  ){
					$base_column = preg_replace( '/_id/', '', $column);
					$uc_base_coumn = ucwords( $base_column );
					$external_fetch .= <<<fetch

			\$this -> model -> set ( 
				'$base_column', 
				\$this -> useModel('$base_column') -> get$uc_base_coumn( null, Session::open() -> get( 'user_id' ) ) 
			);
fetch;
				}
			}
		}

		$rules = <<<RULES
	public \$permissions = array(

		'roles' => array(
			'0'	=> array('c', 'r', 'u', 'd'),
			'3'	=> array('c', 'r', 'u', 'd'),
			''	=> array('', '', '', ''),
		),

		'actions' => array(
			'c'	=> array( 'post', 'form' ),
			'r'	=> array( 'show', 'gallery' ),
			'u' 	=> array( 'edit' ),
			'd'	=> array( 'del')
		)
	);
RULES;

		$controller = <<<CONT
<?php

class $controller_name extends Controller{

$rules

	function __construct(){
		parent::__construct();
	}


	// index() - Loads default 'index' view

	function index(){
		\$this -> prg('gallery');
	}


	// form() - Loads 'form' view

	function form(){
		Session::open() -> del('editing_{$base_name}_id');
		if( \$this -> access -> action('form') ){
			$external_fetch
			\$this -> useView('form');
		}else
			\$this -> prg('gallery');
	}


	// post() - Recieves POST data and hands it to model for database insertion

	function post(){
		if ( \$id = Session::open() -> getThenDel('editing_{$base_name}_id')  )
			\$this -> model -> update$capitalized_name( \$id $user);
		elseif( \$this -> access -> action('post') )
			\$id = \$this -> model -> insertPOST( $user_single );
		\$this -> prg('show', \$id);
	}


	// del() - Directly remove database data from URL. 

	function del( \$id ){
		if ( isset( \$id ) && \$this -> access -> action( 'del' ) )
			\$this -> model -> delete$capitalized_name ( \$id $user);
		\$this -> prg('gallery');
	}


	// edit() - Updates specified values

	function edit(\$id){
		Session::open() -> set( 'editing_{$base_name}_id', \$id );
		if( 
			isset( \$id )
			&& ( $$base_name = \$this -> model -> get$capitalized_name( \$id $user) )
			&& ( \$this -> access -> action( 'edit' ) )
		){
			$external_fetch
			\$this -> model -> saved_fields	= reset ($$base_name); 
			\$this -> useView('form');
		}else
			\$this -> prg('gallery');
	}


	// show() - Display all information for specifed primary Id
	//
	// Retrieves all data for specified id and passes it to 'gallery' view

	function show( \$id ){
		if( 
			isset( \$id ) 
			&& \$this -> model -> get$capitalized_name(\$id $user) 
			&& ( \$this -> access -> action( 'show' ) )
		){
			\$this -> model -> set( 'show', true );
			\$this -> useView('show');
		}else
			\$this -> prg('gallery');
	}


	// gallery() - A gallery of items
	//
	// Displays items '{$capitalized_name}s' in gallery form.
	//
	// \$page - Current page, defaults to 1
	// \$order_col 	- The column to order by
	// \$order_sort 	- The sort to use

	function gallery(\$page = 1, \$order_col = null, \$order_sort = null){
		if ( \$this -> access -> action( 'gallery' ) ){
			\$this -> model -> gallery$capitalized_name( \$order_col, \$order_sort, \$page $user );
			\$this -> useView('table');
		}else
			\$this -> prg();
	}


}
?>
CONT;

/*
	// order() - Recieves/processes order options to be passed to be shown to the gallery.
	//
	// Recieves POST order options from gallery page and 
	// resubmits it as order options to gallery() via  controller::prg(). 

	function order(){
		$variables = array();
		$variables['page'] = 1;
		if ( isset($_POST['order']) && isset($_POST['sort'] )) {
			$variables['order'] = $_POST['order'];
			$variables['sort'] = $_POST['sort'];
			if(!empty($_POST['type'])){
				$variables['type'] =  $_POST['type'];
			}
			if(!empty($_POST['author'])){
			}
		}
		$this -> prg( 'gallery', $variables );
	}
*/

		$is_main = <<<is_main

	// about() - Run of the mill 'about' page

	function about(){
		\$this -> useView('about');
	}
is_main;


		return $controller;
	}

}


?>
