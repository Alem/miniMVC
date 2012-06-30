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
			'model'  => \$this->model()->get(),
			'session'  => \$session->get(),
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
			'model'  => \$this->model()->get(),
			'session'  => \$session->get(),
		));
	}

	// 404() - Custom 404 Page
	// Simply allows the default 404 message to display in the default template
	public function error_404()
	{
		\$config = new Config();
		\$session = new Session();
		\$this->error( '404' )
			->render( array(
			'config' => \$config->fetch('application'),
			'session'  => \$session->get(),
		));
	}


}
?>

CONTROLLER;
		return $controller;

	}

}

?>
