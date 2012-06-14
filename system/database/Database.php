<?php

/**
 * Database class file.
 *
 * @author Z. Alem <info@alemcode.com>
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
	public function __construct( array $settings = null )
	{
		if( $settings === null )
		{
			$config = new Config();
			$settings = $config->fetch('database');
			$this->settings = $settings['default'];
		}
		$this->pdo_connection = null;
	}

	/**
	 * connection() - Accesses database connection
	 *
	 * Starts connection if model::db not set, otherwise returns model::db
	 *
	 * @return PDO 			The PDO object as a property of the current object
	 */
	public function connection()
	{
		if( !isset( $this->pdo_connection ) )
		{
			$this-> pdo_connection = new PDO(
				$this->settings['driver'] . ':host=' .
				$this->settings['host'] . ';dbname=' .
				$this->settings['database'],
				$this->settings['username'],
				$this->settings['password']
			);
		}
		return $this->pdo_connection;
	}

}

?>
