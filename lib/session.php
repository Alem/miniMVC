<?php

class Session{


	private static $instance;
	private static $id;


	private function __construct(){
		session_start();
		self::$id = session_id();
	}


	public static function open() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	// set - Recieves variables to set to $_SESSION array

	function set($property, $value = null, $make_array = false){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> set($key, $single_property);
		}else{
			if ($make_array == false)
				$_SESSION[$property] = $value;
			else
				$_SESSION[$property][] = $value;
		}
	}


	// del - Recieves variables to delete in $_SESSION array

	function del($property){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> del($key);
		}else{
			unset($_SESSION[$property]);
		}
	}


	// get - Returns variables from $_SESSION array.

	function get($property){
		if ( isset( $_SESSION[$property] ) )
			return $_SESSION[$property];
		else 
			return false;
	}


	// timeSince() 
	//
	// Returns time since action

	function timeSince($action, $difference = null, $true_if_unset = true ){
		$time_set = $this -> get($action);
		if ( ( $time_set != false ) && ( isset( $difference ) ) && ( ( time() - $time_set ) > $difference ) ) 
			return true;
		elseif( !isset($difference) ) {
			$this -> set( $action, time() );
			return false;
		} elseif( ($time_set == false ) && ( $true_if_unset == true) ) 
			return true;
	}


}
?>
