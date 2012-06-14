<?php

class Model extends Scaffold
{

	public $undo_directory = true;

	public function initialize( )
	{
		$path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . '/models/';
		$this->file( 'Main', $path );
	}

	public function getContent()
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

