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
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.database
 */
class Database
{

	/**
	 * Holds the database host
	 *
	 * @var string
	 */
	public $host;

	/**
	 * Holds the database username
	 *
	 * @var string
	 */
	public $username;

	/**
	 * Holds the database password
	 *
	 * @var string
	 */
	public $password;

	/**
	 * Holds the database name.
	 *
	 * @var string
	 */
	public $dbname;

	/**
	 * Holds the database connection.
	 *
	 * @var PDO
	 */
	public $pdo_connection;

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
