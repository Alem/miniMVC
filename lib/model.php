<?php

class Model{

	public $table;
	public $column;

	// __construct 
	//
	// Assigns the model the 'table' property, lowercase pluralized name of the controller class
	// and the table's main column the lowercase singular name of the controller class.

	function __construct(){
		$main_column =  strtolower( get_class($this) );
		$table = $main_column . 's';
		$this -> table = $table;
		$this -> column = $main_column;
	}
	

	// db_connect() - Establishes database connection

	public function db_connect(){
		mysql_select_db( DB_DATABASE, mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD ));
	}


	// db_disconnect() - Closes database connection

	public function db_disconnect(){
		mysql_close( mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD	) );
	}


	// query() - Basic database query wrapper
	//	
	// Simplifies database queries by provide a single method that recieves the query.
	// If the query is a select statement then it returns the results as an array.
	//
	// $query - Regular database query statement.

	public function query( $query ){
		$this -> db_connect();
		$result = mysql_query( $query ) or die("Query failed : $query. <br/><br/>Reason: ".mysql_error());
		$this -> db_disconnect();
		if ( preg_match('/select/', $query) ){
			while( $query = mysql_fetch_assoc($result) ) 
				$query_array[] = $query;
			if ( isset($query_array))
				return $query_array;
		}
	}


	// select - Performs a database select query
	//
	// Recieves the value to select, the column it belongs to, the order to display them and the ***
	// and returns the results as an array. The value defaults to * which then directs to a query that 
	// excludes the column, table is by default the model's table property, orderby defaults to null as does limit.
	//
	// $column - Column of interest
	// $ref - Reference value 
	// $ref_column - The column the value belongs to
	// $orderby - The type of ordering, column and sort. (ex. date, DESC)
	// $limit - Number of rows to return

	function select( $column = null, $ref = null, $ref_column = null, $order = null, $limit = null ){
		$table 	= $this -> table;
		$column = ( isset($column) ) ?  $column : '*';
		$ref_column = ( isset($ref_column) ) ?  $ref_column : 'id';
		$order 	= ( ($order) ) ? 'order by ' . $order['0'] . ' ' . $order['1'] : $order;
		$limit 	= ( isset($limit) ) ? "limit $limit" : $limit;	
		if ( !isset( $ref ) ) 
			return $result = $this -> query("select $column from $table $order $limit;");
		else{
			$this -> sanitize($ref);
			return $result = $this -> query("select $column from $table where $ref_column='$ref' $order $limit;");
		}
	}


	// insert - uses query to insert into table
	//
	// Note: If using two insert a forms POST data, ensure the order of $_POST array keys (order in HTML form)
	//  match the order of the specified columns.
	//
	// $value - the value to be inserted. Can accept multiple values to be inserted into multiple columns by passing it 
	// an $value array and $column array. 
	// $column - the  table column to be inserted into. Defaults to model -> column, the lower-case name of controller.

	function insert($value, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : $this -> column;
		if ( is_array($value) ){
			if ( ( count($value) > 1) && (count($column) > 1) ){
				$columns = implode(",", $column);
				$this -> sanitize($value);
				$values = implode("','", $value);
				$this -> query ("insert into $table ($columns) values('$values');");
			}else{
				$value = current($value);
				$column = current($column);
				$this -> sanitize($value);
				$this -> query ("insert into $table ($column) values('$value');");
			}
		}else{
			$this -> sanitize($value);
			$this -> query ("insert into $table ($column) values('$value');");
		}
	}


	// update - uses query to update table
	//
	// $ref - Reference value 
	// $new - New value to replace an existing value
	// $ref_column - The columnn of the reference value
	// $new_column - The columnn of for the new value

	function update($ref, $new, $ref_column = null, $new_column = null ){
		$table = $this -> table;
		$ref_column = ( isset($ref_column) ) ?  $ref_column :  'id';
		$new_column = ( isset($new_column) ) ?  $new_column :	$column_old;
		$this -> sanitize($ref);
		$this -> query("update $table set $new_column = '$new' where $ref_column = '$ref';");
	}


	// remove - uses query to remove from table 
	//
	// $value - the value to be removed.
	// $column - the table column to be removed from. Defaults to model -> column, the lower-case name of controller.

	function remove($value, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : 'id';
		$this -> sanitize($value);
		$this -> query("delete from $table where $column='$value';");
	}


	// sanitize - Sanitizes any user data.
	//
	//
	// &$data - Data.

	function sanitize( &$data ){
		if (is_array($data)) {
			foreach($data as $key => $value){
				$this -> sanitize( $data[$key] );
			}
		} else {
			$data = strip_tags($data);
			$this -> db_connect();
			$data = mysql_real_escape_string($data);
			$this -> db_disconnect();
			$data = trim($data);
		}
		return $data;
	}

}

?>
