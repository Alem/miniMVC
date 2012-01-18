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

	public function query( $query, $rows=1 ){
		$this->db_connect();
		$result = mysql_query($query);
		for( $i = 0; $i <= $rows; $i ++){
			$query_array[$i] = mysql_fetch_assoc($result);
		}
		$this->db_disconnect();
		return $query_array;
	}

}

?>
