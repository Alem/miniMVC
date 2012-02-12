<?php

class Model{

	public $table;
	public $column;
	public $db;
	public $query;
	public $query_data = array();


	// __construct 
	//
	// Assigns the model the 'table' property, lowercase pluralized name of the controller class
	// and the table's main column the lowercase singular name of the controller class.

	function __construct(){
		$column =  strtolower( get_class($this) );
		$table = $column . 's';
		$this -> table = $table;
		$this -> column = $column;
	}


	// set - Assigns property to model
	//
	// $property - The name of the property to be assigned OR 
	// 		an array containing multiple property/value pairs
	// $value - The value of the property

	function set($property, $value = null){
		if ( is_array( $property) ){
			foreach($property as $single_property => $single_value)
				$this -> set( $single_property, $single_value);
		}else
			$this -> $property = $value;
	}


	// Get - Returns the named property of the model
	//
	// $property - The name of the property to be returned OR 
	// 		array containing multiple properties who's values are to be returned

	function get($property){
		if ( is_array( $property) ){
			foreach($property as $single_property)
				$get_array[] = $this -> get( $single_property);
			return $get_array;
		}
		return $this -> $property;
	}


	//
	// QUERY BUILDING/EXECUTING METHODS BELOW
	//


	// db() - Accesses database connection
	// 
	// Starts connection if model::db not set, otherwise returns model::db

