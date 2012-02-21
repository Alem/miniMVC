<?php

class UserController extends Controller{

	public $id;
	public $logged_in;
	public $username;
	public $email;


	function __construct(){
		parent::__construct();
		Session::open();
	}


	// index() - Displays login form or profile page depending on login status
	//
	// Shows welcome, goodbye and failure message depending on flags set by user::login() 
	//
	// $message - If set displays appropriate message, set by user::login()

	function index($message = null){
		if( $message == 'goodbye' )
			$this -> model -> goodbyeMsg = true;
		elseif( $message == 'failure' )
			$this -> model -> failMsg = true;
		elseif ( $message == 'welcome' )
			$this -> model -> welcomeMsg = Session::open() -> get('username');
		if( Session::open() -> get('logged_in') ){
			$this -> useView();
		}
		else
			$this -> useView('login');
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
			$user = $this -> model -> getUser($username,$password);
		}

		if( isset( $user ) ){
			$this -> logged_in = true;
			Session::open() -> set('username', $username);
			Session::open() -> set('email', $user[0]['email']);
			Session::open() -> set('logged_in', $this -> logged_in);
		}

		if ( $this -> logged_in )
			$this -> prg('index/welcome');
		else
			$this -> prg('index/failure');
	}


	// register() - Registers and logs in user if information valid
	// 
	// gets $_POST from registration HTML form and verifies standards met.
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
			$insert = $this -> model -> addUser($username, $password,$email);
			if ( $insert )
				$this -> logged_in = true;	
			Session::open() -> set('logged_in', $this -> logged_in);
			$this -> login();
		}else
			$this -> prg('index/failure');
	}


	// logout() - Logs user out
	//
	
	function logout(){
		Session::open() -> set('logged_in', null);
		session_destroy();
		$this -> prg('index/goodbye');
	}


	// getIp - gets the user's IP address
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


	function listing(){
		$data = $this -> model 
			-> select('id,user,email')
			-> run();
		echo '<pre>', print_r($data,true),'</pre>';
		#$this -> model -> data['show'] = $data;
		#$this -> useView('gallery','test');
	}


}

?>
