<?php

class Model{

#	var $table =  STRTOLOWER(__CLASS__).'s'; 
	public function db_connect(){
		mysql_select_db(DB_DATABASE,
				mysql_connect(
					DB_SERVER, 
					DB_USERNAME,
					DB_PASSWORD	
					)	
			       );
	}

	public function db_disconnect(){
		mysql_close();
	}

	public function query( $query, $row = 1 ){
		$this->db_connect();
		$result = mysql_query($query);
		while( $query = mysql_fetch_assoc($result) or die('No match found') ) {
			$query_array[$row] = $query;
			$row++;
		}
		$this->db_disconnect();
		return $query_array;
	}

}

?>
