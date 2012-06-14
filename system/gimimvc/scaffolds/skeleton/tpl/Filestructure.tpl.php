<?php

class FileStructure extends Scaffold
{

	public $dirs = array(
		'/data/', 	'/logs/',	'/modules/',
		'/libraries/', '/temp/', 	'/docs/',
	);

	public function generate()
	{
		foreach ( $this->dirs as $dir )
		{
			$path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . $dir;
			echo 'Creating: ' .  $path . "\n";
			mkdir( $path , 0777 , true );
		}
	}

	public function undo()
	{
		foreach ( $this->dirs as $dir )
		{
			$path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . $dir;
			echo 'Removing: ' . $path . "\n";
			rmdir( $path );
		}
		echo 'Removing: ' .  GIMIMVC_ROOT .$this->config['apps_path'] . $this->name . "\n";
		rmdir( GIMIMVC_ROOT . $this->config['apps_path'] . $this->name );
	}

}

?>
