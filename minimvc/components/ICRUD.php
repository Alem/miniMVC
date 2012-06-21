<?php
/**
 * ICRUD interface file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The ICRUD interface dictates the expected methods for a
 * component implementing standard CRUD features.
 * This interface is used optionally and introduces 
 * standardization and consistency among components with 
 * CRUD features. 
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.components
 */
interface ICRUD
{
	/**
	 * create() - Must create new component unit
	 */
	public function create( array $params );

	/**
	 * retrieve() - Must fetch component unit
	 */
	public function retrieve( array $params );

	/**
	 * update() - Must modify component unit
	 */
	public function update( array $params );
	
	/**
	 * delete() - Must delete component unit
	 */
	public function delete( array $params );
}
