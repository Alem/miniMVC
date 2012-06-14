<?php

class Controller extends Scaffold{

	public $undo_directory = true;

	public function initialize()
	{
		$path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . '/controllers/';
		$this->file( 'MainController', $path );
	}

	public function getContent()
	{

		$controller = <<<CONTROLLER
<?php

class MainController extends Controller{


	// index() - Loads default 'index' view

	public function actionIndex()
	{
		\$config = new Config();
		\$session = new Session();
		\$this->content( 'index' )
			->render( array(
			'config' => \$config->fetch('application'),
			'model'  => \$this->model()->data,
			'session'  => \$session->data,
		));
	}

	// about() - Run of the mill 'about' page

	public function actionAbout()
	{
		\$config = new Config();
		\$session = new Session();
		\$this->content( 'about' )
			->render( array(
			'config' => \$config->fetch('application'),
			'model'  => \$this->model()->data,
			'session'  => \$session->data,
		));
	}

}
?>

CONTROLLER;
		return $controller;

	}

}

?>
