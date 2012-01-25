<?php

class UserController extends Controller{

	public $user_id;
	public $logged_in;
	public $username;
	public $email;
	
	function __construct(){
	}
	
	function start(){
		session_start();
		$this -> user_id = session_id();
	}

	function login($username,$password){
		// Check table for match Use Session?
		$this -> logged_in = true;	
	}
	
	function register($username, $password, $password_repeat, $email){

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
		else 
			return false;
	}

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
