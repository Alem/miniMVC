<?php

class View extends Template
{

	public $views = array('index', 'about');

	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> root_path = GIMIMVC_ROOT . 'applications/' . $this -> name . '/views/';
		$this -> fileCache() -> path = $this -> root_path . 'content/main/';
	}


	public function generate()
	{
		mkdir( $this -> fileCache() -> path , 0777 , true );
		foreach ( $this -> views as $view )
			$this -> fileCache() -> create( $this -> scaffold( $view ), $view );
	}


	public function undo()
	{
		foreach ( $this -> views as $view )
			$this -> fileCache() -> clear( $view );
		rmdir ( $this -> fileCache() -> path );
		rmdir ( $this -> root_path . 'content/' );
		rmdir ( $this -> root_path );
	}

	public function scaffold( $type ) 
	{

		$index = <<<INDEX
<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
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
<?php echo SITE_NAME ?> is ...
</p>
Contact <a href = "mailto:<?php echo SITE_EMAIL ?>"> <?php echo SITE_EMAIL ?></a>...
</p>
ABOUT;

	}


}

?>

