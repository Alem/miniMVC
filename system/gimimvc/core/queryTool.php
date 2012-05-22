<?php
/**
 * QueryTool class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The QueryTool is an extension of the QueryBuilder that facilitates
 * following database interactions:
 * 	> Table column profiling.
 * 	> Table creation/deletion
 * 	> Foreign primary key creation/deletion
 * 	> Direct queries
 */
class QueryTool extends QueryBuilder
{

	/**
	 * @var array Holds table information. Populated by getFormattedColumns().
	 */
	public $table_info;


	/**
	 * @var array Holds table column names. Populated by getFormattedColumns().
	 */
	public $table_columns;


	/**
	 * @var array Holds table column names excluding id, user_id. Populated by getFormattedColumns().
	 */
	public $filtered_columns;


	/**
	 * @var array Holds table column names that exhibit the linked column naming convention.
	 * 		 Populated by getFormattedColumns().
	 */
	public $linked_columns;


	/**
	 * getFormattedColumns() - Retrieves and formats information for an MVC unit's table. 
	 *
	 * @param string $name 		The name of the MVC unit.
	 */
	public function getFormattedColumns( $name )
	{

		$this -> table_columns = $this -> getColumns( $name .'s' );

		if ( !empty ( $this -> table_columns ) )
		{
			foreach( $this -> table_columns as $table_column)
			{

				if (( $table_column != 'id' ) && ( $table_column != 'user_id' ) )
					$this -> filtered_columns[] = $table_column;
				if ( ( strstr( $table_column, '_id' ) )  !== false  )
					$this -> linked_columns[] =  str_replace( '_id', '', $table_column);
			}
		}
	}


	/**
	 * makeTable() - Creates a table for an MVC unit
	 *
	 * @param string $name 		The name of the MVC unit.
	 */
	public function makeTable($name)
	{
		$names = $name . 's';
		$query = "create table $names ( id integer not null primary key auto_increment, $name varchar(128) not null );";
		$this -> query( $query );
		$result = $this -> run();
		$this -> printResults( $query, $result );
		echo "===============================================\n";
		echo " Created $name table.\n";
		echo "===============================================\n";
	}


	/**
	 * makeTable() - Deletes the table of an MVC unit
	 *
	 * @param string $name 		The name of the MVC unit.
	 */
	public function deleteTable($name)
	{
		if( !empty($name) )
		{
			$query = "drop table $name".'s'; 
			$this -> query = $query;
			$result = $this -> run();
			$this -> printResults( $query, $result );
			echo "===============================================\n";
			echo " Deleted $name table. \n";
			echo "===============================================\n";
		}
	}


	/**
	 * linkTables() - Links the tables of one MVC unit to another
	 *
	 * @param string $name 		The name of the MVC unit.
	 * @param string $foreign_name 	The name of the foriegn MVC unit.
	 */
	public function linkTables( $name, $foreign_name )
	{
		$table = $name . 's';
		#ADD CONSTRAINT {$foreign_table}_id 
		$query = <<<QUERY
		ALTER TABLE $table
			ADD COLUMN {$foreign_name}_id integer(11) UNSIGNED,
			ADD FOREIGN KEY ( {$foreign_name}_id )
			REFERENCES  {$foreign_name}s(id)
QUERY;
		$this -> query( $query );
		$results = $this -> run();
		$this -> printResults( $query, $result );
		echo "===============================================\n";
		echo " Linked $name to $foreign_name.\n";
		echo "===============================================\n";

	}


	/**
	 * unlinkTables() - Unlinks the tables of one MVC unit from another
	 *
	 * @param string $name 		The name of the MVC unit.
	 * @param string $foreign_name 	The name of the foriegn MVC unit.
	 */
	public function unlinkTables( $table, $foreign_name )
	{
		$table = $name . 's';
		$query = <<<QUERY
		ALTER TABLE $table
			DROP FOREIGN KEY {$foreign_name}_id,
			DROP {$foreign_name}_id 
QUERY;
		$this -> query( $query );
		$result = $this -> run();
		$this -> printResults( $query, $result );
		echo "===============================================\n";
		echo " Unlinked $name to $foreign_name.\n";
		echo "===============================================\n";
	}


	/**
	 * openDB() - Uses application database config to allow direct database access.
	 *
	 * @param string $query 	The SQL query
	 */
	public function openDB( $query )
	{
		echo "=============================================== \n";
		echo " Connected to database: " . $this -> database -> settings['database'] . "\n";
		$this -> query ( $query );
		$result = $this -> run();
		$this -> printResults( $query, $result );
	}

	/**
	 * printResults - Prints query results, original query and errors
	 */
	public function printResults( $query, array $result )
	{
		echo " Query executed.\n";
		echo "===============================================\n";
		echo " Query: $query \n";
		echo "-----------------------------------------------\n";
		echo " Result:\n";
		echo print_r( ArrayUtility::makeReadable( $result ), true);
		echo "-----------------------------------------------\n";
		echo " Errors: \n";
		echo print_r( ArrayUtility::makeReadable( $this -> query_errors ), true);
		echo "-----------------------------------------------\n";
	}
}
?>
