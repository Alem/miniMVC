<?php
/**
 * FileCache class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * FileCache grants the ability to cache data to a specified file.
 *
 */
class FileCache extends File implements ICache
{

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
	public function __construct( $set_defaults = true )
	{
		if( $set_defaults === true)
		{
			$this->path = SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CACHE_PATH;
			$this->ext  = '.tmp';
		}
	}

	/**
	 *  add - Writes the supplied data to a file named by the supplied id, if it doesn't exit.
	 *
	 * @param mixed $data 	The data to be cached
	 * @param mixed $id 	The identifying value for the data
	 * @return mixed 	The number of bytes written or a false on failure( file_put_contents )
	 */
	public function add( $data, $id = null, $expiry = null )
	{
		if ( file_exists( $this -> path . $id ) === false)
			return $this->write( $data, $id );
		else
			return false;
	}

	/**
	 *  set - Writes the supplied data to a file named by the supplied id.
	 *
	 * @param mixed $data 	The data to be cached
	 * @param mixed $id 	The identifying value for the data
	 * @return mixed 	The number of bytes written or a false on failure( file_put_contents )
	 */
	public function set( $data, $id = null, $expiry = null )
	{
		return $this->write( $data, $id );
	}

	/**
	 * get - Returns the cache file specified by the supplied id
	 *
	 * @param  mixed $id 	The identifying value for the data
	 * @return string 	The file contents returned as a string( file_get_contents )
	 */
	public function get( $id = null )
	{
		return $this->contents( $filename );
	}

	/**
	 * del - Deletes the cache file specified by the supplied id
	 *
	 * @param mixed $id 	The identifying value for the data
	 * @return bool 	Returns true if file no longer exists, otherwise false.
	 */
	public function del( $id = null )
	{
		return $this->delete( $id );
	}

	/**
	 * flush - Deletes all cache files in specified path
	 */
	public function flush()
	{
		$dir_handle = opendir( $this->path );

		while( $file = readdir( $dir_handle ) !== false )
		{
			unlink( $this -> path . $file );
		}
		closedir( $dir_handle );
	}

}
?>
