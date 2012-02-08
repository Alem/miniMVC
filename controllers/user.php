<?php

class UserController extends Controller{

	public $user_id;
	public $logged_in;
	public $username;
	public $email;

	function __construct(){
		parent::__construct();
		$this -> model -> nav = $this -> menu -> nav();
		$this -> start();
	}

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

	function start(){
		if (!isset($_SESSION))
			session_start();
		$this -> user_id = session_id();
	}

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

	function login(){
		$username = $_POST['username'];
		$password = md5( $_POST['password'] );
		if ( ( isset( $username ) && isset( $password ) ) )
			$user = $this -> model -> select('*') -> where( array($username, $password), array('user','password'))->run();
		if($user){
			$this -> logged_in = true;
			$this -> sessionSet('username', $username);
		}
		$this -> sessionSet('logged_in', $this -> logged_in);
		$this -> sessionSet('username', $username);
		$this -> sessionSet('email', $user[0]['email']);
		if ( $this -> logged_in )
			$this -> prg('index/welcome');
		else
			$this -> prg('index/failure');
	}

	function register(){
		$username = $_POST['username'];
		$password = md5( $_POST['password'] );
		$password_repeat = md5( $_POST['verify_password'] );
		$email 	= $_POST['email'];
		if ( ( isset($username) && isset($password) && isset($password_repeat) && isset($email)) && ($password == $password_repeat) )
			if ($this->model->insert(array($username, $password,$email), array('user', 'password','email'))->run() )
				$this -> logged_in = true;	
		$this -> set('logged_in', $this -> logged_in);
		$this -> login();
	}

	function listing(){
			$data = $this -> model -> select('user,email')->run();
			$this -> model -> data['show'] = $data;
			$this -> useView('gallery','test');
	}

	function logout(){
		$this -> sessionSet('logged_in', null);
		session_destroy();
		$this -> prg('index/goodbye');
	}

	function sessionSet($property, $value = null,$clear = false){
		if ( is_array( $property) ){
			foreach($property as $key => $single_property)
				$this -> sessionSet($key, $single_property,$clear);
		}else{
			if ($clear == false)
				$_SESSION[$property] = $value;
			else
				unset($_SESSION[$property]);
		}
	}

	function sessionGet($property){
		if ( isset( $_SESSION[$property] ) )
			return $_SESSION[$property];
		else 
			return false;
	}

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

}

?>
