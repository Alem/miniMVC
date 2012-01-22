<?php

class Model{


	// __construct 
	//
	// Assigns the model the 'table' property, lowercase pluralized name of the controller class
	// and the table's main/first column the lowercase singular name of the controller class.

	function __construct(){
		$main_column =  strtolower(get_class($this));
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
	// Simplifies database queries by provide a single method that recieves the query, 
	// and the number of rows to return (** the $row param actually defines what the query_array's first index key is.)
	// If the query is a select statement then it returns the results as an array.
	//
	// $query - Regular database query statement.
	// $row - **

	public function query( $query, $row = 1 ){
		$this -> db_connect();
		$result = mysql_query($query) or die("Query failed : $query. <br/><br/>Reason: ".mysql_error());
		if ( preg_match('/select/', $query) ){
			while( $query = mysql_fetch_assoc($result) ) {
				$query_array[$row] = $query;
				$row++;
			}
			$this -> db_disconnect();
			if ( isset($query_array)){
				return $query_array;
			}
		}
		$this -> db_disconnect();
	}

	// select - Performs a database select query
	//
	// Recieves the value to select, the column it belongs to, the order to display them and the ***
	// and returns the results as an array. The value defaults to * which then directs to a query that 
	// excludes the column, table is by default the model's table property, and orderby defaults to null.
	//
	// $value - Value to select for
	// $column - The column the value belongs to
	// $orderby - The type of ordering, column and sort. (ex. date, DESC)
	// $row - ***

	function select( $value = '*', $column = 'id', $orderby = array( 'col' => null, 'sort' => null ), $row = 1 ){
		$table = $this -> table;
		if ( isset($orderby) ){
			$orderby = 'order by ' . $orderby['col'] . ' ' . $orderby['sort'];
		}else{
			$orderby = '';	
		}
		if ($value == '*'){
			return $result = $this -> query("select * from $table $orderby;");
		}else{
			return $result = $this -> query("select $column from $table where $column='$value' $orderby);");
		}
	}

	// insert - uses query to insert into table
	//
	// $value - the value to be inserted. Can accept multiple values to be inserted into multiple columns by passing it 
	// a string of comma seperated $value and $column. Can also accept an array which takes the form 
	// column(s) => value(s) wher eeach column-value pair is inserted into its own row.
	// $column - the  table column to be inserted into. Defaults to model -> column, the lower-case name of controller.

	function insert($value, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : $this -> column;
	
		if ( is_array($value) ){
			foreach ($value as $array_column => $array_value){
				$this -> insert($array_value,$array_column);	
			}
		}elseif( preg_match( '/,/', $value ) && preg_match( '/,/', $column) ){
			$values = $value;
			$columns = $column;
			$this -> query ("insert into $table ($columns) values('$values');");
		}else{
			$this -> query ("insert into $table ($column) values('$value');");
		}
	}

	// form - manages insertion of POST data into single row
	//
	// If POST contains multiple keys then insert as string of multiple values 1','2','3
	// and multiple columns defined as string. Could use POST keys but those can be tampered.
	// Otherwise insert directly. 
	//
	// $form_fields - Either a single field (ex. username) or multiple comma-seperated 
	// 		fields in same order as HTML form (ex. username,password,type). 
	//
	// CRITICAL ASSUMPTIONS - html field names (POST key names) = column names, 
	//			html fields order (POST key order) = $form_fields order.
	// NOTE - Implement data sanitization. 

	function form($form_fields){
		$this -> sanitize($_POST);
		if( count($_POST) > 1 ){
			$multi = implode("','", $_POST);
			$post_order = implode(",", array_keys($_POST) );
			if ( $post_order == $form_fields)
				$this -> insert($multi, $form_fields);
			else
				echo "Specified form fields do not match POST data";
		}elseif(!preg_match ( '/,/', $form_fields) ){
			$single = current( $_POST );
			$this -> insert($single, $form_fields);
		}else{
			echo "Could only find one submitted paramter, specified multiple";
		}
	}

	function update($old, $new, $column_old = null, $column_new = null ){
		$table = $this -> table;
		$column_old = ( isset($column_old) ) ?  $column_old :  'id';
		$column_new = ( isset($column_new) ) ?  $column_new :	$column_old;
		$this -> query("update $table set $column_new = '$new' where $column_old = '$old';");
	}

	function remove($item, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : 'id';
		$this -> query("delete from $table where $column='$item';");
	}


	// sanitize - Sanitizes any user data.
	//
	// Borrowed heavily from some blog, source later.

	function sanitize($data, $html = false ){
		if (empty($data)){
			return $data;
		} elseif (is_array($data)) {
			foreach($data as $key => $value)
				$this -> sanitize( $data[$key], $html);
		} else {
			$data = stripslashes($data);
			if (is_array($html)) 
				$data = strip_tags($data, implode('', $html));
			elseif (preg_match('|<([a-z]+)>|i', $html)) 
				$data = strip_tags($data, $html);
			elseif ($html !== true) 
				$data = strip_tags($data);
			$data = trim($data);
		}
		return $data;
	}

}

?>
