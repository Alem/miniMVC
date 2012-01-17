<?php

class Model{

#	var $table =  STRTOLOWER(__CLASS__).'s'; 

	public function db_connect(){
		mysql_select_db($db_config['database'],
				mysql_connect(
					$db_config['server'], 
					$db_config['username'],
					$db_config['password']
					)	
			       );
	}
	public function db_disconnect(){
		mysql_close;
	}

	public function query($query){
		$result = mysql_query($query);
		$query_array = mysql_fetch_assoc($result);
		return $query_array;
	}

}

?>
