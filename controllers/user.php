<?php

class UserController extends Controller{

	public $user_id;
	public $logged_in;
	public $username;
	public $email;


	function __construct(){
		parent::__construct();
		$this -> start();
	}


	// index() - Displays login form or profile page depending on login status
	//
	// Shows welcome, goodbye and failure message depending on flags set by user::login() 
	//
	// $message - If set displays appropriate message, set by user::login()

	function index($message = null){
		if( $message == 'goodbye' )
			echo $this -> model -> goodbye();
		elseif( $message == 'failure' )
			echo $this -> model -> fail();
		if( $this -> sessionGet('logged_in') ){
			if ( $message == 'welcome' )
				echo $this -> model -> welcome();
			$this -> useView();
		}
		else
			$this -> useView('login');
	}


	// start() - Starts user session and stores session_id()

	function start(){
		if (!isset($_SESSION))
			session_start();
		$this -> user_id = session_id();
	}


	// getIp - Gets the user's IP address
	//
	// Grab users IP using any set $_SERVER variable (found in HTTP request header).
	// Can optionally return SQL formatted long Int IP address
	//
	// $int - Flag, if set returns long-int sql-formatted IP number.

	function getIp($int = false){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = array_pop(explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']));
		else
			$ip = $_SERVER['REMOTE_ADDR'];
		if($int == true)
			return ip2long($ip);
		else
			return $ip;
	}


	// login () - Logs user in on successful credentials
	//
	// Recieves $_POST from login or registration form.
	// If successfull logs user in, sets logged_in as true and directs to index() with welcome flag set
	// If failure, directs to index() with failure flag set.

	function login(){
		$username = $_POST['username'];
		$password = md5( $_POST['password'] );

		if ( isset( $username ) && isset( $password ) ){
			$user = $this -> model 
			-> select('*') 
			-> where( array($username, $password), array('user','password'))
			-> run();
		}

		if( isset( $user ) ){
			$this -> logged_in = true;
			$this -> sessionSet('username', $username);
			$this -> sessionSet('email', $user[0]['email']);
			$this -> sessionSet('logged_in', $this -> logged_in);
		}

		if ( $this -> logged_in )
			$this -> prg('index/welcome');
		else
			$this -> prg('index/failure');
	}


	// register() - Registers and logs in user if information valid
	// 
	// Gets $_POST from registration HTML form and verifies standards met.
	// If successfull insert sets logged_in to true and runs user::login()
	// If fails, directs to index with failure flag

	function register(){
		$username = $_POST['username'];
		$password = md5( $_POST['password'] );
		$password_repeat = md5( $_POST['verify_password'] );
		$email 	= $_POST['email'];
		if (	isset($username) 
			&& isset($password) 
			&& isset($password_repeat) 
			&& isset($email)  
			&& ( $password == $password_repeat ) 
			&& empty( $_POST['address'])
		){
			$insert = $this -> model 
				-> insert( array($username, $password,$email), array('user', 'password','email') )
				-> run();
			if ( $insert )
				$this -> logged_in = true;	
			$this -> sessionSet('logged_in', $this -> logged_in);
			$this -> login();
		}else
			$this -> prg('index/failure');
	}


	// logout() - Logs user out
	//
	
	function logout(){
		$this -> sessionSet('logged_in', null);
		session_destroy();
		$this -> prg('index/goodbye');
	}

	// sessionSet - Recieves variables to set to $_SESSION array
	//
	
	function sessionSet($property, $value = null, $make_array = false){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> sessionSet($key, $single_property);
		}else{
				if ($make_array == false)
					$_SESSION[$property] = $value;
				else
					$_SESSION[$property][] = $value;
		}
	}


	// sessionDel - Recieves variables to delete in $_SESSION array

	function sessionDel($property){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> sessionDel($key);
		}else{
				unset($_SESSION[$property]);
		}
	}

	// sessionGet - Returns variables from $_SESSION array.
	//
	function sessionGet($property){
		if ( isset( $_SESSION[$property] ) )
			return $_SESSION[$property];
		else 
			return false;
	}

	// timeSince() 
	//
	// Returns time since action
	
	function timeSince($action, $difference = null, $true_if_unset = true ){
		$time_set = $this -> sessionGet($action);
		if ( ( $time_set != false ) && ( isset( $difference ) ) && ( ( time() - $time_set ) > $difference ) ) 
			return true;
		elseif( !isset($difference) ) {
			$this -> sessionSet( $action, time() );
			return false;
		} elseif( ($time_set == false ) && ( $true_if_unset == true) ) 
			return true;
	}


	function listing(){
		$data = $this -> model 
			-> select('user,email')
			-> run();
		echo '<pre>', print_r($data,true),'</pre>';
		#$this -> model -> data['show'] = $data;
		#$this -> useView('gallery','test');
	}


}

?>
