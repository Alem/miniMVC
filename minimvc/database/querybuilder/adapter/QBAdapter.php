<?php
/**
 * QBAdapter class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.database.querybuilder.adapter
 */
abstract class QBAdapter
{

	/**
	 * Stores the DbQuery instance(s)
	 * @var array
	 */
	public $dbquery = array();


	/**
	 * Holds the names of the table columns. 
	 * Used by QueryBuilder::getColumns()
	 * @var array
	 */
	public $table_columns = array();


	/**
	 * Holds the whitelists for database tables
	 * @var array
	 */
	public $custom_whitelist = array();


	/**
	 * storeDbQuery() - Stores instance of DbQuery for building
	 *
	 * @param DbQuery $dbquery 	Instance of DbQuery
	 * @param string  $name 	The name to store it as
	 */
	public function storeDbQuery( DbQuery $dbquery, $name = 'default' )
	{
		$this->dbquery[$name] = $dbquery;
		$this->resetCounter( $name );
		return $this;
	}

	/**
	 * setCurrentDbQuery() - Set the current DbQuery instance to use
	 *
	 * @param string  $name 	The name of the stored DbQuery
	 */
	public function setCurrentDbQuery( $name )
	{
		$this->active_dbquery = $name;
		return $this;
	}


	/**
	 * useDbQuery() - Build a stored Query
	 *
	 * @param string  $name 	The name of the stored DbQuery
	 */
	public function useDbQuery( $name = 'default', DbQuery $dbquery = null )
	{
		$this->storeDbQuery( $dbquery, $name );
		$this->setCurrentDbQuery( $name );
		return $this;
	}

	/**
	 * CurrentDbQuery() - Returns current DbQuery instance
	 */
	public function activeDbQuery()
	{
		return $this->dbquery[$this->active_dbquery];
	}
	
	/**
	 * resetCounter() - Initialize count-keeper for query
	 *
	 * Tracks the query fragments constructed for
	 * each registered DbQuery
	 *
	 * @param string  $name 	The name to store it as
	 */
	public function resetCounter( $name = null )
	{
		if( $name === null )
			$name = $this->active_dbquery;

		$this->counter[$name] = array(
			'insert' => 0,
			'update' => 0,
			'remove' => 0,
			'select' => 0,
			'from' 	 => 0,
			'joining' => 0,
			'where'  => 0,
			'order'  => 0,
			'limit'  => 0,
			'show' 	 => 0,
			'whitelist_fail'  => 0
		);
	}


	/**
	 * clear - Clears query fragment and resets counters
	 *
	 * @return QueryBuilder 	The current QueryBuilder object.
	 */
	public function clear()
	{
		$this->activeDbquery()->clearQuery();
		$this->resetCounter();
		return $this;
	}

	/**
	 * save - Saves query fragment and counters
	 *
	 * @return QueryBuilder 	The current QueryBuilder object.
	 */
	public function save()
	{
		$this->activeDbquery()->saveQuery();
		$this->saved_counter[$this->active_dbquery] = $this->counter[$this->active_dbquery];
		return $this;
	}

	/**
	 * restore() - Restores saved query fragment and counters
	 *
	 * @return QueryBuilder 	The current QueryBuilder object.
	 */
	public function restore()
	{
		$this->activeDbquery()->restoreQuery();
		$this->counter[$this->active_dbquery] = $this->saved_counter[$this->active_dbquery];
		return $this;
	}


	/**
	 * fetch() - Wrapper for DbQuery::fetch()
	 *
	 */
	public function fetch( $return_type = FETCH::RESULT )
	{
		$this->resetCounter();
		return $this->activeDbquery()->fetch( $return_type );
	}

	/**
	 * query() - Construct query directly.  Wrapper for DbQuery::query()
	 *
	 * @return QueryBuilder 	The current QueryBuilder object.
	 */
	public function query( $query )
	{
		$this->activeDbquery()->query( $query );
		return $this;
	}


	/**
	 * queryData() - Provide query data. Wrapper for DbQuery::queryData()
	 *
	 * @return QueryBuilder 	The current QueryBuilder object.
	 */
	public function queryData( $data )
	{
		$this->activeDbquery()->queryData( $data );
		return $this;
	}



	/**
	 * page() - Executes query and returns paged result
	 *
	 * Alternative to fetch(), 
	 * returns an pagination-friendly array with the format:
	 * 	pages( # pages),
	 * 	paged( single page result),
	 *	total(total # rows ).
	 *	total_result( un-paged set of rows ).
	 *
	 * @param int   $page           		The page to start on or SQL offset
	 * @param int   $items_per_page 		Number of rows to return which determines the number of results per page
	 * @return array  				The pagination-friendly array.
	 * @uses 	QueryBuilder::save() 		Saves query before running first query
	 * @uses 	QueryBuilder::restore() 	Restores query to for use in following paged query
	 * @uses 	QueryBuilder::limit()		Used to limit the paged query.
	 */
	public function page($page, $items_per_page)
	{
		$this->save();
		$total_result = $this->activeDbquery()->fetch();
		$total = count( $total_result );
		$this->restore();

		$starting_row =( $page != 1 ) ?(($page - 1) * $items_per_page ) : 0;
		$this->limit( $starting_row, $items_per_page );
		$paged_query_result = $this->activeDbquery()->fetch();

		return array(
			'pages' => ceil( $total/$items_per_page ),
			'paged' => $paged_query_result,
			'total' => $total,
			'total_result' => $total_result
		);
	}


