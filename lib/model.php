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
		$result = mysql_query($query) or die("Query failed : $query");
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

	function select($item, $column = 'id', $row = 1 ){
		$table = $this -> table;
		if ($item == '*'){
			return $result = $this -> query("select * from $table;");
		}else{
			return $result = $this -> query("select from $table where $column='$item');");
		}
	}

	function insert($item, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : $this -> column;
		$this -> query ("insert into $table ($column) values('$item');");
	}

	function remove($item, $column = null ){
		$table = $this -> table;
		$column = ( isset($column) ) ?  $column : 'id';
		$this -> query("delete from $table where $column='$item';");
	}

}

?>
