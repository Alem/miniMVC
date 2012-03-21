<?php


class Query extends Database{
	
	public $counter = array( 
		'insert' => 0,
		'update' => 0,
		'remove' => 0,
		'select' => 0,
		'from' => 0,
		'joining' => 0,
		'where' => 0,
		'order' => 0,
		'limit' => 0,
		'show' => 0
	);


	// page() - Executes query and returns paged result
	//
	// Alternative to run(), returns an pagination-friendly array.
	//
	// $limit - Number of rows to return AKA results per page
	// $page - Set when paged results are require_once_onced. Returns a pagination friendly array: 
	//		 pages ( # pages), paged ( single page result), total (total # rows in column)

	function page($page, $items_per_page){

		$this -> clean($page);

		$original_query = $this -> query;
		$total_result = count( $this -> run() );

		$this -> query = $original_query;
		$first_row = ($page != 1) ? ( ($page - 1) * $items_per_page ) : ($page - 1);
		$this -> limit( $first_row, $items_per_page );
		$query_result = $this -> run();

		return array(
			'total' => $total_result,
			'pages' => ceil($total_result/$items_per_page), 
			'paged' => $query_result 
		);
	}

	// whitelist () - Checks if value is a valid column.
	// 
	// Retrieves list of columns from SQL table.
	// Parses through table columns to check if supplied column name 
	// is present in the table.
	//
	// $column - Column values to check
	// $table - The table whose columns will be checked against
	// Model::custom_whitelist - Optionally supply alternate whitelist values

	function whitelist( $column, $table = null ){

		$table = ($table == null) ? $this -> table : $table;

		if ( empty( $this -> table_info[$table] ) && empty ( $this -> custom_whitelist ) ) {
			$this -> saveQuery();
			$this -> clearQuery();
			$this -> table_info[$table] = $this -> show( $table ) -> run();
			$this -> restoreQuery();

			foreach( $this -> table_info[$table] as $table_row => $table_column)
				$this -> table_columns[$table][] = $table_column['Field'];
		}

		if ( empty( $this -> custom_whitelist ) )
			$list =&$this -> table_columns[$table];
		else{
			$list = $this -> custom_whitelist;
			$this -> custom_whitelist = null;
		}

		if ( is_array ( $column ) ) {
			foreach ($column as $single_column)
				$this -> whitelist( $single_column, $table );
		}else{

			if ( in_array ( $column, $list) || ( $column == '*') ){
				return true;
			} else {
				$error = "Column '$column' not found in table '$table'";
				Debugger::instantiate() -> record['whitelist_error'][] = $error;
				controller::prg( null, null, DEFAULT_CONTROLLER );
			}
		}
	}


	// seperator - Generates a partial query construct from arrays
	//
	// Generates a query for multiple columns
	// by appending the ' = ? ' for parameterization
	// and seperating each "column = ? " by a defined seperator
	// and returns constructed query
	//
	// Only used by where() " column=? AND column=?  "  and update(), needs to be improved for others.
	//
	// &$columns - array of columns
	// $seperator - the string that seperates the 'column = ?' statements

	function seperator( &$columns, $seperator ){
		
		for( $i = 0, $j = count($columns), $construct = null; $i < $j ; $i++ ){
			if ( $i > 0 )
				$construct .= $seperator;
			$construct .= $columns[$i] . " = ? ";
		}

		return $construct;
	}


	// prefix - Prefixes names
	//
	// $name - Word to prefix
	// $prefix_one - Primary Prefix
	// $prefix_two - Secondary prefix


	function prefix( $name , $prefix_one = null, $prefix_two = '.', $check = true ){
		$prefix_one = ( isset( $prefix_one ) ) ? $prefix_one : $this -> table;

		if( is_array( $name ) ){
			foreach( $name as $single_name)
				$prefixed_name_array[] = $this -> prefix ( $single_name, $prefix_one, $prefix_two );
			return $prefixed_name_array;
		}

		if ( ( strpos( $name, $prefix_one ) === false ) && ( strpos( $name, $prefix_two ) === false ) && $check ){
			$prefixed_name = $prefix_one . $prefix_two . $name;	
			return $prefixed_name;
		}else
			return $name;
	}


	// select - Performs a database select query
	//
	// Recieves the column to select
	//
	// $column - Column of interest,  defaults to $this -> column 
	// $table - Table of interest, defaults to $this -> table 
	// $distinct - if set, will include the 'distinct' option in query

	function select( $column=null, $table = null, $distinct = false){
		$this -> counter['select']++;
		$table = ( isset($table) ) ? $this -> clean ($table) : $this -> table;
		$column = (isset($column)) ? $this -> clean($column) : '*';
		$distinct = ( $distinct )  ? 'DISTINCT' : null ;

		$this -> whitelist($column, $table);
		if ( $column != '*' )
			$column = $this -> prefix( $column, $table );

		if(is_array($column))
			$column = implode( $column, ',');

		if ( $this -> counter['select'] === 1 )
			$this -> query ("SELECT $distinct $column ");
		else
			$this -> query (", $column ");
		return $this;
	}


	function from ( $table = null){
		$this -> counter['from']++;
		$table = ( isset($table) ) ? $this -> clean ($table) : $this -> table;
		$this -> query (" FROM $table ");
		return $this;
	}


