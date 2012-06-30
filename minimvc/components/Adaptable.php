<?php
/**
 * Adaptable class file
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * Adaptable class implements a default, standardized function set
 * for classes that can be adapted by inter-changable units.
 */
abstract class Adaptable
{

	/**
	 * Must provide the package name, which matches
	 * the name of the containing the adapter when in lowercase,
	 * the type of adapter, and the directory of the adapting class
	 * @var string
	 */
	public $adapter_info = array( 
		'package' => '',
		'type' => '', 
		'adapting_dir' => __DIR__,
	);


	/**
	 * Holds the base name of the active adapter
	 * @var string
	 */
	public $adapter_active = null;


	/**
	 * Holds adapter instances
	 * @var array
	 */
	public $adapter_collection = array();

	/**
	 * useAdapter() - Loads adapter to object
	 *
	 * Takes the name of the adapter and, if
	 * it is not yet instantiated, requires and
	 * instantiates an instance.
	 */
	public function useAdapter( $name )
	{
		if( $name === null && $this->adapter_active !== null  )
			$name = $this->adapter_active;
		else
			$this->adapter_active = $name;

		if( !isset( $this->adapter_collection[$name] ) )
			$this->adapter_collection[$name] = $this->adapterFactory( $name );

		return 	$this->adapter_collection[$name];
	}

	/**
	 * adapterFactory() - Instantiates adapter, constructing path and class name from base name
	 *
	 * @param string $name 	
	 */
	public function adapterFactory( $name )
	{
		$class = $name . $this->adapter_info['package'] . $this->adapter_info['type'];
		$path  = $this->adapter_info['adapting_dir'] . '/' . strtolower( $this->adapter_info['type'] ) . '/' . $class . '.php';

		if( file_exists( $path ) )
		{
			require_once( $path );
			return new $class();
		}
		else
			return null;
	}

}
?>
