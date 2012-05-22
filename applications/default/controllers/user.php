<?php

class UserController extends Controller
{

	public $logged_in;
	public $username;
	public $email;
	public $user_id;
	public $user_type;


	/**
	 * actionIndex() - Displays login form or profile page depending on login status
	 *
	 * Shows welcome, goodbye and failure message depending on flags set by user::login() 
	 *
	 * @param string $message 	 If set displays appropriate message, set by user::login()
	 */
	public function actionIndex($message = null)
	{
		$session = new Session();
		$config  = new Config();

		$this -> library('commonData') -> setConfigData( $config, $this -> model() );
		$this -> library('commonData') -> setSessionData( $session, $this -> model() );

		if( $message == 'goodbye' )
			$this -> model() -> goodbyeMsg = true;

		elseif( $message == 'failure' )
			$this -> model() -> failMsg = true;

		elseif ( $message == 'welcome' )
			$this -> model() -> welcomeMsg = $session -> get('username');

		elseif ( $message == 'taken' )
			$this -> model() -> takenMsg = $session -> get('username');

		if( $session -> get('logged_in') )
		{
			$this -> model() -> title = $session -> get('username');
			$this -> model() -> getUser( $session -> get('username'));
			$this -> view('index');
		}else
		{
			$this -> view('login');
		}
	}


	/**
	 * actionLogin () - Logs user in on successful credentials
	 * 
	 * Recieves POST data from login or registration form.
	 * If successfull logs user in, sets logged_in as true and directs to index() with welcome flag set
	 * If failure, directs to index() with failure flag set.
	 */
	public function actionLogin()
	{
		$request = new Request();
		$session = new Session();
		$config  = new Config();

		$this -> library('commonData') -> setConfigData( $config, $this -> model() );
		$this -> library('commonData') -> setSessionData( $session, $this -> model() );

		$username = $request -> post['username'];
		$password = md5( $request -> post['password'] );

		if ( isset( $username ) && isset( $password ) )
		{
			$user_data = $this -> model() -> getUser($username,$password);
		}

		if( !empty( $user_data ) )
		{
			$this -> logged_in = true;
			$session -> set('username', $username);
			$session -> set('email',    $user_data[0]['email']);
			$session -> set('user_id',  $user_data[0]['id']);
			$session -> set('user_type', (int)$user_data[0]['type']);
			$session -> set('logged_in', $this -> logged_in);
		}

		if ( $this -> logged_in )
			$this -> prg('index/welcome');
		else
			$this -> prg('index/failure');
	}


	/**
	 * actionRegister() - Registers and logs in user if information valid
	 * 
	 * gets POST data from registration HTML form and verifies standards met.
	 * If successfull insert sets logged_in to true and runs user::login()
	 * If fails, directs to index with failure flag
	 */
	public function actionRegister()
	{
		$request  = new Request();
		$session = new Session();
		$config  = new Config();

		$this -> library('commonData') -> setConfigData( $config, $this -> model() );
		$this -> library('commonData') -> setSessionData( $session, $this -> model() );

		$username = $request -> post['username'];
		$password = md5( $request -> post['password'] );
		if (	$this -> validates( $request -> post )	)
		{
			if ( !( $this -> model() -> getUser( $username , $password ) ) )
			{
				$insert = $this -> model() -> addUser($username, $password, $request -> post['email'] );
				if ( $insert )
					$this -> logged_in = true;	
				$session -> set('logged_in', $this -> logged_in);
				$this -> actionLogin();
			}else
				$this -> prg('index/taken');
		}else
			$this -> prg('index/failure');
	}


	/**
	 * actionSettings - Takes user to settings page
	 */
	public function actionSettings()
	{
		$session = new Session();
		$config  = new Config();

		$this -> library('commonData') -> setConfigData( $config, $this -> model() );
		$this -> library('commonData') -> setSessionData( $session, $this -> model() );

		if( $session -> get('logged_in') )
		{
			#$user = $this -> model -> getUser($username,$password);
			$this -> model() -> getUser( $session -> get('username'));
			$this -> view( 'settings' );
		}
	}


	/**
	 * actionChange() - Change user settings
	 *
	 * If $setting is set to password, retrieves hashed password from session, compares it with old password
	 * compares new password with its repeat, and if successful, updates password column to value of new password
	 * If email, updates and renews session value for email.
	 *
	 * @param string $setting 	Determines the setting to be changed. Can be 'password' or 'email'.
	 */
	function actionChange( $setting )
	{
		$request = new Request();
		$session = new Session();
		$config  = new Config();

		$this -> library('commonData') -> setConfigData( $config, $this -> model() );
		if( $session -> get('logged_in') )
		{
			if ( $setting == 'password' )
			{
				if (
					( $session -> get ('password') == md5( $request -> post['password_old'] ) ) 
					&& ( $request -> post['password_repeat'] == $request -> post['password_new'] )
				)
				$password_new =  md5( $request -> post['password_new'] );
				$this -> model() -> editUser( $password_new, 'password', $session -> get('username') );
			}elseif ( $setting == 'email' )
			{
				$this -> model() -> editUser( $request -> post['email_new'] , 'email', $session -> get('username') );
				$session -> set( 'email', $request -> post['email_new'] );
			}
		}
		$this -> prg('settings');
	}


	/**
	 * validates - Performs validation on regristration POST data
	 */
	public function validates( $post )
	{
		$password = md5( $post['password'] );
		$password_repeat = md5( $post['verify_password'] );
		if (	isset( $post['username'] )
			&& empty( $post['address'])
			&& isset( $post['email'] )  
			&& isset( $post['username'] ) 
			&& isset($password) 
			&& isset($password_repeat) 
			&& ( $password == $password_repeat )
			&& (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $post['username'])) //Special chars
		)
		return true;
		else
			return false;
	}

	/**
	 * actionLogout() - Logs user out
	 */
	public function actionLogout()
	{
		$session = new Session();

		$session -> set('logged_in', null);
		session_destroy();
		$this -> prg('index/goodbye');
	}


}

?>
