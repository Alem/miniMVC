<?php

class Database{

	private static $instance;

	private function __construct(){
	}

	public static function open() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
 
	function run() {
		$db_link = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE,DB_USERNAME,DB_PASSWORD);
		$statement = $db_link -> query($this -> query);
		if ( !$statement )
			echo "Errors: " . print_r( $db_link -> errorInfo() );
		else{
			return  $statement -> fetchall(PDO::FETCH_ASSOC);
			echo "Query executed \n";
		}
	}

	function get_columns( $name ){
		$this -> query = 'show columns from ' .  $name . 's';
		$this -> table_info = $this -> run();
		foreach( $this -> table_info as $table_row => $table_column){
			$this -> table_columns[] = $table_column['Field'];
			if ( ( $table_column['Field'] != 'id' ) && ( $table_column['Field'] != 'user_id' ) ){
				#$table_column['Field'] = str_replace ( '_', ' ', $table_column['Field'] );
				$this -> filtered_columns[] = $table_column['Field'];
			}
		}
	}


	function openDB( $query ){
		echo "Connected to database: " . DB_DATABASE . "\n";
		$this -> query = $query;
		$this -> run();
		echo "Query executed.\n";
	}
}

?>
