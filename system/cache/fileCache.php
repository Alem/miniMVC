<?php

/**
 * FileCache
 *
 * Todo Reimp module as system class
 *
 */

class FileCache{

	public $path;
	public $ext;

	function __construct(){
		$this -> path = SERVER_ROOT . DEFAULT_PUBLIC_PATH . DEFAULT_CACHE_PATH; 
		$this -> ext  = '.tmp';
	}

	// create: Using the data passed from the $output var, writes a file.
	function create( $id, $data ) {
		$filename = $this -> path . $id . $this -> ext;
		return file_put_contents( $filename, $data );
	}

	// get: returns if exists otherwise creates it.
	function get( $id ) {
		$filename = $this -> path . $id . $this -> ext;
		if ( file_exists($id) )
			return file_get_contents( $filename );	
	}

	// Deletes specified file. 
	function clear( $id ) {
		$filename = $this -> path . $id . $this -> ext;
		unlink( $filename );
	}

	// Recency: Returns the recency of cache file in seconds
	function recency( $id ) {
		$filename = $this -> path . $id . $this -> ext;
		if ( file_exists( $filename ) )
			return $time_difference = ( time() - filemtime($id) );
		else 
			return null;
	}
}
?>
