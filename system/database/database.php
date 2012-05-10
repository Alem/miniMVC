<?php

/**
 * Database class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Database class serves to hold the database connection.
 *
 */
class Database
{

	/**
	 * @var string Holds the database host
	 */
	public $host;


	/**
	 * @var string Holds the database username
	 */
	public $username;


	/**
	 * @var string Holds the database password
	 */
	public $password;


	/**
	 * @var string Holds the database name.
	 */
	public $dbname;


	/**
	 * @var PDO Holds the database connection.
	 */
	public $pdo_connection;


	/**
	 * __construct - Creates default database configuration.
	 */
	public function __construct()
	{
		$this -> host 		= DB_SERVER;
		$this -> username 	= DB_USERNAME;
		$this -> password 	= DB_PASSWORD;
		$this -> dbname 	= DB_DATABASE;
		$this -> pdo_connection = null;
	}


	/** 
	 * connection() - Accesses database connection 
	 *
	 * Starts connection if model::db not set, otherwise returns model::db
	 *
	 * @return PDO 			The PDO object as a property of the current object
	 */
	function connection()
	{
		if ( !isset( $this -> pdo_connection ) )
			$this-> pdo_connection = new PDO( 'mysql:host=' . $this -> host . ';dbname=' . $this -> dbname, $this -> username, $this -> password );
		return $this -> pdo_connection;
	}

}

?>