	// insert - uses query to insert into table
	//
	// Takes a value and its column and performs insert. 
	// Can accept multiple values to be inserted into multiple columns by passing it 
	//
	// $value - the value to be inserted.  
	// $column - the  table column to be inserted into. Defaults to model -> column, 
	// 		the lower-case name of controller.
	//
	// Note: If using to insert POST data, ensure the order of $_POST array keys (order in HTML form) 
	// match $column array order

	function insert( $value, $column = null, $table =  null){
		$this -> counter['insert']++;

		$table = ( isset ($table) ) ? $this -> clean($table) : $this -> table;
		$this -> whitelist($column, $table);

		$value_string = ' ? ';
		if ( is_array($value) ){
			for ( $i = 1, $j = count($value); $i <= $j ; $i++ ){
				if ($i >  1)		
					$value_string .= ", ? ";
			}
			$column_string = implode(',', $column );
		}else
			$column_string =& $column;

		$this -> query ("INSERT INTO $table ($column_string) VALUES( $value_string )");
		$this -> query_data($value);
		return $this;
	}


	// update - uses query to update table
	//
	// $new - New value to replace an existing value
	// $new_column - The columnn of for the new value

	function update($new, $new_column = null, $table = null ){
		$this -> counter['update']++;
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;
		$this -> whitelist($new_column, $table);

		if ( is_array($new) && ($new = array_values($new) ) && (!empty($new[0]) ))
			$values = $this -> seperator( $new_column, ' , ');
		else
			$values = $new_column . ' = ? ';
		$this -> query ("UPDATE $table SET $values");
		$this -> query_data( $new );
		return $this;
	}


	// remove - uses query to remove from table 
	//
	// $table - the table to be removed from. Defaults to model -> table

	function remove($table = null ){
		$this -> counter['remove']++;
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;
		$column = ( isset($column) ) ?  $this -> clean($column) : 'id';
		$this -> query ("DELETE FROM $table");
		return $this;
	}


	// where 
	//
	// Recieves the value of a known/reference column, the name of the known/reference column.
	//
	// $ref - Reference value 
	// $ref_column - The column the value belongs to
	// $table - The table to whitelist the columns against

	function where( $ref, $ref_column = null , $table = null ){
		$this -> counter['where']++;
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;
		$ref_column = ( isset($ref_column) ) ? $this -> clean($ref_column) : 'id';

		$this -> whitelist($ref_column, $table );

		$ref_column = $this -> prefix( $ref_column, $table );

		if ( isset($ref) && !is_array($ref) ) {
			$where = $ref_column . " = ? ";
		} elseif ( is_array($ref) ){
			$where = $this -> seperator( $ref_column, ' AND ');
		} 

		if ( isset ($where) ) {
			if ( $this -> counter['where']  > 1 )
				$statement = ' AND ' . $where;
			else 
				$statement = ' WHERE ' .$where;
			$this -> query ( $statement );
			$this -> query_data ($ref);
		}
		return $this;
	}


	// order 
	//
	// Recieves the column to use for ordering, the type of sort.
	//
	// $orderby - The column used for ordering
	// $sort - The type of sort (ex. DESC)
	// $table - The table to whitelist the columns against

	function order($orderby, $sort, $table = null){
		$this -> counter['order']++;
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;
		if ( ($orderby) && ($sort == 'ASC' || $sort == 'DESC' ) ){
			$this -> whitelist($orderby,$table);
			#$this -> whitelist($sort,$table, array('ASC','DESC'));
			$order = " ORDER BY $table.$orderby $sort";
			$this -> query .= $order; 
		}
		return $this;
	}


	// limit 
	//
	// Recieves the limit of rows to return
	//
	// $limit - Number of rows to return

	function limit($limit, $offset = null){
		$this -> counter['limit']++;
		if ( isset( $limit) )
			$limit = " LIMIT $limit";
		if ( isset( $offset) && isset($limit) )
			$limit .= ',' . $offset;
		$this -> query .= $limit; 
		return $this;
	}

	// joining -  Joins two tables
	//
	// Takes the table 'a' and 'b', their respective columns
	// and the type of join.
	//
	// $type - Join type: INNER, LEFT, RIGHT, OUTER
	// $table_b - The table to join to.
	// $columns_a - The columns of table_a to match with columns of table b
	// $columns_b - The columns of table_b
	// $table_a - The main table, defaults to the table registered in $this -> table.

	function joining( $table_b, $columns_a, $columns_b , $type = 'LEFT OUTER', $table_a = null ) {
		$this -> counter['joining']++;
		$table_a = (isset($table_a)) ? $this -> clean ( $table_a ) : $this -> table;
		$this -> whitelist($columns_a, $table_a);
		$this -> whitelist($columns_b, $table_b);
		if  ( is_array ($columns_a) ){
			for ( $i = 0 , $j = count ( $column_a ), $conditions = null; $i <= $j; $i ++ ){
				$conditions .= $this-> prefix( $column_a[$i], $table_a );
				$conditions .= '=' . $this -> prefix ( $column_b[$i], $table_b );
			}
		}else{
			$conditions = $this-> prefix( $columns_a, $table_a );
			$conditions .= '=' . $this -> prefix ( $columns_b, $table_b );
		}
		$statement = " $type JOIN $table_b ON $conditions";
		$this -> query ( $statement );
		return $this;
	}


	// show - Retrieve table information
	//
	// $table - The database table

	function show ( $table ){
			$this -> counter['show']++;
			$this -> query("SHOW COLUMNS FROM $table");
			return $this;
	}

}


?>
