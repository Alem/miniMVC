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
 * Example: To fetch the file config/foo.php
 *
 * 	$config = new Config();
 *
 * 	$config->fetch('foo');
 * 	echo $config->foo['default']['setting'];
 * ------------------------------
 *
 */
class Config
{

	/**
	 * @var Load 	Holds instance of the Load class
	 */
	public $load = null;

	/**
	 * @var array  Holds the loaded configuration arrays
	 */
	public $loaded = array();

	/**
	 * fetch() - fetchs the specified configuration file
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

		if( !isset( $this->load ) )
			$this->load = new Load();

		$path = $this->load->path( 'config', $type );

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
