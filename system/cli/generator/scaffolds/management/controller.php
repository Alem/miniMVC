<?php


class Controller extends Generator{

	public function scaffold( $user = true ) {
		
		$name  =& $this -> name;
		$uname =& $this -> uname;
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


/*********************** BEGIN HTML ****************************************************************/
					$external_fetch .= <<<fetch

			\$this -> model() -> set ( 
				'$base_column', 
				\$this -> model('$base_column') -> get$uc_base_coumn( null, Session::open() -> get( 'user_id' ) ) 
			);
fetch;
######################## END HTML   #################################################################
				}
			}
		}



/*********************** BEGIN HTML ****************************************************************/
		$rules = <<<RULES
	/**
 	 * @var array Sets the permissions for each type of action. Processed and enforced by the Access module.
	 */
	public \$permissions = array(

		'roles' => array(
			'0'	=> array('c', 'r', 'u', 'd'),
			'1'	=> array('c', 'r', 'u', 'd'),
			'2'	=> array('', 'r', '', ''),
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
######################## END HTML   #################################################################

		$HTTP_ACCESS_PREFIX = HTTP_ACCESS_PREFIX;

/*********************** BEGIN HTML ****************************************************************/
		$controller = <<<CONT
<?php

class {$uname}Controller extends Controller{

	public function __construct(){
		parent::__construct();
	}


	/**
	 * access - Sets and enforces the permissions for each type of action. 
	 *
	 * Processed and enforced by the AccessControl 
	 */
	public function access(){
		if ( !isset ( \$this -> accessControl ) ) {
			\$roles = array(
				'0'	=> array('c', 'r', 'u', 'd'),
				'1'	=> array('c', 'r', 'u', 'd'),
				'2'	=> array('', 'r', '', ''),
				''	=> array('', '', '', ''),
			);

			\$actions = array(
				'c'	=> array( 'post', 'form' ),
				'r'	=> array( 'show', 'gallery' ),
				'u' 	=> array( 'edit' ),
				'd'	=> array( 'del')
			);

			\$this -> accessControl = new AccessControl();
			\$this -> accessControl -> defineRoles( \$roles );
			\$this -> accessControl -> defineActions( \$actions );
			\$this -> accessControl -> setRole( Session::get('user_type') );
		}
		return \$this -> accessControl;
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Index() - Loads default 'index' view
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Index(){
		\$this -> prg('gallery');
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Form() - Loads 'form' view
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Form(){
		Session::open() -> del('editing_{$name}_id');
		if( \$this -> access() -> action('form') ){
			$external_fetch
			\$this -> view('form');
		}else
			\$this -> prg('gallery');
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Post() - Recieves POST data and hands it to model for database insertion
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Post(){
		if ( \$id = Session::open() -> getThenDel('editing_{$name}_id')  )
			\$this -> model() -> update$uname( \$id $user);
		elseif( \$this -> access() -> action('post') )
			\$id = \$this -> model() -> insertPOST( $user_single );
		\$this -> prg('show', \$id);
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Del() - Removes $uname from database.
	 *
	 * @param integer \$id The id of the $uname to delete.
	 */
	public function {$HTTP_ACCESS_PREFIX}Del( \$id ){
		if ( isset( \$id ) && \$this -> access() -> action( 'del') )
			\$this -> model() -> delete$uname ( \$id $user);
		\$this -> prg('gallery');
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Edit() - Updates specified $uname in database.
	 *
	 * @param integer \$id The id of the $uname to update.
	 */
	public function {$HTTP_ACCESS_PREFIX}Edit(\$id){
		Session::open() -> set( 'editing_{$name}_id', \$id );
		if( 
			isset( \$id )
			&& ( $$name = \$this -> model() -> get$uname( \$id $user) )
			&& ( \$this -> access() -> action( 'edit' ) )
		){
			$external_fetch
			\$this -> model() -> saved_fields	= reset ($$name); 
			\$this -> view('form');
		}else
			\$this -> prg('gallery');
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Show() - Retrieves and displays specified $uname
	 *
	 * @param integer \$id The id of the $uname to show.
	 */
	public function {$HTTP_ACCESS_PREFIX}Show( \$id ){
		if( 
			isset( \$id ) 
			&& \$this -> model() -> get$uname(\$id $user) 
			&& ( \$this -> access() -> action( 'show' ) )
		){
			\$this -> model() -> set( 'show', true );
			\$this -> view('show');
		}else
			\$this -> prg('gallery');
	}


	/**
 	 * {$HTTP_ACCESS_PREFIX}Gallery() -  Displays items '{$uname}s' in gallery form.
	 *
	 * @param integer \$page        Current page, defaults to 1
	 * @param string  \$order_col   The column to order by
	 * @param string  \$order_sort  The sort to use
	 */
	public function {$HTTP_ACCESS_PREFIX}Gallery(\$page = 1, \$order_col = null, \$order_sort = null ){
		if ( \$this -> access() -> action( 'gallery' ) ){
			
			\$search = null;
			if( !empty ( \$_GET ) ){
				\$_GET = array_filter( \$_GET );
				\$columns = str_replace ( '-', '.' , array_keys( \$_GET ) );
				\$values = array_values( \$_GET );
				if( !empty ( \$_GET ) )
					\$search = array ( 'columns' => \$columns , 'values' => \$values );
			}

			\$this -> model() -> gallery$uname( \$order_col, \$order_sort, \$page, \$search $user );
			\$this -> view('table');
		}else
			\$this -> prg();
	}


}
?>
CONT;
######################## END HTML   #################################################################



/*********************** BEGIN HTML ****************************************************************/
		$is_main = <<<is_main

	// {$HTTP_ACCESS_PREFIX}About() - Run of the mill 'about' page

	public function {$HTTP_ACCESS_PREFIX}About(){
		\$this -> view('about');
	}
is_main;
######################## END HTML   #################################################################


		return $controller;
	}

}



?>
