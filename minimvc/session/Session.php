<?php
/**
 * Session class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Session class is a singleton wrapper to aid in the
 * accessing and modifying the session
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.session
 * @todo Implement ISession, create adapters
 */
class Session 
{

	/**
	 * The session id of the current session
	 * @var string
	 */
	public $id;

	/**
	 * The data to be held in session.
	 * @var array
	 */
	public $data;


	/**
	 * Adapter type, adapter package, and directory of adapting class
	 * Required by Adaptable::useAdapter
	 * @var array
	 */
	public $adapter_info = array( 
		'type' => 'Handler', 
		'package' => 'Session', 
		'adapting_dir' => __DIR__ 
	);


	/**
	 * construct() - Optionally opens session
	 *
	 * @param bool $autostart 	If true, opens the session
	 */
	public function __construct( $autostart = true )
	{
		if ( $autostart )
			$this->open();
	}

	/**
	 * open -  Starts session
	 *
	 * @return object
	 */
	public function open()
	{
		if ( session_id() == '' )
			session_start();

		$this->id   = session_id();
		$this->data =& $_SESSION;
	}

	/**
	 * handler - Set session handler
	 *
	 * @param string $type 		The driver type
	 *
	 * @todo update to PHP5.4 session_set_save_handler prototype
	 */
	public function handler( $type )
	{
		$handler = $this->useAdapter( $type );	

		session_set_save_handler(
			array( $handler, 'open' ),
			array( $handler, 'close' ),
			array( $handler, 'read' ),
			array( $handler, 'write' ),
			array( $handler, 'destroy' ),
			array( $handler, 'gc' )
		);

		// 'prevents unexpected effects when using objects as save handlers'
		register_shutdown_function('session_write_close');

	}

	/**
	 * set - Recieves variables to set to $_SESSION array
	 *
	 * @param string $property   The property to set to the session
	 * @param mixed  $value      The balue to set to the property
	 * @param bool   $make_array If set to true the property will be an numeric array
	 */
	public function set( $property, $value = null, $make_array = false )
	{
		if ( is_array( $property) )
		{
			foreach( $property as $key => $single_property )
				$this->set( $key, $single_property );
		}
		else
		{
			if ($make_array == false)
				 $this->data[$property] = $value;
			else
				 $this->data[$property][] = $value;
		}
	}

	/**
	 * del - Recieves variables to delete in $_SESSION array
	 *
	 * @param string $property   The property to delete from the session
	 */
	public function del( $property )
	{
		if ( is_array( $property) )
		{
			foreach( $property as $key => $single_property )
				 $this->del( $key );
		}else
			unset( $this->data[$property] );
	}

	/**
	 * get - Returns variables from $_SESSION array. Returns all session data if no specific property requested.
	 *
	 * @param string $property   The property to retrieve from the session
	 * @return mixed|bool        The retrieved property or false.
	 */
	public function get( $property = null )
	{
		if( $property === null )
			return $this->data;

		if ( isset( $this->data[$property] ) )
			return $this->data[$property];
		else
			return false;
	}

	/**
	 * get - Returns variables from $_SESSION array and subsequently deletes them.
	 *
	 * @param string $property   The property to retrieve and delete from the session
	 * @return mixed             The retrieved property.
	 */
	public function getThenDel( $property )
	{
		$result = $this->get( $property );
		$this->del( $property );
		return $result;
	}


	/**
	 * destroy() - Destroys session
	 */
	public function destroy()
	{
		session_destroy();
	}

}
?>
