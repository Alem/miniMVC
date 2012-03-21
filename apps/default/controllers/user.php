<?php

class UserController extends Controller{

	public $logged_in;
	public $username;
	public $email;
	public $user_id;
	public $user_type;


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
		elseif ( $message == 'taken' )
			$this -> model -> takenMsg = Session::open() -> get('username');
		if( Session::open() -> get('logged_in') ){
			$this -> model -> title = Session::open() -> get('username');
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

		if( !empty( $user ) ){
			$this -> logged_in = true;
			Session::open() -> set('username', $username);
			Session::open() -> set('email', $user[0]['email']);
			Session::open() -> set('user_id', $user[0]['id']);
			Session::open() -> set('user_type', (int)$user[0]['type']);
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
			if ( !( $this -> model -> getUser( $username , $password ) ) ){
				$insert = $this -> model -> addUser($username, $password,$email);
				if ( $insert )
					$this -> logged_in = true;	
				Session::open() -> set('logged_in', $this -> logged_in);
				$this -> login();
			}else
				$this -> prg('index/taken');
		}else
			$this -> prg('index/failure');
	}


	// settings - Takes user to settings page
	//

	function settings(){
		if( Session::open() -> get('logged_in') ){
			#$user = $this -> model -> getUser($username,$password);
			$this -> useView( 'settings' );
		}
	}


	// change() - Change user settings
	//
	// If $setting is set to password, retrieves hashed password from session, compares it with old password
	// 	compares new password with its repeat, and if successful, updates password column to value of new password
	// 	If email, updates and renews session value for email.
	//
	// $setting - Determines the setting to be changed. Can be 'password' or 'email'.

	function change( $setting ){
		if( Session::open() -> get('logged_in') ){
			if ( $setting == 'password' ){
				if (
				       	( Session::open() -> get ('password') == md5( $_POST['password_old'] ) ) 
					&& ( $_POST['password_repeat'] == $_POST['password_new'] )
				)
				$password_new =  md5( $_POST['password_new'] );
				$this -> model -> editUser( $password_new, 'password', Session::open() -> get('username') );
			}elseif ( $setting == 'email' ){
				$this -> model -> editUser( $_POST['email_new'] , 'email', Session::open() -> get('username') );
				Session::open() -> set( 'email', $_POST['email_new'] );
			}
		}
		$this -> prg('settings');
	}


	// verify password - Checks if two passwords are the same
	//
	// $password - First password
	// $repeat_password - Second password

	function verify( $password, $repeat_password ){
		if ( $password == $repeat_password )
			return true;
		else
			return false;
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
