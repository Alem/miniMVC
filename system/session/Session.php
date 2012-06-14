<?php
/**
 * Session class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Session class is a singleton wrapper to aid in the
 * accessing and modifying the session
 *
 */
class Session
{

	/**
	 * @var string The session id of the current session
	 */
	public $id;

	/**
	 * @var array The data to be held in session.
	 */
	public $data;

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
		$root = dirname(__FILE__);
		require( $root . '/handlers/' . $type . '.php');

		$this->handler =  new $type;

		session_set_save_handler(
			array( $this->handler, 'open' ),
			array( $this->handler, 'close' ),
			array( $this->handler, 'read' ),
			array( $this->handler, 'write' ),
			array( $this->handler, 'destroy' ),
			array( $this->handler, 'gc' )
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
	 * get - Returns variables from $_SESSION array.
	 *
	 * @param string $property   The property to retrieve from the session
	 * @return mixed|bool        The retrieved property or false.
	 */
	public function get( $property )
	{
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
