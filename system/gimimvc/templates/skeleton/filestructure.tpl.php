<?php

class FileStructure extends Template
{


	public $dirs = array( 
		'/data/', 	'/logs/',		'/modules/', 		
		'/libraries/', 
	);


	public function generate()
	{
		foreach ( $this -> dirs as $dir )
		{
			$path = GIMIMVC_ROOT . 'applications/' . $this -> name . $dir;
			echo 'Creating: ' .  $path . "\n";
			mkdir( $path , 0777 , true );
		}
	}

	public function undo()
	{
		foreach ( $this -> dirs as $dir )
		{
			$path = GIMIMVC_ROOT . 'applications/' . $this -> name . $dir;
			echo 'Removing: ' . $path . "\n";
			rmdir( $path );
		}
		echo 'Removing: ' .  GIMIMVC_ROOT .'applications/' . $this -> name . "\n";
		rmdir( GIMIMVC_ROOT . 'applications/' . $this -> name );
	}

}

?>
