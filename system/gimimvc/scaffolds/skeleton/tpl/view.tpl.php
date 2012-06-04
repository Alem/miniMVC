<?php

class View extends Scaffold
{

	public $views = array('index', 'about');

	public $errors = array('404');

	public $undo_directory = true;

	public function initialize()
	{
		$this->root_path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . '/views/';
		$this->content_path = $this->root_path . 'content/';
		$this->shared_path = $this->root_path . 'shared/';

		$this->error_path = $this->root_path . 'error/';
		$this->file( $this->errors, $this->error_path );

		$this->main_content_path = $this->content_path . 'main/';
		$this->file( $this->views, $this->main_content_path );

	}

	public function generate()
	{
		mkdir( $this->content_path, 0777 , true );
		mkdir( $this->shared_path, 0777 , true );
		parent::generate();
	}

	public function undo()
	{

		parent::undo();
		rmdir( $this->content_path );
		rmdir( $this->shared_path );
		rmdir( $this->root_path );
	}

	public function getContent( $type )
	{

		$index = <<<INDEX
<h1><?php echo \$data['config']['site_name']; ?> <small><?php echo \$data['config']['site_tag']; ?></small></h1>
<hr>
<br/>
<p>
Content Goes Here.
</p>
INDEX;

		$about = <<<ABOUT
<h1> About</h1>
<hr>
<br/>
<p>
<?php echo \$data['config']['site_name']; ?> is ...
</p>
Contact <a href = "mailto:<?php echo \$data['config']['site_email'] ?>"> <?php echo \$data['config']['site_email'] ?></a>...
</p>
ABOUT;
		$notfound = <<<notfound
<h1> 404 - Content Not Found </h1>
<p> Sorry bud. </p>
notfound;
		switch( $type )
		{
		case 'index':
			return $index;
			break;
		case 'about':
			return $about;
			break;
		case '404':
			return $notfound;
			break;
		}
	}

}

?>

