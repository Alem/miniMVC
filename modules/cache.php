<?php

class Cache{

	function __construct(){
		ob_start();
		$this -> path = SERVER_ROOT . DEFAULT_PUBLIC_PATH . DEFAULT_CACHE_PATH; 
	}

	function __destruct(){
		ob_flush();
	}

	// Recency: Returns a true if the time difference is less than 1000s
	function recency($filename) {
		if (file_exists($filename)) {
			$time_difference = @(time() - filemtime($filename));
			if( $time_difference < 1000 ) 
				$status = true;
			else 
				$status = false;
		}else 
			$status = false;
		return $status;
	}

	// create: Using the data passed from the $output var, writes a file.
	function create($output = null, $filename = null, $ext = null) {
		$output = ( isset($output) ) ? $output : ob_get_contents();
		if ( isset($filename) ){ 
			if ( isset($ext) ) 
				$filename .= '.' . $ext;
		      $filename = $this -> path . $filename;
		}else
		       	$this -> path .preg_replace( '/\//', '.', $_SERVER['REQUEST_URI'] ); 
		if ( $this -> recency( $filename ) === false ){
			$file = fopen($filename,"w") or die("Couldn't Open"); 
			fwrite($file,$output) or die ("Couldn't write");
			fclose($file);
		}
	}

	// get: returns if exists otherwise creates it.
	function get($filename) {
		if ( $this -> recency($filename) ) {
			 return file_get_contents($filename);	
		}else{
			create(ob_get_contents(),$filename);
		}
	}

	// Deletes specified file. 
	function clear($filename = null) {
		if ( isset($filename) ) 
		      $filename = $this -> path . $filename;
		else
		       	$this -> path .preg_replace( '/\//', '.', $_SERVER['REQUEST_URI'] ); 
		unlink($file);
	}
}
?>


