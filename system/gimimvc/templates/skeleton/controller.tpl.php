<?php

class Controller extends Template{

	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> fileCache() -> path = GIMIMVC_ROOT . 'applications/' . $this -> name . '/controllers/';
		$this -> fileCache() -> id   = 'main';
	}


	public function generate()
	{
		echo 'Creating: ' .  $this -> fileCache() -> path . "\n";
		mkdir( $this -> fileCache() -> path , 0777 , true );
		echo 'Creating controller template' . "\n";
		$this -> fileCache() -> create( $this -> scaffold() );
	}


	public function undo()
	{
		echo 'Removing controller template' . "\n";
		$this -> fileCache() -> clear();
		echo 'Removing: ' .  $this -> fileCache() -> path . "\n";
		rmdir( $this -> fileCache() -> path );
	}


	public function scaffold()
	{

		$controller = <<<CONTROLLER
<?php

class MainController extends Controller{


	// index() - Loads default 'index' view

	function actionIndex()
	{
		\$this -> view();
	}

	// about() - Run of the mill 'about' page

	function actionAbout()
	{
		\$this -> view('about');
	}

}
?>

CONTROLLER;
		return $controller;

	}

}

?>
