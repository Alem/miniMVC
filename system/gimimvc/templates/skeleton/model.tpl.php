<?php

class Model extends Template
{

	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> fileCache() -> path = GIMIMVC_ROOT . 'applications/' . $this -> name . '/models/';
		$this -> fileCache() -> id   = 'main';
	}


	public function generate()
	{
		echo 'Creating: ' .  $this -> fileCache() -> path . "\n";
		mkdir( $this -> fileCache() -> path , 0777 , true );
		echo 'Creating model template' . "\n";
		$this -> fileCache() -> create( $this -> scaffold() );
	}


	public function undo()
	{
		echo 'Removing model template' . "\n";
		$this -> fileCache() -> clear();
		echo 'Removing: ' .  $this -> fileCache() -> path . "\n";
		rmdir( $this -> fileCache() -> path );
	}


	public function scaffold()
	{

		$model = <<<MODEL
<?php

class Main extends Model
{

	function __construct()
	{
		parent::__construct();
	}
}

?>
MODEL;
		return $model;

	}

}

?>