	function db(){
		if ( !isset( $this -> db ) )
			$this->db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE,DB_USERNAME,DB_PASSWORD);
		return $this -> db;
	}


	// query() - Concatonates query fragements to build a full query
	//
	// $fragment - A partial query

	function query($fragment){
		if($fragment)
			$this -> query .= $fragment;
	}


	// query_data() - Sets the array of parameterized data to be passed to prepared statement
	//
	// $data - Data to be passed

	function query_data($data){
		if(is_array($data)){
			$data = array_values($data);
			$this -> query_data = array_merge( $this -> query_data,$data);
		}else
			$this -> query_data[] = $data;
	}


	// clean - Cleans any user data.
	//
	// Cleaning function, does NOT secure data, simply escapes spechars and strips html.
	//
	// &$data - Data.

	function clean( &$data ){
		if (is_array($data)) {
			foreach($data as &$value){
				$this -> clean( $value );
			}
		} else {
			$data = strip_tags($data);
			$data = addslashes($data);
			$data = trim($data);
		}
		return $data;
	}

	// whitelist () - in the works...

	function whitelist( $value, $list ){
		if ( is_array ( $value ) ) {
			foreach ($value as $single_value)
				$this -> whitelist($single_value,$list);
		}
		if ( in_array ( $value, $list) )
			return true;
		else {
			return false;
			break;
		}
	}


	// arrayToQuery - Generates a partial query construct from arrays
	//
	// Generates a query for multiple columns
	// and returns parameters that paired non-empty columns.
	//
	// Only used by where() and update(), needs to be improved for others.
	//
	// $columns - array of columns
	// $parameters - array of paramters
	// $seperator - the string that seperates the 'column = ?' statements

	function arrayToQuery( $columns, $parameters, $seperator){
		$parameters = array_values($parameters);
		for( $i = 0, $j = count($parameters), $construct = null; $i < $j ; $i++ ){
			if( !empty($columns[$i]) ) {
				if ($i > 0)
					$construct .= $seperator;
				$construct .= $columns[$i] . " = ? ";
				$paired_parameters[] = $parameters[$i];
			}
		}
		if (isset($construct) ) {
			return array(
				'construct' => $construct,
				'paired_parameters' => $paired_parameters
			);
		} else
			return null;
	}


	// select - Performs a database select query
	//
	// Recieves the column to select
	//
	// $column - Column of interest,  defaults to $this -> column 
	// $table - Table of interest, defaults to $this -> table 
	// $distinct - if set, will include the 'distinct' option in query

	function select( $column=null, $table = null, $distinct = false){
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;
		$column = (isset($column)) ? $this -> clean($column) : '*';
		$distinct = ( $distinct ) ? 'distinct' : null ;
		if(is_array($column))
			$column =  implode($column, ',');
		$this -> query ("select $distinct $column from $table");
		return $this;
	}



	// insert - uses query to insert into table
	//
	// Takes a value and its column and performs insert. 
	// Can accept multiple values to be inserted into multiple columns by passing it 
	//
	// $value - the value to be inserted.  
	// $column - the  table column to be inserted into. Defaults to model -> column, the lower-case name of controller.
	//
	// Note: If using to insert POST data, ensure the order of $_POST array keys (order in HTML form) 
	// match $column array order

	function insert($value, $column = null, $table =  null){
		$table = ( isset ($table) ) ? $this -> clean($table) : $this -> table;
		$this -> clean($value);
		$column = ( isset($column) ) ?  $this -> clean($column) : $this -> column;
		if ( is_array($value) ){
			for ($i = 1, $j = count($value), $values = null; $i <= $j ; $i ++){
				if ($i >  1)		
					$values .= ", ? ";
			}
			$columns = implode(',', $column );
		}
		$this -> query ("insert into $table ($columns) values( ? $values )");
		$this -> query_data($value);
		return $this;
	}


	// update - uses query to update table
	//
	// $new - New value to replace an existing value
	// $new_column - The columnn of for the new value

	function update($new, $new_column = null, $table = null ){
		$this -> clean($new);
		$this -> clean($new_column);
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;

		if ( is_array($new) && ($new = array_values($new) ) && (!empty($new[0]) )){
			$generated_query = $this -> arrayToQuery( $new_column, $new, ' , ');
			if ($generated_query){
				$values = $generated_query['construct'];
				$valid_news = $generated_query['paired_parameters'];
			}
		}else{
			$values = $new;
			$valid_news = $new;
		}
		$this -> query ("update $table set $values");
		$this -> query_data( $valid_news );
		return $this;
	}


	// remove - uses query to remove from table 
	//
	// $table - the table to be removed from. Defaults to model -> table

	function remove($table = null ){
		$table = (isset($table)) ? $this -> clean ($table) : $this -> table;
		$column = ( isset($column) ) ?  $this -> clean($column) : 'id';
		$this -> clean($value);
		$this -> query ("delete from $table");
		return $this;
	}


	// where 
	//
	// Recieves the value of a known/reference column, the name of the known/reference column.
	//
	// $ref - Reference value 
	// $ref_column - The column the value belongs to

	function where($ref, $ref_column = null ){
		if ( isset( $ref ) ) 
			$this -> clean($ref);
		$ref_column = ( isset($ref_column) ) ? $this -> clean($ref_column) : 'id';
		$where = null;
		if ( isset($ref) && !is_array($ref) ) {
			$where = $this -> clean($ref_column) . " = ? ";
		} elseif ( is_array($ref) ){
			$generated_query = $this -> arrayToQuery( $ref_column, $ref, ' and ');
			if ($generated_query){
				$where = $generated_query['construct'];
				$ref = $generated_query['paired_parameters'];
			}
		} 
		if ($where){
			$this -> query ( ' where ' . $where );
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

	function order($orderby, $sort){
		$order = ( ($orderby) && ($sort) ) ? 
			' order by ' . $this -> clean($orderby) . ' ' . $this -> clean($sort) : null;
		$this -> query .= $order; 
		return $this;
	}


	// limit 
	//
	// Recieves the limit of rows to return
	//
	// $limit - Number of rows to return

	function limit($limit, $offset = null){
		$limit 	= ( isset($limit) ) ? ' limit ' . $this -> clean($limit) : $this -> clean($limit);
		if ( isset( $offset) )
			$limit .= ',' . $offset;
		$this -> query .= $limit; 
		return $this;
	}


	// run() - Executes query 
	//	
	// Recieves query generated by query() and query_data generated by query_data()
	// and prepares then executes the parameterized query. 
	// On completion clears the stored query & data.

	public function run(){
		$db_link = $this -> db();
		Logger::instantiate() -> record['PDO_Query'][] = $this -> query;
		Logger::instantiate() -> record['PDO_Data'][] = print_r($this -> query_data,true);
		$statement = $db_link -> prepare($this -> query);
		$statement -> execute($this -> query_data);
		Logger::instantiate() -> record['PDO_Errors'][] = print_r($statement -> errorInfo(), true);
		if ( preg_match('/select/', $this -> query) )
			return  $statement -> fetchall(PDO::FETCH_ASSOC);
		elseif( preg_match('/insert/', $this -> query) )
			$this -> last_insert_id = $db_link -> lastInsertId();
		$this -> query = null;
		$this -> query_data = array();
	}


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
		$first_row = ($page != 1) ? (($page - 1) * $limit) : ($page - 1);
		$this -> limit($first_row,$items_per_page);
		$query_result = $this -> run();
		return array(
			'total' => $total_result,
			'pages' => ceil($total_result/$items_per_page), 
			'paged' => $query_result 
		);
	}

}

?>
