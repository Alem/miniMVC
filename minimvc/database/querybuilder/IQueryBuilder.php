<?php
/**
 * IQueryBuilder interface file
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * @fixme Many parameters can be reduced to multiparameter array with optional keys
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.database.querybuilder.adapter
 */
interface IQueryBuilder
{
	/**
	 * select()
	 */
	public function select( $column, $table, $autoalias, $is_distinct);

	/**
	 * from()
	 */
	public function from( $table);
	
	/**
	 * insert()
	 */
	public function insert( $value, $column, $table);
	/**
	 * update()
	 */
	public function update($new, $new_column, $table);
	/**
	 * remove()
	 */
	public function remove($table);

	/**
	 * where()
	 */
	public function where( $ref, $ref_column, $table, $is_like);

	/**
	 * order()
	 */
	public function order( $orderby, $sort, $table);

	/**
	 * limit()
	 */
	public function limit($limit, $offset);

	/**
	 * joining()
	 */
	public function joining( $table_b, $columns_a, $columns_b , $type , $table_a );

	/**
	 * show()
	 */
	public function show( $table );

	/**
	 * getColumns()
	 */
	public function getColumns( $table );

}
?>
