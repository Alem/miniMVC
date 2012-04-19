<?php

/**
 * Database class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */


class Database{

	/**
	 * @var object Holds the database connection.
	 */
	public $db;


	/** 
	 * db() - Accesses database connection 
	 *
	 * Starts connection if model::db not set, otherwise returns model::db
	 *
	 * @return object The PDO object as a property of the current object
	 * 
	 */

	function db(){
		if ( !isset( $this -> db ) )
			$this-> db = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
		return $this -> db;
	}

}

?>
