<?php

class Model{

	function __construct(){
		$table = strtolower(get_class($this));
		$this -> table = $table;
	}

	public function db_connect(){
		mysql_select_db( DB_DATABASE, mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD ));
	}

	public function db_disconnect(){
		mysql_close( mysql_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD	) );
	}

	public function query( $query, $row = 1 ){
		$this -> db_connect();
		$result = mysql_query($query) or die('Query failed :(');
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

	function insert($item, $column){
		$table = $this -> table;
		$this -> query ("insert into $table ($column) values('$item');");
	}

	function remove($item, $column){
		$table = $this -> table;
		$this -> query("delete from $table where $column='$item';");
	}
}

?>
