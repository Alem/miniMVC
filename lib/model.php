<?php

class Model{

	function __construct(){
		$column =  strtolower(get_class($this));
		$table = $column . 's';
		$this -> table = $table;
		$this -> column = $column;
	}

	public function db_connect(){
		mysql_select_db( DB_DATABASE, mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD ));
	}

	public function db_disconnect(){
		mysql_close( mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD	) );
	}

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

	function select( $item = '*', $column = 'id', $orderby = array( 'col' => null, 'sort' => null ), $row = 1 ){
		$table = $this -> table;
		if ( isset($orderby) ){
			$orderby = 'order by ' . $orderby['col'] . ' ' . $orderby['sort'];
		}else{
			$orderby = '';	
		}
		if ($item == '*'){
			return $result = $this -> query("select * from $table $orderby;");
		}else{
			return $result = $this -> query("select $column from $table where $column='$item' $orderby);");
		}
	}

	// insert - uses query to insert into table
	//
	// $item - the item to be inserted. Can accept an array which takes the form column(s) => item(s),
	//	each column,item pair inserted into its own row. (if multiple columns/rows must format for proper SQL)
	// $column - the  table column to be inserted into. 
	//	Defaults to model -> column, the lower-case name of controller.

	function insert($item, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : $this -> column;
	
		if ( is_array($item) ){
			foreach ($item as $column => $value){
				$this -> insert($value,$column);	
			}
		}else{
			$this -> query ("insert into $table ($column) values('$item');");
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
	// ASSUMPTIONS - html field names (POST key names) = column names	
	// NOTE - Implement data sanitization. 

	function form($form_fields){
		$this -> sanitize($_POST);
		if( count($_POST) > 1 ){
			$multi = implode("','", $_POST);
			$this -> insert($multi, $form_fields);
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
