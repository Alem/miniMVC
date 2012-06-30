<?php
/**
 * Config class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The config class is used to fetch the configuration arrays
 * stored in the config/ directory of the application.
 *
 * The configuration array files found in config/ are
 * fetched by their filename.
 *
 * ------------------------------
 * Example: To fetch the configuration array file config/foo.php
 *
 * 	$config = new Config();
 *
 * 	$foo_settings = $config->fetch('foo');
 * ------------------------------
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.base
 */
class Config
{

	/**
	 * The directory holding configuration arrays.
	 * @var string
	 */
	public $config_dir = null;


	/**
	 * Holds the loaded configuration arrays
	 * @var array
	 */
	public $loaded = array();


	/**
	 * basePath() - Returns or sets config directory
	 *
	 * If the Config::config_dir property is not defined,
	 * it is set using the $path parameter. Otherwise the Load
	 * object is instantiated and the default value, Load::paths['config'] is used.
	 * 
	 * @param string $path 	 The directory that holds the application configuration arrays
	 * @return array
	 */
	public function basePath( $path = null )
	{
		if( $this->config_dir === null )
		{
			if( $path !== null )
				$this->config_dir = $path;
			else
			{
				$load = new Load();
				$this->config_dir = $load->path( 'config' );
			}
		}
		return $this->config_dir;
	}


	/**
	 * fetch() - fetchs the specified configuration file
	 *
	 * Returns the configuration type if found in Config::loaded array
	 * otherwise loads it by constructing the path using the Config::basePath 
	 * and the config type name appended with '.php','.yml',
	 * or '.yaml'
	 *
	 * @param string $type 		The name of the configuration file.
	 * @param bool 	 $returns_array True if the configuration file returns an array.
	 * @return Config 		The current Config object
	 *
	 * @todo Transition to content-type determination of lang vs extension-based?
	 */
	public function fetch( $type, $returns_array = true )
	{
		if( isset( $this->loaded[$type] ) )
			return $this->loaded[$type];
		else
			$this->loaded[$type] = null;

		$name_path  = $this->basePath() . $type;

		foreach( array('.php','.yml','.yaml') as $ext )
		{
			if( file_exists( $name_path . $ext ) )
			{
				if( $returns_array )
				{
					if( $ext === '.php' )
						$this->loaded[$type] = require( $name_path . $ext );
					elseif( function_exists( 'yaml_parse_file' ) )
						$this->loaded[$type] = yaml_parse_file( $name_path . $ext );
					else
						throw new Exception('Could not parse YAML file "'. $name_path . $ext . '", yaml_parse_file() method requires the YAML package of the PECL extension');
				}
				else
					require( $name_path . $ext );
			}
		}

		return $this->loaded[$type];
	}
}

?>
