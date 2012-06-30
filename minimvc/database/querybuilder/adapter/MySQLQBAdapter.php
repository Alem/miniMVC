<?php
/**
 * MySQLQBAdapter class file
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * @fixme If Qb is to support multiple DbQuery objects,
 * the counters must be specific to those objects,
 * otherwise they will be inappropiately shared
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.database
 */
class MySQLQBAdapter extends QBAdapter implements IQueryBuilder
{

	/**
	 * select - Performs a database select query
	 *
	 * Recieves the column to select
	 *
	 * @param mixed  $column 			Column of interest,  defaults to '*'
	 * @param string $table 			Table of interest, defaults to $this->table
	 * @param bool   $autoalias 			If set to true, aliases columns with form: table_column
	 * @param bool   $is_distinct 			If set to true, includes 'distinct' in query
	 * @return 	 QueryBuilder 			The current QueryBuilder object.
	 * @uses 	 QueryBuilder::whitelist() 	Whitelists column to ensure it is a valid table column
	 * @uses 	 QueryBuilder::setPrefix() 	Prefixes table columns
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 */
	public function select( $column = '*', $table = null, $autoalias = false, $is_distinct = false)
	{
		$this->counter[$this->active_dbquery]['select']++;

		if( !isset($table)  )
			$table = $this->activeDbQuery()->table;

		$distinct =( $is_distinct )   ? 'DISTINCT' : null ;

		if( $column !== '*' )
		{
			$this->whitelist($column, $table);
			$this->setPrefix( $column, $table );
			if( $autoalias )
				$this->alias( $column, $table );
		}

		if(is_array($column))
			$column = implode( $column, ',');

		if( $this->counter[$this->active_dbquery]['select'] === 1 )
			$this->activeDbQuery()->query("SELECT $distinct $column ");
		else
			$this->activeDbQuery()->query(", $column ");
		return $this;
	}

	/**
	 * from - The 'from table' fragment of SQL query
	 *
	 * @param string $table
	 * @return QueryBuilder 	The current QueryBuilder object.
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 */
	public function from( $table = null)
	{
		$this->counter[$this->active_dbquery]['from']++;
		if( !isset($table) )
			$table = $this->activeDbQuery()->table;
		$this->activeDbQuery()->query(" FROM $table ");
		return $this;
	}

	/**
	 * insert - uses query to insert into table
	 *
	 * Takes a value and its column and performs insert.
	 * Can accept multiple values to be inserted into multiple columns by passing it
	 * Note: If using to insert POST data, ensure the order of $_POST array keys(order in HTML form) match $column array order
	 *
	 * @param mixed  $value  The value to be inserted.
	 * @param string $column The  table column to be inserted into. Defaults to model->column, the lower-case name of controller.
	 * @param string $table  The table to insert into.
	 * @return QueryBuilder 	The current QueryBuilder object.
	 * @uses 	 QueryBuilder::whitelist() 	Whitelists column to ensure it is a valid table column
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 * @uses 	 QueryBuilder::queryData() 	Holds the partial query's data
	 */
	public function insert( $value, $column = null, $table =  null)
	{
		$this->counter[$this->active_dbquery]['insert']++;

		if( !isset($table) )
			$table = $this->activeDbQuery()->table;

		$this->whitelist($column, $table);

		$value_string = ' ? ';

		if( is_array($value) )
		{
			for( $i = 1, $j = count($value); $i <= $j ; $i++ )
			{
				if($i >  1)
					$value_string .= ", ? ";
			}
		}

		if( is_array($column) )
			$column_string = implode(',', $column );
		else
			$column_string =& $column;

		$this->activeDbQuery()->query("INSERT INTO $table($column_string) VALUES( $value_string )");
		$this->activeDbQuery()->queryData($value);

		return $this;
	}


