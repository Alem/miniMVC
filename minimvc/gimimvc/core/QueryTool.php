<?php
/**
 * QueryTool class file.
 *
 * @author Z. Alem <info@alemcode.com>
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
	 * Holds table information. Populated by getFormattedColumns().
	 * @var array
	 */
	public $table_info;

	/**
	 * Holds table column names. Populated by getFormattedColumns().
	 * @var array
	 */
	public $table_columns;

	/**
	 * Holds table column names excluding id, user_id. Populated by getFormattedColumns().
	 * @var array
	 */
	public $filtered_columns;

	/**
	 * Holds table column names that exhibit the linked column naming convention.
	 * @var array
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

		$this->table_columns = $this->getColumns( $name .'s' );

		if ( !empty ( $this->table_columns ) )
		{
			foreach( $this->table_columns as $table_column)
			{

				if (( $table_column != 'id' ) && ( $table_column != 'user_id' ) )
					$this->filtered_columns[] = $table_column;
				if ( ( strstr( $table_column, '_id' ) )  !== false  )
					$this->linked_columns[] =  str_replace( '_id', '', $table_column);
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
		$query = "create table $names ( id integer unsigned not null primary key auto_increment, $name varchar(128) not null );";
		$this->runAndPrint( $query );

		echo " Created $name table.\n";
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

			$this->runAndPrint( $query );

			echo " Deleted $name table. \n";
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

		$this->runAndPrint( $query );

		echo " Linked $name to $foreign_name.\n";

	}


	/**
	 * unlinkTables() - Unlinks the tables of one MVC unit from another
	 *
	 * @param string $table 	The name of the MVC unit.
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

		$this->runAndPrint( $query );

		echo " Unlinked $name to $foreign_name.\n";
	}


	/**
	 * openDB() - Uses application database config to allow direct database access.
	 *
	 * @param string $query 	The SQL query
	 */
	public function openDB( $query )
	{
		echo " Connected to database: " . $this->database->settings['database'] . "\n";
		$this->runAndPrint( $query );
	}


	/**
	 * todo
	 *
	 * @param string $table 
	 */
	public function getTableReferences( $table )
	{
		$query = <<<SELECT
		SELECT $table 
		from information_schema.KEY_COLUMN_USAGE
		where table_schema = '{$this->database->settings['database']}'
		and referenced_table_name = $table
SELECT;
		$this->runAndPrint( $query );
	}


	/**
	 * importData() - Reads specified file in application data/ into database.
	 *
	 * Note that files containing multiple queries will not complete or produce their intended effects.
	 * The database abstraction is built around PDO which seemingly does not permit 
	 * multiple in-line queries.
	 *
	 * @param string $filename 	The name of the file
	 */
	public function importData( $filename )
	{
		$fullpath = SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_DATA_PATH . $filename;

		if( file_exists( $fullpath ) )
		{
			$query = file_get_contents( $fullpath );
			$this->runAndPrint( $query );
		}
		else
			echo "Error: Schema file '$fullpath' does not exist";
	}


	/**
	 * exportData() - Exports database data into a file saved in the application data/ directory.
	 *
	 * @param string $unit 		The name of the unit to export.
	 * 				If unspecified, exports entire database.
	 */
	public function exportData( $unit = null )
	{
		// This may not be feasible/practical.
	}


	/**
	 * runAndPrint() - Runs query and executes printResults()
	 *
	 * @param string $query 	The SQL query
	 */
	public function runAndPrint( $query )
	{
		$this->query( $query );
		$result = $this->run();
		$this->printResults( $query, $result );
	}


	/**
	 * printResults - Prints query results, original query and errors
	 *
	 * @param string $query 	The SQL query
	 * @param array  $result 	The SQL query results
	 */
	public function printResults( $query, array $result )
	{
		echo "\n";
		echo "===============================================\n";
		echo " Database Query Report.\n";
		echo "===============================================\n";
		echo " Query: $query \n";
		echo "-----------------------------------------------\n";
		echo " Result:\n";
		echo ArrayUtil::makeReadable( $result );
		echo "-----------------------------------------------\n";
		echo " Errors: \n";
		echo ArrayUtil::makeReadable( $this->query_errors );
		echo "-----------------------------------------------\n";
	}
}
?>
