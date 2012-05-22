<?php

class View extends Template
{

	public $views = array('index', 'about');

	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> root_path = GIMIMVC_ROOT . 'applications/' . $this -> name . '/views/';
		$this -> content_path = $this -> root_path . 'content/';
		$this -> shared_path = $this -> root_path . 'shared/';

		$this -> fileCache() -> path = $this -> content_path . 'main/';
	}


	public function generate()
	{
		mkdir( $this -> fileCache() -> path , 0777 , true );
		mkdir( $this -> shared_path, 0777 , true );
		foreach ( $this -> views as $view )
			$this -> fileCache() -> create( $this -> scaffold( $view ), $view );
	}


	public function undo()
	{
		foreach ( $this -> views as $view )
			$this -> fileCache() -> clear( $view );
		rmdir ( $this -> fileCache() -> path );

		rmdir ( $this -> content_path );
		rmdir ( $this -> shared_path );

		rmdir ( $this -> root_path );
	}

	public function scaffold( $type ) 
	{

		$index = <<<INDEX
<h1><?php echo \$data['site_name']; ?> <small><?php echo \$data['site_tag']; ?></small></h1>
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
<?php echo \$data['site_name']; ?> is ...
</p>
Contact <a href = "mailto:<?php echo \$data['site_email'] ?>"> <?php echo \$data['site_email'] ?></a>...
</p>
ABOUT;
		return $$type;

	}


}

?>

