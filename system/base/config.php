<?php
/**
 * Config class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The config class is used to load the configuration arrays 
 * stored in the config/ directory of the application.
 *
 * The configuration array files found in config/ are 
 * loaded and accessed by their filename.
 *
 * ------------------------------
 * Example: To load the file config/foo.php
 *
 * 	$config = new Config();
 *
 * 	$config -> load('foo');
 * 	echo $config -> foo['default']['setting'];
 * ------------------------------
 *
 */
class Config
{

	/**
	 * load() - Loads the specified configuration file
	 *
	 * @param string $type 		The name of the configuration file.
	 * @param bool 	 $returns_array True if the configuration file returns an array.
	 * @return Config 		The current Config object
	 */
	public function load( $type, $returns_array = true ) 
	{
		$this -> $type = null;
		$path = load::path('config', $type );

		if ( file_exists( $path ) )
		{

			if ( $returns_array )
				$this -> $type = require( $path );
			else
				require( $path );
		}

		return $this -> $type;
	}
}

?>
