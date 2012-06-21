<?php
/**
 * Cache class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 *
 */
class Cache extends Adaptable
{

	/**
	 * Adapter type, adapter package, and directory of adapting class
	 * Required by Adaptable::useAdapter
	 * @var array
	 */
	public $adapter_info = array( 
		'type' => 'Adapter', 
		'package' => 'Cache', 
		'adapting_dir' => __DIR__ 
	);


	/**
	 * adapter() - Set cache adapter instance
	 */
	public function adapter( $adapter )
	{
		return $this->useAdapter( $adapter );	
	}


	/**
	 * add() - Adds value to cache if it doesn't exist
	 *
	 * @param mixed $data 
	 * @param mixed $id 
	 * @param mixed $expiry 
	 */
	public function add( $data, $id, $expiry )
	{
		$this->adapter()->add( $data, $id, $expiry );
	}

	/**
	 * set() - Sets value to cache; if it exists, replace it.
	 *
	 * @param mixed $data 
	 * @param mixed $id 
	 * @param mixed $expiry 
	 */
	public function set( $data, $id, $expiry )
	{
		$this->adapter()->set( $data, $id, $expiry );
	}

	/**
	 * get() - Retrieves value, specified by id, from cache.
	 *
	 * @param mixed $id 
	 */
	public function get( $id )
	{
		$this->adapter()->get( $id );
	}

	/**
	 * del() - Deletes value, specified by id, from cache.
	 *
	 * @param mixed $id 
	 */
	public function del( $id )
	{
		$this->adapter()->del( $id );
	}

	/**
	 * flush() - Deletes all values currently stored in cache.
	 */
	public function flush()
	{
		$this->adapter()->flush();
	}

}
?>
