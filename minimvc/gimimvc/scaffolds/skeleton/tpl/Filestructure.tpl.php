<?php

class FileStructure extends Scaffold
{

	public $dirs = array(
		'/data/', 	'/logs/',	'/modules/',
		'/libraries/', '/temp/', 	'/docs/',
	);

	public function generate()
	{
		$path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name;
		foreach ( $this->dirs as $dir )
		{
			$dir_path = $path . $dir;
			echo 'Creating: ' .  $dir_path . "\n";
			mkdir( $dir_path , 0755 , true );
		}
		$umask_backup = umask( 0 );
		chmod( $path . '/logs/', 0777 );
		umask( $umask_backup );
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
