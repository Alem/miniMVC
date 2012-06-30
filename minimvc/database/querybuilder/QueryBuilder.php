<?php
/**
 * QueryBuilder class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The QueryBuilder class allows a simplified abstraction layer for
 * constructing and running queries. 
 * This allows for simpler sequential construction of queries.
 *
 * ------------------------------
 * Example:
 *
 *    $database     = new Database();
 *    $QueryBuilder = new QueryBuilder( $database );
 *
 *    $QueryBuilder->select('*')
 * 		-> from('table')
 * 		-> where('value','column')
 * 		-> run();
 * ------------------------------
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.database
 */
class QueryBuilder extends Adaptable implements IQueryBuilder
{

	/**
	 * Stores the adapter instance(s)
	 * @var array
	 */
	public $adapter = array();


	/**
	 * Adapter type, adapter package, and directory of adapting class
	 * Required by Adaptable::useAdapter
	 * @var array
	 */
	public $adapter_info = array( 
		'type' => 'Adapter', 
		'package' => 'QB', 
		'adapting_dir' => __DIR__ 
	);


	/**
	 * getDriver() - Determines correct adapter using database driver value
	 *
	 * @param DbQuery $dbquery 	Instance of DbQuery
	 * @return  string 	Adapter name
	 * @todo Update on adding adapters
	 */
	public function getDriver( $dbquery )
	{
		$lc_driver = strtolower( $dbquery->database->settings['driver'] );
		
		if( $lc_driver === 'mysql' )
			$adapter = 'MySQL';
		else
			$adapter = null;

		return $adapter;
	}


	/**
	 * adapter() - Set AuthProvider instance
	 *
	 * @param string $adapter 	The base name of the adapter
	 * @return IQueryBuilder 	The database adapter object implementing IQueryBuilder
	 *
	 * @fixme Default adapter determination is flawed, not CI
	 */
	public function adapter( $adapter = null )
	{
		return $this->useAdapter( $adapter );
	}

	/**
	 * construct() - Sets the default adapter and DbQuery object
	 *
	 * @param DbQuery $dbquery 	Instance of DbQuery
	 * @param string $adapter 	The base name of the adapter
	 */
	public function __construct( DbQuery $dbquery, $adapter = null )
	{
		$this->loadDbQuery( $dbquery, $adapter );
	}

	/**
	 * loadDbQuery() - Loads DbQuery instance
	 *
	 * @param DbQuery $dbquery 	Instance of DbQuery
	 * @param string $adapter 	The base name of the adapter
	 */
	public function loadDbQuery( DbQuery $dbquery, $adapter = null )
	{
		if( $adapter === null )
			$adapter = $this->getDriver( $dbquery );

		$this->adapter( $adapter )
			->storeDbQuery( $dbquery )
			->useDbQuery( $adapter, $dbquery );
	}

	public function select( $column = '*', $table = null, $autoalias = false, $is_distinct = false)
	{
		return $this->adapter()->select( $column, $table, $autoalias, $is_distinct );
	}

	public function from( $table = null)
	{
		return $this->adapter()->from( $table );
	}

	public function insert( $value, $column = null, $table =  null)
	{
		return $this->adapter()->insert( $value, $column, $table );
	}

	public function update($new, $new_column = null, $table = null )
	{
		return $this->adapter()->update($new, $new_column, $table );
	}

	public function remove($table = null )
	{
		return $this->adapter()->remove($table);
	}

	public function where( $ref, $ref_column = null, $table = null, $is_like = false )
	{
		return $this->adapter()->where( $ref, $ref_column, $table, $is_like );
	}

	public function order( $orderby, $sort, $table = null)
	{
		return $this->adapter()->order( $orderby, $sort, $table );
	}

	public function limit($limit, $offset = null)
	{
		return $this->adapter()->limit($limit, $offset );
	}

	public function joining( $table_b, $columns_a, $columns_b , $type = 'LEFT OUTER', $table_a = null ) 
	{
		return $this->adapter()->joining( $table_b, $columns_a, $columns_b , $type, $table_a ) ;
	}

	public function show( $table )
	{
		return $this->adapter()->show( $table );
	}

	public function getColumns( $table )
	{
		return $this->adapter()->getColumns( $table );
	}

	public function page( $page, $items_per_page )
	{
		return $this->adapter()->page( $page, $items_per_page );
	}

	public function query( $query )
	{
		return $this->adapter()->query( $query );
	}

	public function queryData( $data )
	{
		return $this->adapter()->queryData( $data );
	}

	public function fetch( $return_type = FETCH::RESULT )
	{
		return $this->adapter()->fetch( $return_type );
	}

	public function activeDbQuery()
	{
		return $this->adapter()->activeDbQuery();
	}
}
?>
