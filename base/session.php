<?php

/**
 * Session class file.
 *
 * @author Zersenay Alem <info@alemmedia.com>
 * @link http://www.alemmedia.com/
 * @copyright Copyright &copy; 2008-2012 Alemmedia
 */

class Session{


	protected $id;
	private static $instance;

	/**
	 * @var mixed The data to be held in session.
	 */
	public $data;


	/**
	 * __construct - Starts session, establishes Session::data reference to $_SESSION superglobal array, and sets session id.
	 *
	 */
	private function __construct(){
		session_start();
		$this -> data =& $_SESSION;
		if ( !isset ( $this -> id ) )
			$this -> id = session_id();
	}


	/**
	 * open - Creates singleton instance of Session object
	 * 
	 * @return object
	 *
	 */

	public static function open() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * set - Recieves variables to set to $_SESSION array
	 *
	 * @param string $property   The property to set to the session
	 * @param mixed  $value      The balue to set to the property
	 * @param bool   $make_array If set to true the property will be an numeric array
	 *
	 */

	function set($property, $value = null, $make_array = false){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> set($key, $single_property);
		}else{
			if ($make_array == false)
				$this -> data[$property] = $value;
			else
				$this -> data[$property][] = $value;
		}
	}


	/**
	 * del - Recieves variables to delete in $_SESSION array
	 *
	 * @param string $property   The property to delete from the session
	 *
	 */

	function del($property){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> del($key);
		}else{
			unset($this -> data[$property]);
		}
	}


	/**
	 * get - Returns variables from $_SESSION array.
	 * 
	 * @param string $property   The property to retrieve from the session
	 * @return mixed|bool        The retrieved property or false.
	 *
	 */

	function get($property){
		if ( isset( $this -> data[$property] ) )
			return $this -> data[$property];
		else 
			return false;
	}

	/**
	 * get - Returns variables from $_SESSION array and subsequently deletes them.
	 * 
	 * @param string $property   The property to retrieve and delete from the session
	 * @return mixed             The retrieved property.
	 *
	 */

	function getThenDel($property){
		$result = $this -> get ($property);
		$this -> del ($property);
		return $result;
	}

}
?>
