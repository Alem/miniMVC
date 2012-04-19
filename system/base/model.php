<?php


/**
 * Model class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */


class Model{

	/**
	 * @var string The name of the Model object's database table.
	 */
	public $table;

	/**
	 * @var string The name of the Model object's epynomous table column.
	 */
	public $column;

	/**
	 * @var object Instance of Query class. Allows building database queries.
	 * @todo Implement query in model as a property object rather than being extension of it.
	 */
	public $sql;


	/**
	 * __construct
	 *
	 * Assigns the model the 'table' property, lowercase pluralized name of the controller class
	 * and the table's main column the lowercase singular name of the controller class.
	 */
	function __construct(){
		$column =  strtolower( get_class($this) );
		$table = $column . 's';
		$this -> table = $table;
		$this -> column = $column;
	}


	/**
	 * set - Assigns property to model
	 *
	 * @oaram mixed $property The name of the property to be assigned OR an array containing multiple property/value pairs
	 * @param mixed $value    The value of the property
	 */
	function set($property, $value = null){
		if ( is_array( $property) ){
			foreach($property as $single_property => $single_value)
				$this -> set( $single_property, $single_value);
		}else
			$this -> $property = $value;
	}


	/**
	 * get - Returns the named property of the model
	 *
	 * @param  mixed $property - The name of the property to be returned OR array containing multiple properties who's values are to be returned
	 * @return mixed             The requested property.
	 */
	function get($property){
		if ( is_array( $property) ){
			foreach($property as $single_property)
				$get_array[] = $this -> get( $single_property);
			return $get_array;
		}
		return $this -> $property;
	}


	function SQL(){
		if ( !isset( $this -> sql ) ){
			$this -> sql = new QueryBuilder();
			$this -> sql -> table =& $this -> table;
		}
		return $this -> sql;
	}

}

?>