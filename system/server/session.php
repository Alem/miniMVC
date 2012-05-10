<?php
/**
 * Session class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Session class is a singleton wrapper to aid in the 
 * accessing and modifying the session
 *
 */
class Session
{


	/**
	 * @var object Holds the single instance of Session
	 */
	private static $instance;


	/**
	 * @var string The session id of the current session
	 */
	protected $id;


	/**
	 * @var mixed The data to be held in session.
	 */
	public $data;


	/**
	 * __construct - Starts session
	 *
	 *  Establishes Session::data reference to $_SESSION superglobal array, and sets session id.
	 *  Privately held an called only by Session::open for singleton functionality
	 */
	private function __construct()
	{
		session_start();
		$this -> id   = session_id();
		$this -> data =& $_SESSION;
	}


	/**
	 * open - Creates singleton instance of Session object
	 * 
	 * @return object
	 */
	public static function open() {
		if ( !isset(self::$instance) )
			self::$instance = new self();
		return self::$instance;
	}


	/**
	 * set - Recieves variables to set to $_SESSION array
	 *
	 * @param string $property   The property to set to the session
	 * @param mixed  $value      The balue to set to the property
	 * @param bool   $make_array If set to true the property will be an numeric array
	 */
	public static function set($property, $value = null, $make_array = false)
	{
		if ( is_array( $property) )
		{
			foreach($property as $key => $single_property)
				Session::open() -> set($key, $single_property);
		}
		else
		{
			if ($make_array == false)
				Session::open() -> data[$property] = $value;
			else
				Session::open() -> data[$property][] = $value;
		}
	}


	/**
	 * del - Recieves variables to delete in $_SESSION array
	 *
	 * @param string $property   The property to delete from the session
	 */
	public static function del($property)
	{
		if ( is_array( $property) )
		{
			foreach($property as $key => $single_property)
				Session::open() -> del($key);
		}else
			unset(Session::open() -> data[$property]);
	}


	/**
	 * get - Returns variables from $_SESSION array.
	 * 
	 * @param string $property   The property to retrieve from the session
	 * @return mixed|bool        The retrieved property or false.
	 */
	public static function get($property)
	{
		if ( isset( Session::open() -> data[$property] ) )
			return Session::open() -> data[$property];
		else 
			return false;
	}


	/**
	 * get - Returns variables from $_SESSION array and subsequently deletes them.
	 * 
	 * @param string $property   The property to retrieve and delete from the session
	 * @return mixed             The retrieved property.
	 */
	public static function getThenDel($property)
	{
		$result = Session::open() -> get ($property);
		Session::open() -> del ($property);
		return $result;
	}

}
?>
