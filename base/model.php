<?php

/**
 * Model class file.
 *
 * @author Zersenay Alem <info@alemmedia.com>
 * @link http://www.alemmedia.com/
 * @copyright Copyright &copy; 2008-2012 Alemmedia
 */


class Model extends Query{

	public $table;
	public $column;
	public $db;
	public $query;
	public $query_data = array();


	// __construct 
	//
	// Assigns the model the 'table' property, lowercase pluralized name of the controller class
	// and the table's main column the lowercase singular name of the controller class.

	function __construct(){
		$column =  strtolower( get_class($this) );
		$table = $column . 's';
		$this -> table = $table;
		$this -> column = $column;
	}


	// set - Assigns property to model
	//
	// $property - The name of the property to be assigned OR 
	// 		an array containing multiple property/value pairs
	// $value - The value of the property

	function set($property, $value = null){
		if ( is_array( $property) ){
			foreach($property as $single_property => $single_value)
				$this -> set( $single_property, $single_value);
		}else
			$this -> $property = $value;
	}


	// Get - Returns the named property of the model
	//
	// $property - The name of the property to be returned OR 
	// 		array containing multiple properties who's values are to be returned

	function get($property){
		if ( is_array( $property) ){
			foreach($property as $single_property)
				$get_array[] = $this -> get( $single_property);
			return $get_array;
		}
		return $this -> $property;
	}


}

?>
