<?php

/**
 * Database class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Database class serves to hold the database connection.
 *
 * @todo Revise current scheme. Support for multiple connections?
 */


class Database{


	/**
	 * __construct - Creates default database configuration.
	 */
	public function __construct(){
		$this -> db['main'] = array(
			'host' 	   => DB_SERVER,
			'username' => DB_USERNAME,
			'password' => DB_PASSWORD,
			'database' => DB_DATABASE,
			'connection' => null
		);
	}


	/**
	 * @var object Holds the database connection.
	 */
	public $db;


	/** 
	 * db() - Accesses database connection 
	 *
	 * Starts connection if model::db not set, otherwise returns model::db
	 *
	 * @param string name 	The name of the connection. Defaults to 'main'.
	 * @return object 	The PDO object as a property of the current object
	 */
	function db( $name = 'main' ){
		if ( !isset( $this -> db[$name]['connection'] ) )
			$this-> db[$name]['connection'] = new PDO('mysql:host=' . $this ->db[$name]['host'] . ';dbname=' . $this ->db[$name]['database'], $this ->db[$name]['username'], $this ->db[$name]['password']);

		return $this -> db[$name]['connection'];
	}

}

?>
