<?php
/**
 * Authenticate class file
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * Authenticate objects provide user authentication and management
 * functions. It uses the Session object to store any stateful 
 * data. Authenticate objects may also use alternate authentication
 * providers (ex: Facebook login, Twitter, etc) given the provider 
 * has an AuthProvider class and impliments IAuthProvider
 *
 * Authenticate uses the AuthProvider to 
 * login,logout, register, and check login status.  ???
 *
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.auth
 */
class Authenticate extends Adaptable
{

	/**
	 * Stores the session instance
	 */
	public $session = null;


	/**
	 * Stores the provider instance(s)
	 */
	public $provider = array();


	/**
	 * Adapter type, adapter package, and directory of adapting class
	 * Required by Adaptable::useAdapter
	 * @var array
	 */
	public $adapter_info = array( 
		'type' => 'Provider', 
		'package' => 'Auth', 
		'adapting_dir' => __DIR__ 
	);


	/**
	 * provider() - Set AuthProvider instance
	 */
	public function provider( $provider )
	{
		return $this->useAdapter( $provider );	
	}

	/**
	 * useSession() - Set session instance for session access
	 */
	public function session( Session $session )
	{
		$this->session = $session;
	}


	/**
	 */
	public function login()
	{
		return $this->provider->login();
	}


}

?>
