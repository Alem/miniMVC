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
		if( !isset( $this->adapter[$name] ) )
		{
			$class = $name . $this->adapter_info['package'] . $this->adapter_info['type'];
			$path  = $this->adapter_info['adapting_dir'] . '/' . strtolower( $this->adapter_info['type'] ) . '/' . $class . '.php';

			if( file_exists( $path ) )
			{
				require( $path );
				$this->adapterinstances[$name] = new $class();
			}
			else
				return null;
		}
		return 	$this->adapterinstances[$name];
	}

}
?>
