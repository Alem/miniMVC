<?php
/**
 * Model class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The model class is the parent for all application models.
 *
 * It serves to retrieve data and encapsulate data.
 * It is provided SQL access by the SQL() method which utilizes the QueryBuilder class.
 *
 */
abstract class Model
{

	/**
	 * The name of the Model object's unit and collection of units.
	 *
	 * Unit name for 'foo': foo
	 * Collection name for 'foo': foos ( unit's database table name by convention )
	 *
	 * @var string
	 */
	public $name = array(
		'unit' => null,
		'collection' => null
	);

	/**
	 * Instance of Query class. Allows building database queries.
	 * @var object
	 */
	public $sql = null;

	/**
	 * Holds the model data
	 * @var array
	 */
	public $data = array();

	/**
	 * __construct
	 *
	 * Runs defineName, defining the Model::name property.
	 */
	public function __construct()
	{
		$this->defineName();
	}

	/**
	 * defineName() - Returns unit name of controller
	 *
	 * @return string 	Unit name of controller ( Basename, without 'Controller' )
	 */
	public function defineName()
	{
		$this->name['unit'] 		= strtolower( get_called_class() );
		$this->name['collection'] 	= $this->name['unit'] . 's';
	}

	/**
	 * set - Assigns data to models data property
	 *
	 * @param mixed $property The name of the property to be assigned OR an array containing multiple property/value pairs
	 * @param mixed $value    The value of the property
	 */
	public function set( $property, $value = null )
	{
		if( is_array( $property) )
		{
			foreach($property as $single_property => $single_value)
				$this->set( $single_property, $single_value);
		}
		else
			$this->data[$property] = $value;
	}

	/**
	 * get - Returns the named data property of the model
	 *
	 * @param  mixed $property 	The name of the property to be returned OR array containing multiple properties who's values are to be returned
	 * @return mixed 		The requested property.
	 */
	public function get( $property )
	{
		if( is_array( $property) )
		{
			foreach($property as $single_property)
				$get_array[] = $this->get( $single_property);
			return $get_array;
		}
		return $this->data[$property];
	}


	/**
	 * SQL - A simple lazy loader wrapper for a QueryBuilder object
	 *
	 * Allows model to use single instance of QueryBuilder to
	 * build queries in a sequential manner.
	 *
	 * @param  Database $database	The Database object to be passed to the QueryBuilder
	 * @return QueryBuilder 	The loaded QueryBuilder object
	 * @uses   QueryBuilder 	Assigned as a property for automated loading of single instance.
	 */
	public function SQL( Database $database = null )
	{
		if( !isset( $this->sql ) )
		{
			if( !isset( $database ) )
				$database = new Database();
			$this->sql = new QueryBuilder( $database, $table = $this->name['collection']  );
		}
		return $this->sql;
	}

}

?>
