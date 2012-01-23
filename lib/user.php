<?php

class User{

	function __construct(){
		session_start();
		$this -> user_id = session_id();
	}

	function login($username,$password){

	}

	function logout(){
		session_destroy();
	}

	function set($property,$value){
		$_SESSION[$property] = $value;
	}

	function get($property){
		if ( isset( $_SESSION[$property] ) )
			return $_SESSION[$property];
	}

	function recency($action, $difference = false ){
		if ( $difference != false ){
			if ( ( time() - $this -> get($action) ) > $difference )
				return true;
		}else{
			$this -> set( $action, time() );
		}
	}
}

?>
