<?php

class Model{

	public $table;
	public $column;
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

	// query() - Basic database query wrapper
	//	
	// Simplifies database queries by provide a single method that recieves the query.
	// If the query is a select statement then it returns the results as an array.
	//
	// $query - Regular database query statement.

	public function run(){
		$db_link = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE,DB_USERNAME,DB_PASSWORD );
		Logger::instantiate() -> message .= '<pre><h4>PDO</h4>' . $this->query . ' <br/>Parameters: '. print_r($this->query_data,true).'<br/>';
		$statement = $db_link -> prepare($this -> query);
		$statement -> execute($this -> query_data);
		Logger::instantiate() -> message .= '<p><b>Errors</b><br/>' . print_r($statement -> errorInfo(),true) . '</pre></p>';
		if ( preg_match('/select/', $this -> query) )
			return  $statement -> fetchall(PDO::FETCH_ASSOC);
		elseif( preg_match('/insert/', $this -> query) )
			$this -> last_insert_id = $db_link -> lastInsertId();
		$this -> query = null;
		$this -> query_data = array();
		$db_link = null;
	}


	// select - Performs a database select query
	//
	// Recieves the column to select,
	// a pagination-friendly array.
	//
	// $column - Column of interest

	function select( $column=null){
		$column = (isset($column)) ? $this -> clean($column) : '*';
		if(is_array($column))
			$column =  implode($column, ',');
		$this -> query = ("select $column from {$this -> table}");
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
	// Note: If using to insert POST data, ensure the order of $_POST array keys (order in HTML form) match $column array order

	function insert($value, $column = null ){
		$table = $this -> table;
		$this -> clean($value);
		$column = ( isset($column) ) ?  $this -> clean($column) : $this -> column;
		if ( is_array($value) ){
			$columns = implode(",", $column );
			for ($i = 1; $i <= count($value); $i ++){
				if ($i == 1)		
					$values = " ? ";
				else
					$values .= ", ? ";
			}
			$this -> query =  ("insert into $table ($columns) values($values)");
			$this -> query_data = array_merge($this->query_data, array_values($value));
		}else{
			$this -> query =  ("insert into $table ($column) values( ? )");
			$this -> query_data[]= $value;
		}
		return $this;
	}


	// update - uses query to update table
	//
	// $ref - Reference value 
	// $new - New value to replace an existing value
	// $ref_column - The columnn of the reference value
	// $new_column - The columnn of for the new value

	function update($ref, $new, $ref_column = null, $new_column = null ){
		$this -> clean($ref);
		$this -> clean($new);
		$ref_column = ( isset($ref_column) ) ?  $this -> clean($ref_column) :  'id';
		$new_column = ( isset($new_column) ) ?  $this -> clean($new_column) : $this -> clean($column_old);
		$this -> query = ("update {$this->table} set $new_column=? where $ref_column = ?");
		$this -> query_data[] = $new;
		$this -> query_data[] = $ref;
		return $this;
	}


	// remove - uses query to remove from table 
	//
	// $value - the value to be removed.
	// $column - the table column to be removed from. Defaults to model -> column, the lower-case name of controller.

	function remove($value, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $this -> clean($column) : 'id';
		$this -> clean($value);
		$this -> query = ("delete from $table where $column = ?");
		$this -> query_data[] = $value;
		return $this;
	}

	// where 
	//
	// Recieves the value of a known/reference column, the name of the known/reference column.
	//
	// $ref - Reference value 
	// $ref_column - The column the value belongs to

	function where($ref, $ref_column ){
		if ( isset( $ref ) ) 
			$this -> clean($ref);
		$ref_column = ( isset($ref_column) ) ? $this -> clean($ref_column) : 'id';
		if ( isset($ref) && !is_array($ref) ) {
			$where = ' where ' . $this -> clean($ref_column) . "= ?";
			$this -> query_data[] = $ref;
		} elseif ( is_array($ref) && ($ref = array_values($ref) ) && (!empty($ref[0]) )){
			for( $i = 0; $i < count($ref); $i++ ){
				if ( !empty($ref_column[$i])){
					if ($i > 0)
						$where .= " and ";
					else
						$where = " where ";
					$where .= $ref_column[$i] . "=?";
					$valid_refs[] = $ref[$i];
				}
			}
			$this -> query_data = array_merge( $this -> query_data, $valid_refs );
		} else 
			$where = null;
		$this -> query .= $where; 
		return $this;
	}

	// order 
	//
	// Recieves the column to use for ordering, the type of sort.
	//
	// $orderby - The column used for ordering
	// $sort - The type of sort (ex. DESC)
	function order($orderby, $sort){
		$order = ( ($orderby) && ($sort) ) ?  ' order by ' . $this -> clean($orderby) . ' ' . $this -> clean($sort) : null;
		$this -> query .= $order; 
		return $this;
	}

	// limit 
	//
	// Recieves the limit of rows to return
	//
	// $limit - Number of rows to return
	function limit($limit){
		$limit 	= ( isset($limit) ) ? ' limit ' . $this -> clean($limit) : $this -> clean($limit);
		$this -> query .= $limit; 
		return $this;
	}

	// page 
	//
	// Alternative to run(), returns an pagination-friendly array.
	//
	// $limit - Number of rows to return AKA results per page
	// $page - Set when paged results are required. Returns a pagination friendly array: 
	//  pages ( the total # of pages), paged ( the page-limited result), total (the total # of rows in the column)
	function page($page, $limit){
		$this -> clean($page);
		$original_query = $this -> query;
		$total_result = count( $this -> run() );
		$this -> query = $original_query;
		$first_row = ($page != 1) ? (($page - 1) * $limit) : ($page - 1);
		$this -> limit($first_row .','.$limit);
		$query_result = $this -> run();
		return array(
			'total' => $total_result,
			'pages' => ceil($total_result/$limit), 
			'paged' => $query_result 
		);
	}

	// set - Assigns property to model
	//
	// $property - The name of the property to be assigned OR an array containing multiple property/value pairs
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
	// $property - The name of the property to be returned OR array containing multiple properties who's values are to be returned

	function get($property){
		if ( is_array( $property) ){
			foreach($property as $single_property)
				$get_array[] = $this -> get( $single_property);
			return $get_array;
		}
		return $this -> $property;
	}

	// clean - Cleans any user data.
	//
	// Simple function, does not secure data, simply escapes MySQL spechars and strips html.
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
			# TOO SLOW
			#mysql_select_db( DB_DATABASE, mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD ));
			#$data = mysql_real_escape_string($data);
			#mysql_close( mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD	) );
			$data = trim($data);
		}
		return $data;
	}

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
	
}

?>
