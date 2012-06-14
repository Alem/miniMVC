<?php
/**
 * File class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * File simplifies the creation/deletion and management of files.
 *
 */
class File{

	/**
	 * @var The default filename.
	 */
	public $id;

	/**
	 * @var The default path for files
	 */
	public $path;

	/**
	 * @var The default extension for files
	 */
	public $ext;

	/**
	 * setPath - Sets the path of the file ( Not filename, but its directory)
	 *
	 * @param string $path 		The path leading to the file, not including the filename.
	 */
	public function setPath( $path )
	{
		$this->path = $path;
	}

	/**
	 *  write - Writes the supplied data to a file named by the supplied id.
	 *
	 * @param mixed $data 	The data to be written
	 * @param mixed $id 	The identifying value for the data
	 * @return mixed 	The number of bytes written or a false on failure( file_put_contents )
	 */
	public function write( $data, $id = null ) {
		if( !isset( $id ) )
			$id =& $this->id;

		$filename = $this->path . $id . $this->ext;

		return file_put_contents( $filename, $data );
	}

	/**
	 * contents - Returns the contents of the file specified by the supplied id
	 *
	 * @param  mixed $id 	The identifying value for the data
	 * @return string 	The file contents returned as a string( file_get_contents )
	 */
	public function contents( $id = null ) {
		if( !isset( $id ) )
			$id =& $this->id;

		$filename = $this->path . $id . $this->ext;

		if( file_exists($filename) )
			return file_get_contents( $filename );
	}

	/**
	 * delete - Deletes the file specified by the supplied id
	 *
	 * @param mixed $id 	The identifying value for the data
	 * @return bool 	Returns true if file no longer exists, otherwise false.
	 */
	public function delete( $id = null ) {
		if( !isset( $id ) )
			$id =& $this->id;

		$filename = $this->path . $id . $this->ext;

		unlink( $filename );

		if( !file_exists($filename) )
			return true;
		else
			return false;
	}

	/**
	 * Recency - Returns the recency of the file in seconds
	 *
	 * @param  mixed $id 	The identifying value for the data
	 * @return integer 	The recency of the file in seconds
	 */
	public function recency( $id = null ) {
		if( !isset( $id ) )
			$id =& $this->id;

		$filename = $this->path . $id . $this->ext;

		if( file_exists( $filename ) )
			return $time_difference = ( time() - filemtime($id) );
		else
			return null;
	}

	/**
	 * isPathEmpty - Returns true if path is empty
	 *
	 * Checks if path is empty.
	 * If the path is empty, the count will return a value of 2
	 * corresponding to the two listings: '.' and '..'
	 */
	public function isPathEmpty()
	{
		return ( count( scandir( $this->path ) ) === 2  );
	}
}
?>
