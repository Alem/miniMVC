<?php
/**
 * @todo Get rid of this garbage and use the system/db/databse instead
 */
class QueryTool extends QueryBuilder{

	public $table_info;
	public $table_columns;
	public $filtered_columns;
	public $linked_columns;

	public function getFormattedColumns( $name ){

		$this -> table_columns = $this -> getColumns( $name .'s' );

		if ( !empty ( $this -> table_columns ) ){
			foreach( $this -> table_columns as $table_column){

				if (( $table_column != 'id' ) && ( $table_column != 'user_id' ) )
					$this -> filtered_columns[] = $table_column;
				if ( ( strstr( $table_column, '_id' ) )  !== false  )
					$this -> linked_columns[] =  str_replace( '_id', '', $table_column);
			}
		}
	}


	public function makeTable($name){
		$names = $name . 's';
		$this -> query( "create table $names ( id integer not null primary key auto_increment, $name varchar(128) not null );");
		$this -> run();
		echo "===============================================\n";
		echo " Created $name table.\n";
		echo "===============================================\n";
		echo print_r( Debug::formatArray( Debug::open() -> record ), true);
	}


	public function linkTables( $table, $foreign_table ){
		$table = $table . 's';
		#ADD CONSTRAINT {$foreign_table}_id 
		$sql = <<<SQL
		ALTER TABLE $table
			ADD COLUMN {$foreign_table}_id integer(11) UNSIGNED,
			ADD FOREIGN KEY ( {$foreign_table}_id )
			REFERENCES  {$foreign_table}s(id)
SQL;
		$this -> query( $sql );
		$this -> run();
		echo print_r( Debug::formatArray( Debug::open() -> record ), true);

	}


	public function unlinkTables( $table, $foreign_table ){
		$table = $table . 's';
		$sql = <<<SQL
		ALTER TABLE $table
			DROP FOREIGN KEY {$foreign_table}_id,
			DROP {$foreign_table}_id 
SQL;
		$this -> query( $sql );
		$this -> run();
		echo print_r( Debug::formatArray( Debug::open() -> record ), true);
	}


	public function deleteTable($name){
		if( !empty($name) ){
			$this -> query = "drop table $name".'s'; 
			$this -> run();
			echo "===============================================\n";
			echo " Deleted $name table. \n";
			echo "===============================================\n";
			echo print_r( Debug::formatArray( Debug::open() -> record ), true);
		}
	}


	public function openDB( $query ){
		echo "=============================================== \n";
		echo " Connected to database: " . DB_DATABASE . "\n";
		$this -> query ( $query );
		$this -> run();
		echo " Query executed.\n";
		echo "===============================================\n";
		echo " Results\n";
		echo "-----------------------------------------------";
		echo print_r( Debug::formatArray( Debug::open() -> record ), true);
	}
}
?>
