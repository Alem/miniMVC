<?php
/**
 * FileCache class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * FileCache grants the ability to cache data to a specified file.
 *
 * Todo Allow the setting of id outside functions, as well as giving md5(id) option.
 */
class FileCache{

	/**
	 * @var path The default path for cache files
	 */
	public $path;

	/**
	 * @var path The default extension for cache files
	 */
	public $ext;


	/**
	 * __construct - Defines the default path and extension for cache files
	 */
	function __construct(){
		$this -> path = SERVER_ROOT . DEFAULT_PUBLIC_PATH . DEFAULT_CACHE_PATH; 
		$this -> ext  = '.tmp';
	}

	/**
	 *  create - Writes the supplied data to a file named by the supplied id.
	 *
	 * @param mixed id 	The identifying value for the data
	 * @param mixed data 	The data to be cached
	 * @return mixed 	The number of bytes written or a false on failure ( file_put_contents )
	 */
	function create( $id, $data ) {
		$filename = $this -> path . $id . $this -> ext;
		return file_put_contents( $filename, $data );
	}

	/**
	 * get - Returns the cache file specified by the supplied id
	 *
	 * @param mixed id 	The identifying value for the data
	 * @return string 	The file contents returned as a string ( file_get_contents )
	 */
	function get( $id ) {
		$filename = $this -> path . $id . $this -> ext;
		if ( file_exists($id) )
			return file_get_contents( $filename );	
	}

	/**
	 * clear - Deletes the cache file specified by the supplied id
	 *
	 * @param mixed id 	The identifying value for the data
	 */
	function clear( $id ) {
		$filename = $this -> path . $id . $this -> ext;
		unlink( $filename );
	}

	/**
	 * Recency - Returns the recency of cache file in seconds
	 *
	 * @param mixed id 	The identifying value for the data
	 * @return integer 	The recency of the cache file in seconds
	 */
	function recency( $id ) {
		$filename = $this -> path . $id . $this -> ext;
		if ( file_exists( $filename ) )
			return $time_difference = ( time() - filemtime($id) );
		else 
			return null;
	}
}
?>
