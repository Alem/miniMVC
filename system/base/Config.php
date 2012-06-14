<?php
/**
 * Config class file.
 *
 * @author Z. Alem <info@alemcode.com>
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
 */
class Config
{

	/**
	 * @var string The directory holding configuration arrays.
	 */
	public $config_dir = null;


	/**
	 * @var array  Holds the loaded configuration arrays
	 */
	public $loaded = array();


	/**
	 * basePath() - Returns or sets config directory
	 *
	 * If the Config::config_dir property is not defined,
	 * it is set using the $path parameter. Otherwise the Load
	 * object is instantiated and the default value, Load::paths['config'] is used.
	 * 
	 * $param string $path
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
				$this->config_dir = $load->path( 'config', null, null );
			}
		}
		return $this->config_dir;
	}


	/**
	 * fetch() - fetchs the specified configuration file
	 *
	 * Returns the configuration type if found in Config::loaded array
	 * otherwise loads it by constructing the path using the Config::basePath 
	 * and the config type name appended with a '.php'
	 *
	 * @param string $type 		The name of the configuration file.
	 * @param bool 	 $returns_array True if the configuration file returns an array.
	 * @return Config 		The current Config object
	 */
	public function fetch( $type, $returns_array = true )
	{
		if( isset( $this->loaded[$type] ) )
			return $this->loaded[$type];
		else
			$this->loaded[$type] = null;

		$path = $this->basePath() . $type . '.php';

		if( file_exists( $path ) )
		{
			if( $returns_array )
				$this->loaded[$type] = require( $path );
			else
				require( $path );
		}

		return $this->loaded[$type];
	}
}

?>