	/**
	 * update - uses query to update table
	 *
	 * @param mixed $new        New value to replace an existing value
	 * @param mixed $new_column The columnn of for the new value
	 * @param string $table     The table to update.
	 * @return QueryBuilder 	The current QueryBuilder object.
	 * @uses 	 QueryBuilder::whitelist() 	Whitelists column to ensure it is a valid table column
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 * @uses 	 QueryBuilder::queryData() 	Holds the partial query's data
	 */
	public function update($new, $new_column = null, $table = null )
	{
		$this->counter[$this->active_dbquery]['update']++;

		if( !isset($table) )
			$table = $this->activeDbQuery()->table;

		$this->whitelist($new_column, $table);

		if( is_array($new) )
			$values = $this->separator( $new_column, ' , ');
		else
			$values = $new_column . ' = ? ';

		$this->activeDbQuery()->query("UPDATE $table SET $values");
		$this->activeDbQuery()->queryData( $new );

		return $this;
	}

	/**
	 * remove - uses query to remove from table
	 *
	 * @param  string 	$table 			The table to be removed from. Defaults to model->table
	 * @return QueryBuilder 			The current QueryBuilder object.
	 * @uses 	 	QueryBuilder::query() 	Holds partial query
	 */
	public function remove($table = null )
	{
		$this->counter[$this->active_dbquery]['remove']++;

		if( !isset($table) )
			$table = $this->activeDbQuery()->table;

		if( !isset($column) )
			$column = 'id';

		$this->activeDbQuery()->query("DELETE FROM $table");

		return $this;
	}

	/**
	 * where
	 *
	 * Recieves the value of a known/reference column, the name of the known/reference column.
	 *
	 * @param mixed  $ref        			Reference value
	 * @param mixed  $ref_column 			The column the value belongs to
	 * @param string $table      			The table to whitelist the columns against
	 * @param bool 	 $is_like 			True if uses LIKE clause
	 * @uses 	 QueryBuilder::whitelist() 	Whitelists column to ensure it is a valid table column
	 * @uses 	 QueryBuilder::setPrefix() 	Prefixes table columns
	 * @uses 	 QueryBuilder::separator() 	Correctly formats multiple columns
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 * @uses 	 QueryBuilder::queryData() 	Holds the partial query's data
	 */
	public function where( $ref, $ref_column = null, $table = null, $is_like = false )
	{
		$this->counter[$this->active_dbquery]['where']++;

		if( !isset($table) )
			$table = $this->activeDbQuery()->table;

		if( $is_like )
			$relation = 'LIKE';
		else
			$relation = '=';


		$this->whitelist($ref_column, $table );
		$this->setPrefix( $ref_column, $table );

		if( isset($ref) && !is_array($ref) )
		{
			if( is_array( $ref_column ) )
				$where = reset( $ref_column ) . " $relation ?";
			else
				$where = $ref_column . " $relation ? ";
		}
		elseif( is_array($ref) )
		{
			$where = $this->separator( $ref_column, ' AND ', $relation );

			if( $is_like )
			{
				foreach( $ref as &$single_ref )
					$single_ref = '%' . $single_ref .'%';
			}
		}

		if( isset( $where ) )
		{
			if( $this->counter[$this->active_dbquery]['where']  > 1 )
				$statement = ' AND ' . $where;
			else
				$statement = ' WHERE ' .$where;

			$this->activeDbQuery()->query( $statement );
			$this->activeDbQuery()->queryData($ref);
		}

		return $this;
	}


	/**
	 * order - Recieves the column to use for ordering, the type of sort.
	 *
	 * @param string $orderby - The column used for ordering
	 * @param string $sort - The type of sort(ex. DESC)
	 * @param string $table - The table to whitelist the columns against
	 * @uses 	 QueryBuilder::whitelist() 	Whitelists column to ensure it is a valid table column
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 */
	public function order( $orderby, $sort, $table = null)
	{
		$this->counter[$this->active_dbquery]['order']++;

		if( !isset($table) )
			$table = $this->activeDbQuery()->table;

		if( isset( $orderby ) &&( stristr( $sort, 'asc' ) || stristr( $sort, 'desc' ) ) )
		{
			$this->whitelist($orderby,$table);
			$order = " ORDER BY $table.$orderby $sort";
			$this->activeDbQuery()->query( $order );
		}

		return $this;
	}


