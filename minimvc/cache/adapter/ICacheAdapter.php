<?php
/**
 * ICacheAdapter interface file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The ICacheAdapter interface establishes the expected interface of cache adapter classes.
 */
interface ICacheAdapter
{

	/**
	 * add() - Adds value to cache if it doesn't exist
	 *
	 * @param mixed $data 
	 * @param mixed $id 
	 * @param mixed $expiry 
	 */
	public function add( $data, $id, $expiry );

	/**
	 * set() - Sets value to cache; if it exists, replace it.
	 *
	 * @param mixed $data 
	 * @param mixed $id 
	 * @param mixed $expiry 
	 */
	public function set( $data, $id, $expiry );

	/**
	 * get() - Retrieves value, specified by id, from cache.
	 *
	 * @param mixed $id 
	 */
	public function get( $id );

	/**
	 * del() - Deletes value, specified by id, from cache.
	 *
	 * @param mixed $id 
	 */
	public function del( $id );

	/**
	 * flush() - Deletes all values currently stored in cache.
	 */
	public function flush();

}
?>
