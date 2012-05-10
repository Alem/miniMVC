<?php
/**
 * FileCache class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * FileCache grants the ability to cache data to a specified file.
 *
 */
class FileCache{

	/**
	 * @var The default filename to use for caching.
	 */
	public $id;

	/**
	 * @var The default path for cache files
	 */
	public $path;

	/**
	 * @var The default extension for cache files
	 */
	public $ext;


	/**
	 * __construct - Defines the default path and extension for cache files
	 */
	function __construct ( $set_defaults = true ) 
	{
		if ( $set_defaults === true) 
		{
			$this -> path = SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_PUBLIC_PATH . DEFAULT_CACHE_PATH; 
			$this -> ext  = '.tmp';
		}
	}

	/**
	 *  create - Writes the supplied data to a file named by the supplied id.
	 *
	 * @param mixed $data 	The data to be cached
	 * @param mixed $id 	The identifying value for the data
	 * @return mixed 	The number of bytes written or a false on failure ( file_put_contents )
	 */
	function create( $data, $id = null ) {
		if ( !isset( $id ) )
			$id =& $this -> id;

		$filename = $this -> path . $id . $this -> ext;

		return file_put_contents( $filename, $data );
	}

	/**
	 * get - Returns the cache file specified by the supplied id
	 *
	 * @param  mixed $id 	The identifying value for the data
	 * @return string 	The file contents returned as a string ( file_get_contents )
	 */
	function get( $id = null ) {
		if ( !isset( $id ) )
			$id =& $this -> id;

		$filename = $this -> path . $id . $this -> ext;

		if ( file_exists($filename) )
			return file_get_contents( $filename );	
	}

	/**
	 * clear - Deletes the cache file specified by the supplied id
	 *
	 * @param mixed $id 	The identifying value for the data
	 * @return bool 	Returns true if file no longer exists, otherwise false.
	 */
	function clear( $id = null ) {
		if ( !isset( $id ) )
			$id =& $this -> id;

		$filename = $this -> path . $id . $this -> ext;

		unlink( $filename );

		if ( !file_exists($filename) )
			return true;
		else
			return false;
	}

	/**
	 * Recency - Returns the recency of cache file in seconds
	 *
	 * @param  mixed $id 	The identifying value for the data
	 * @return integer 	The recency of the cache file in seconds
	 */
	function recency( $id = null ) {
		if ( !isset( $id ) )
			$id =& $this -> id;

		$filename = $this -> path . $id . $this -> ext;

		if ( file_exists( $filename ) )
			return $time_difference = ( time() - filemtime($id) );
		else 
			return null;
	}
}
?>
