<?php
/**
 * Controller template class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Controller template creates the controller scaffold file.
 * It names the class using the convention [Unit]Controller 
 * and write the file to the application's controllers directory as [Unit].php
 */
class Controller extends Template
{


	public function __construct( $name )
	{
		parent::__construct( $name );

		$this -> fileCache() -> path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CONTROLLER_PATH;  
	}


	public function externalModelFetch()
	{
		$external_fetch = null;
		if ( isset( $this -> queryTool() -> linked_columns ) ):
			foreach ( $this -> queryTool() -> linked_columns  as $column ):
				$uc_column = ucwords( $column );
		//--------------------------------------HTML START
		$external_fetch .= <<<fetch

			\$this -> model() -> set ( 
				'$column', 
				\$this -> model('$column') -> get$uc_column( null, \$session -> user_id' ) ) 
			);
fetch;
		//---------------------------------------HTML END
endforeach;
return $external_fetch;
endif;
	}


	/**
	 * scaffold() - Creates the content for the scaffold.
	 *
	 * @return string 	The conent for the scaffold file
	 */
	public function scaffold() 
	{
		$name  =& $this -> name;
		$uname =& $this -> uname;
		$external_fetch = $this -> externalModelFetch();
		$HTTP_ACCESS_PREFIX = HTTP_ACCESS_PREFIX;

		if ( 1 == 1 )
		{
			$user = ", \$session -> user_id' ) ";
			$user_single = " \$session -> get( 'user_id' ) ";
		}

		//----------------HTML START
		$controller = <<<CONT
<?php

class {$uname}Controller extends Controller
{

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


	/**
	 * access - Sets and enforces the permissions for each type of action. 
	 *
	 * Processed and enforced by the AccessControl 
	 */
	public function access()
	{
		if ( !isset ( \$this -> accessControl ) ) 
		{
			\$session = new Session();
			\$this -> accessControl = new AccessControl();
			\$this -> accessControl -> defineRoles( \$this -> permissions['roles'] );
			\$this -> accessControl -> defineActions( \$this -> permissions['actions'] );
			\$this -> accessControl -> setRole( \$session -> get('user_type') );
		}
		return \$this -> accessControl;
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Index() - Loads default 'index' view
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Index()
	{
		\$this -> prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Form() - Loads 'form' view
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Form()
	{
		\$session = new Session();
		\$session -> del('editing_{$name}_id');
		if( \$this -> access() -> action('form') )
		{
			$external_fetch
			\$this -> view('form');
		}else
			\$this -> prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Post() - Recieves POST data and hands it to model for database insertion
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Post()
	{
		\$request = new Request();
		\$session = new Session();
		if ( \$id = \$session -> getThenDel('editing_{$name}_id')  )
			\$this -> model() -> update$uname(  \$request -> post, \$id $user);
		elseif( \$this -> access() -> action('post') )
			\$id = \$this -> model() -> insert$uname( \$request -> post $user );
		\$this -> prg('show', \$id);
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Del() - Removes $uname from database.
	 *
	 * @param integer \$id The id of the $uname to delete.
	 */
	public function {$HTTP_ACCESS_PREFIX}Del( \$id )
	{
		if ( isset( \$id ) && \$this -> access() -> action( 'del') )
			\$this -> model() -> delete$uname ( \$id $user);
		\$this -> prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Edit() - Updates specified $uname in database.
	 *
	 * @param integer \$id The id of the $uname to update.
	 */
	public function {$HTTP_ACCESS_PREFIX}Edit(\$id)
	{
		\$session = new Session();
		\$session -> set( 'editing_{$name}_id', \$id );
		if( 
			isset( \$id )
			&& ( $$name = \$this -> model() -> get$uname( \$id $user) )
			&& ( \$this -> access() -> action( 'edit' ) )
		)
	{
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
	public function {$HTTP_ACCESS_PREFIX}Show( \$id )
	{
		\$session = new Session();
		if( 
			isset( \$id ) 
			&& \$this -> model() -> get$uname(\$id $user) 
			&& ( \$this -> access() -> action( 'show' ) )
		)
		{
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
	public function {$HTTP_ACCESS_PREFIX}Gallery(\$page = 1, \$order_col = null, \$order_sort = null )
	{
		\$session = new Session();
		if ( \$this -> access() -> action( 'gallery' ) )
		{
			\$request = new Request();
			\$search = null;
			if( !empty ( \$request -> get ) )
			{
				\$filtered_get = array_filter( \$request -> get );
				\$columns = str_replace ( '-', '.' , array_keys( \$filtered_get ) );
				\$values = array_values( \$filtered_get );
				if( !empty ( \$filtered_get ) )
					\$search = array ( 'columns' => \$columns , 'values' => \$values, 'query_string' => \$request -> query_string );
			}

			\$this -> model() -> gallery$uname( \$order_col, \$order_sort, \$page, \$search $user );
			\$this -> view('table');
		}else
			\$this -> prg();
	}


}
?>
CONT;
		//----------------HTML END

		return $controller;
	}

}



?>
