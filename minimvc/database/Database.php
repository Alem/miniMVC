<?php

/**
 * Database class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Database class serves to hold the database connection.
 * In essence, it is a simplified convenience wrapper/abstracted interface for 
 * the PDO object, which is held in Database::PDO.
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.database
 */
class Database
{
	/**
	 * Holds the database connection settings.
	 *
	 * @var string
	 */
	public $settings;

	/**
	 * Holds the database connection.
	 *
	 * @var PDO
	 */
	public $PDO;

	/**
	 * Holds the database errors.
	 *
	 * @var array
	 */
	public $errors;


	/**
	 * __construct - Creates default database configuration.
	 *
	 * @param array $settings 	The database connection settings. Typically located in the application's config/database.php
	 */
	public function __construct( array $settings = null )
	{
		if( $settings === null )
		{
			$config = new Config();
			$settings = $config->fetch('database');
			$this->settings = $settings['default'];
		}
		$this->PDO = null;
	}

	/**
	 * PDOConnection() - Accesses database connection
	 *
	 * Starts connection if model::db not set, otherwise returns model::db
	 *
	 * @return PDO 			The PDO object as a property of the current object
	 */
	public function PDOConnection()
	{
		if( !isset( $this->PDO ) )
		{
			$this-> PDO = new PDO(
				$this->settings['driver'] . ':host=' .
				$this->settings['host'] . ';dbname=' .
				$this->settings['database'],
				$this->settings['username'],
				$this->settings['password']
			);
		}
		return $this->PDO;
	}


	/**
	 * runStatement - Prepares and executes PDO statement, returns result profile
	 *
	 * @param string query 		The parameterized query to execute.
	 * @param array  query_data 	The parameterized data.
	 * @return array 		The result profile.
	 */
	public function runStatement( $query, $query_data )
	{
		$this->PDO_statement = $this->PDOConnection()->prepare( $query );
		$this->PDO_statement->execute( $query_data );
		$this->errors = array(
			'Database'  => $this->PDO->errorInfo(),
			'Statement' => $this->PDO_statement->errorInfo(),
		);

		Logger::debug('PDO Query', $query );
		Logger::debug('PDO Data',  $query_data );
		if( 
			$this->errors['Database'][0] != '00000'
			|| $this->errors['Statement'][0] != '00000'  
		)
		Logger::error('PDO Errors', $this->errors );

		return array(
			'fetched' 	 => $this->PDO_statement->fetchall( PDO::FETCH_ASSOC ),
			'row_count'	 => $this->PDO_statement->rowCount(),
			'last_insert_id' => $this->PDO->lastInsertId(),
		);

	}

}

?>
