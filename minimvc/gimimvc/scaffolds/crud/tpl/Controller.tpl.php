<?php
/**
 * Controller Scaffold class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Controller Scaffold creates the controller scaffold file.
 * It names the class using the convention [Unit]Controller
 * and write the file to the application's controllers directory as [Unit].php
 */
class Controller extends Scaffold
{


	public function initialize()
	{
		$path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CONTROLLER_PATH;
		$this->file( ucwords( $this->name ) . 'Controller', $path );
	}


	public function externalModelFetch( $user )
	{
		$external_fetch = null;
		if ( isset( $this->queryTool()->linked_columns ) ):
			foreach ( $this->queryTool()->linked_columns  as $column ):
				if( isset( $this->config['ownership_id'] )
					&& ( $this->config['ownership_id'] != $column . '_id' )
				):
				$uc_column = ucwords( $column );
		//--------------------------------------CODE START
		$external_fetch .= <<<fetch

			\$this->model()->set(
				'$column',
				\$this->model('$uc_column')->retrieve( null $user )
			);
fetch;
		//---------------------------------------CODE END
				endif;
			endforeach;
			return $external_fetch;
		endif;
	}


	/**
	 * getContent() - Creates the content for the scaffold.
	 *
	 * @return string 	The conent for the scaffold file
	 */
	public function getContent()
	{
		$name  =& $this->name;
		$uname =& $this->uname;


		$config = new Config();
		$settings = $config->fetch( 'application' );
		$HTTP_ACCESS_PREFIX = $settings['http_access_prefix'];

		if ( isset( $this->config['ownership_id'] ) )
		{
			$user = ", \$session->get( '{$this->config['ownership_id']}' ) ";
			$user_single = " \$session->get( '{$this->config['ownership_id']}' ) ";
		}

		$external_fetch = $this->externalModelFetch( $user );

		//----------------CODE START
		$controller = <<<CONT
<?php

class {$uname}Controller extends Controller
{

	/**
	 * Sets the permissions for each type of action. Processed and enforced by the Access module.
	 * @var array
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
		if ( !isset ( \$this->accessControl ) )
		{
			\$session = new Session();
			\$this->accessControl = new AccessControl();
			\$this->accessControl->defineRoles( \$this->permissions['roles'] );
			\$this->accessControl->defineActions( \$this->permissions['actions'] );
			\$this->accessControl->setRole( \$session->get('user_type') );
		}
		return \$this->accessControl;
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Index() - Loads default 'index' view
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Index()
	{
		\$this->prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Form() - Loads 'form' view
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Form()
	{
		\$session = new Session();
		\$config  = new Config();

		\$session->del('editing_{$name}_id');
		if( \$this->access()->action('form') )
		{
			$external_fetch
			\$this->content('form' )
				->render( array(
				'model' => \$this->model()->get(),
				'session' => \$session->get(),
				'config' => \$config->fetch('application'),
			));
		}else
			\$this->prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Post() - Recieves POST data and hands it to model for database insertion
	 *
	 */
	public function {$HTTP_ACCESS_PREFIX}Post()
	{
		\$request = new Request();
		\$session = new Session();

		if ( \$id = \$session->getThenDel('editing_{$name}_id')  )
			\$this->model()->update(  \$request->post, \$id $user);

		elseif( \$this->access()->action('post') )
			\$id = \$this->model()->insert$uname( \$request->post $user );

		\$this->prg('show', \$id);
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Del() - Removes $uname from database.
	 *
	 * @param integer \$id The id of the $uname to delete.
	 */
	public function {$HTTP_ACCESS_PREFIX}Del( \$id )
	{
		\$session = new Session();

		if ( isset( \$id ) && \$this->access()->action( 'del') )
			\$this->model()->delete( \$id $user);

		\$this->prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Edit() - Updates specified $uname in database.
	 *
	 * @param integer \$id The id of the $uname to update.
	 */
	public function {$HTTP_ACCESS_PREFIX}Edit(\$id)
	{
		\$session = new Session();
		\$config  = new Config();

		\$session->set( 'editing_{$name}_id', \$id );
		if(
			isset( \$id )
			&& ( $$name = \$this->model()->retrieve( \$id $user) )
			&& ( \$this->access()->action( 'edit' ) )
		)
		{
			$external_fetch
			\$this->model()->set( 'saved_fields', reset ($$name) );

			\$this->content('form' )
				->render( array(
				'model' => \$this->model()->get(),
				'session' => \$session->get(),
				'config' => \$config->fetch('application'),
				'settings' => array( 'editing' => true ),
			));
		}
		else
			\$this->prg('gallery');
	}


	/**
	 * {$HTTP_ACCESS_PREFIX}Show() - Retrieves and displays specified $uname
	 *
	 * @param integer \$id The id of the $uname to show.
	 */
	public function {$HTTP_ACCESS_PREFIX}Show( \$id )
	{
		\$session = new Session();
		\$config  = new Config();

		if(
			isset( \$id )
			&& \$this->model()->retrieve(\$id $user)
			&& ( \$this->access()->action( 'show' ) )
		)
		{
			\$this->content('show' )
				->render( array(
				'model' => \$this->model()->get(),
				'session' => \$session->get(),
				'config' => \$config->fetch('application'),
				'settings' => array('show' => true ),
			));
		}else
			\$this->prg('gallery');
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
		\$config  = new Config();

		if ( \$this->access()->action( 'gallery' ) )
		{
			\$request = new Request();
			\$search = null;
			if( !empty ( \$request->get ) )
			{
				\$filtered_get = array_filter( \$request->get );
				\$columns = str_replace ( '-', '.' , array_keys( \$filtered_get ) );
				\$values = array_values( \$filtered_get );
				if( !empty ( \$filtered_get ) )
					\$search = array ( 'columns' => \$columns , 'values' => \$values, 'query_string' => \$request->query_string );
			}

			\$this->model()->listing( \$order_col, \$order_sort, \$page, \$search $user );

			\$this->content('table' )
				->render( array(
				'model' => \$this->model()->get(),
				'session' => \$session->get(),
				'config' => \$config->fetch('application'),
			));
		}else
			\$this->prg();
	}

}
?>
CONT;
		//----------------CODE END

		return $controller;
	}

}

?>
