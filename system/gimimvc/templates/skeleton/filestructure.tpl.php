<?php

class FileStructure extends Template{


	public $dirs = array( 
		'/data/', 	'/logs/',		'/modules/', 		
		'/libraries/',  '/views/shared/'
	);


	public function generate(){
		foreach ( $this -> dirs as $dir ){
			$path = GIMIMVC_ROOT . 'applications/' . $this -> name . $dir;
			echo 'Creating: ' .  $path . "\n";
			mkdir( $path , 0777 , true );
		}
	}

	public function undo(){
		foreach ( array_reverse( $this -> dirs ) as $dir ){
			$path = GIMIMVC_ROOT . 'applications/' . $this -> name . $dir;
			echo 'Removing: ' . $path . "\n";
			rmdir( $path );
		}
		#echo 'Removing: ' .  GIMIMVC_ROOT .'applications/' . $this -> name . "\n";
		#rmdir( GIMIMVC_ROOT . 'applications/' . $this -> name );
	}

}

?>
