<?php

class UserController extends Controller{

	public $logged_in;
	public $username;
	public $email;
	public $user_id;
	public $user_type;


	function __construct(){
		parent::__construct();
	}


	// actionIndex() - Displays login form or profile page depending on login status
	//
	// Shows welcome, goodbye and failure message depending on flags set by user::login() 
	//
	// $message - If set displays appropriate message, set by user::login()

	public function actionIndex($message = null){
		if( $message == 'goodbye' )
			$this -> model() -> goodbyeMsg = true;

		elseif( $message == 'failure' )
			$this -> model() -> failMsg = true;

		elseif ( $message == 'welcome' )
			$this -> model() -> welcomeMsg = Session::get('username');

		elseif ( $message == 'taken' )
			$this -> model() -> takenMsg = Session::get('username');

		if( Session::get('logged_in') ){
			$this -> model() -> title = Session::get('username');
			$this -> view();
		}
		else
			$this -> view('login');
	}


	// actionLogin () - Logs user in on successful credentials
	//
	// Recieves $_POST from login or registration form.
	// If successfull logs user in, sets logged_in as true and directs to index() with welcome flag set
	// If failure, directs to index() with failure flag set.

	public function actionLogin(){
		$username = $_POST['username'];
		$password = md5( $_POST['password'] );

		if ( isset( $username ) && isset( $password ) ){
			$user = $this -> model() -> getUser($username,$password);
		}

		if( !empty( $user ) ){
			$this -> logged_in = true;
			Session::set('username', $username);
			Session::set('email',    $user[0]['email']);
			Session::set('user_id',  $user[0]['id']);
			Session::set('user_type', (int)$user[0]['type']);
			Session::set('logged_in', $this -> logged_in);
		}

		if ( $this -> logged_in )
			$this -> prg('index/welcome');
		else
			$this -> prg('index/failure');
	}


	// actionRegister() - Registers and logs in user if information valid
	// 
	// gets $_POST from registration HTML form and verifies standards met.
	// If successfull insert sets logged_in to true and runs user::login()
	// If fails, directs to index with failure flag

	public function actionRegister(){
		$username = $_POST['username'];
		$password = md5( $_POST['password'] );
		if (	$this -> validates()	){
			if ( !( $this -> model() -> getUser( $username , $password ) ) ){
				$insert = $this -> model() -> addUser($username, $password, $_POST['email'] );
				if ( $insert )
					$this -> logged_in = true;	
				Session::set('logged_in', $this -> logged_in);
				$this -> login();
			}else
				$this -> prg('index/taken');
		}else
			$this -> prg('index/failure');
	}


	// actionSettings - Takes user to settings page
	//

	public function actionSettings(){
		if( Session::get('logged_in') ){
			#$user = $this -> model -> getUser($username,$password);
			$this -> view( 'settings' );
		}
	}


	// actionChange() - Change user settings
	//
	// If $setting is set to password, retrieves hashed password from session, compares it with old password
	// 	compares new password with its repeat, and if successful, updates password column to value of new password
	// 	If email, updates and renews session value for email.
	//
	// $setting - Determines the setting to be changed. Can be 'password' or 'email'.

	function actionChange( $setting ){
		if( Session::get('logged_in') ){
			if ( $setting == 'password' ){
				if (
				       	( Session::get ('password') == md5( $_POST['password_old'] ) ) 
					&& ( $_POST['password_repeat'] == $_POST['password_new'] )
				)
				$password_new =  md5( $_POST['password_new'] );
				$this -> model() -> editUser( $password_new, 'password', Session::get('username') );
			}elseif ( $setting == 'email' ){
				$this -> model() -> editUser( $_POST['email_new'] , 'email', Session::get('username') );
				Session::set( 'email', $_POST['email_new'] );
			}
		}
		$this -> prg('settings');
	}


	// validates - Performs validation on regristration POST data
	public function validates(){
		$password = md5( $_POST['password'] );
		$password_repeat = md5( $_POST['verify_password'] );
		if (	isset( $_POST['username'] )
		       	&& empty( $_POST['address'])
			&& isset( $_POST['email'] )  
			&& isset($password) 
			&& isset($password_repeat) 
			&& ( $password == $password_repeat )
		)
			return true;
		else
			return false;
	}

	// actionLogout() - Logs user out
	//
	
	public function actionLogout(){
		Session::set('logged_in', null);
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
		$data = $this -> model() 
			-> select('id,user,email')
			-> run();
		echo '<pre>', print_r($data,true),'</pre>';
		#$this -> model -> data['show'] = $data;
		#$this -> useView('gallery','test');
	}


}

?>