	/**
	 * limit - Recieves the limit of rows to return
	 *
	 * @param  integer 	$limit  		Number of rows to return
	 * @param  integer 	$offset 		The record to start at.
	 * @return QueryBuilder 			The current QueryBuilder object.
	 * @uses 	 	QueryBuilder::query() 	Holds partial query
	 */
	public function limit($limit, $offset = null)
	{
		$this->counter[$this->active_dbquery]['limit']++;

		if( isset( $limit) )
		{
			$limit = " LIMIT $limit";
			if( isset( $offset)  )
				$limit .= ',' . $offset;
			$this->activeDbQuery()->query( $limit );
		}

		return $this;
	}


	/**
	 * joining -  Joins two tables
	 *
	 * Takes the table 'a' and 'b', their respective columns and the type of join.
	 *
	 * @param string $table_b  			The table to join to.
	 * @param mixed  $columns_a 			The columns of table_a to match with columns of table b
	 * @param mixed  $columns_b 		 	The columns of table_b
	 * @param string $type      			Join type: INNER, LEFT, RIGHT, OUTER
	 * @param string $table_a   			The main table, defaults to $this->table.
	 * @return QueryBuilder 			The current QueryBuilder object.
	 * @uses 	 QueryBuilder::whitelist() 	Whitelists columns to ensure it is a valid table column
	 * @uses 	 QueryBuilder::setPrefix() 	Prefixes table columns
	 * @uses 	 QueryBuilder::separator() 	Correctly formats multiple columns
	 * @uses 	 QueryBuilder::query() 		Holds partial query
	 */
	public function joining( $table_b, $columns_a, $columns_b , $type = 'LEFT OUTER', $table_a = null ) {

		$this->counter[$this->active_dbquery]['joining']++;

		if( !isset($table_a) )
			$table_a = $this->activeDbQuery()->table;

		$this->whitelist( $columns_a, $table_a );
		$this->whitelist( $columns_b, $table_b );
		$this->setPrefix( $columns_a, $table_a );
		$this->setPrefix( $columns_b, $table_b );

		if( is_array($columns_a) )
		{
			for( $i = 0 , $j = count( $column_a ), $conditions = null; $i <= $j; $i ++ )
				$conditions .= $column_a[$i] . '=' . $column_b[$i];
		}
		else
			$conditions = $columns_a . '=' . $columns_b;

		$statement = " $type JOIN $table_b ON $conditions";
		$this->activeDbQuery()->query( $statement );

		return $this;
	}


	/**
	 * show - Retrieve table information
	 *
	 * @param string 	$table 			The database table
	 * @return 		QueryBuilder 		The current QueryBuilder object.
	 * @uses 		QueryBuilder::query() 	Holds partial query
	 */
	public function show( $table )
	{
		$this->counter[$this->active_dbquery]['show']++;
		$this->activeDbQuery()->query("SHOW COLUMNS FROM $table");

		return $this;
	}


	/**
	 * getColumns - Retrieve table columns
	 *
	 * @param string $table The database table
	 * @return array        The columns of the table.
	 * @uses 		QueryBuilder::show() 	Retrieves table information
	 */
	public function getColumns( $table )
	{
		if( !isset( $this->table_columns[$table] ) )
		{
			$this->table_columns[$table] = null;
			$this->table_info[$table] = $this->show( $table )->activeDbQuery()->fetch();
			foreach( $this->table_info[$table] as $table_row => $table_column)
				$this->table_columns[$table][] = $table_column['Field'];
		}
		return $this->table_columns[$table];
	}

}
?>