	/**
	 * setWhitelist() - Sets the list of acceptable columns in SQL queries
	 *
	 * @param string $table 	The table the whitelist applies to
	 * @param array $whitelist 	The whitelist, a numerically indexed array of accepted values.
	 */
	public function setWhitelist( $table, $whitelist )
	{
		$this->custom_whitelist[$table] = $whitelist;
	}


	/**
	 * whitelist() - Checks if value is a valid column.
	 *
	 * Retrieves list of columns from SQL table.
	 * Parses through table columns to check if supplied column 
	 * name is present in the table.
	 *
	 * If column is not valid, the current query construct is deleted
	 * and any proceeding query constructed is commented out
	 * ensuring no actual query performed.
	 *
	 *
	 * @param mixed  $column 			The column values to check.
	 * @param string $table 			The table whose columns will be checked against
	 * @uses  	 QueryBuilder::saveQuery() 	Saves orignal query before running getColumns
	 * @uses 	 QueryBuilder::getColumns() 	Retrieves tables columns to whitelist $column
	 * @uses 	 Controller::prg() 		Redirects to default controller if invalid column is given
	 */
	public function whitelist( $column, $table = null )
	{

		if( $prefix = $this->getPrefix( $column ) )
		{
			$table = $prefix;
			$this->unprefix( $column );
		}
		elseif( empty( $table ) )
			$table =& $this->table;

		if( isset( $this->custom_whitelist[$table] ) )
		{
			$list = $this->custom_whitelist[$table];
		}
		else
		{
			$this->save()->clear();
			$list = $this->getColumns( $table );
			$this->restore();
		}

		if( is_array( $column ) )
		{
			foreach( $column as $single_column )
				$this->whitelist( $single_column, $table );
		}
		else
		{
			if( in_array( $column, $list ) )
				return true;
			else
			{
				Logger::error('Whitelist',"Column '$column' not found in table '$table'");
				Logger::error('Whitelist',"Query Construct: {$this->activeDbquery()->query_construct}" );
				$this->activeDbquery()->cancelQuery();
			}
		}
	}

	/**
	 * separator - Generates a partial query construct from arrays
	 *
	 * example output: 'AND column = ? AND column = ?'
	 *
	 * @param  array  &$columns  	The array of columns
	 * @param  string $separator 	The string that joins the column statements
	 * @param  string $relation 	The string that defines the columns relation with the value.
	 * @return string 		Returns the constructed query fragment
	 */
	public function separator( &$columns, $separator, $relation = '=' )
	{

		for( $i = 0, $j = count($columns), $construct = null; $i < $j ; $i++ )
		{
			if( $i > 0 )
				$construct .= $separator;
			$construct .= $columns[$i] . " $relation ? ";
		}

		return $construct;
	}

	/**
	 * setPrefix - Prefixes names.
	 *
	 * Used for prefixing column with table name, table with database name, etc.
	 *
	 * @param string $name         Word to prefix
	 * @param string $prefix_a     Primary Prefix(ex. table name or database name )
	 * @param string $prefix_b     Secondary prefix(ex. '.' or '_' )
	 * @param bool   $check        If set to true, checks if either prefix is already present in column before prefixing
	 * @return array|string        Returns the prefixed column or columns.
	 */
	public function setPrefix( &$name , $prefix_a = null, $prefix_b = '.', $check = true )
	{

		if( !isset($prefix_a) )
			$prefix_a = $this->activeDbquery()->table;

		if( is_array( $name ) )
		{
			foreach( $name as $single_name)
				$prefixed_array[] = $this->setPrefix( $single_name, $prefix_a, $prefix_b, $check );
			if( isset( $prefixed_array ) )
				return $name = $prefixed_array;

		}
		elseif(( strpos( $name, $prefix_a ) === false ) &&( strpos( $name, $prefix_b ) === false ) && $check )
			return $name = $prefix_a . $prefix_b . $name;
		else
			return $name;
	}

	/**
	 * getPrefix - Retrieve prefix name.
	 *
	 * @param  string $name         Word to prefix
	 * @param  string $prefix_b     Secondary prefix(ex. '.' or '_' )
	 * @return string|bool         Returns the prefix name.
	 */
	public function getPrefix( $name, $prefix_b = '.' )
	{
		if( is_array( $name ) )
			return null;
		$position = strpos( $name, $prefix_b );
		if( $position !== false )
			return $prefix_a = substr( $name, 0, $position );
		else
			return null;
	}

	/**
	 * unPrefix - Remove prefix from name.
	 *
	 * @param string $name         Prefixed word.
	 * @param string $prefix_b     Secondary prefix(ex. '.' or '_' )
	 * @return string  	       Returns un-prefixed name.
	 */
	public function unPrefix( &$name , $prefix_b = '.' )
	{
		if( is_array( $name ) )
			return $name;
		$position = strpos( $name, $prefix_b );
		if( $position !== false )
			return $name = substr( $name,( $position + 1 ) , strlen( $name ) );
	}

	/**
	 * alias - Aliases column names
	 *
	 * Uses the form: PREFIX_column
	 * If column is prefixed with table name (table.column), 
	 * calculates the un-prefixed name.
	 *
	 * @param $column The column to alias
	 * @param $prefix The prefix to use.( Typically a table )
	 */
	public function alias( &$column, $prefix )
	{
		if( is_array( $column ) )
		{
			foreach( $column as &$single_column )
				$this->alias( $single_column, $prefix );
		}
		else
		{
			$position = strpos( $column, '.' );
			if( $position !== false )
				return $column = $column . ' AS ' . $prefix . '_' . substr( $column,($position + 1));
			else
				return $column = $column . ' AS ' . $prefix . '_' . $column;
		}
	}

}
?>
