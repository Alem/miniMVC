<?php

class Generator{

	public $args;

	public $views = array( 'index', 'form', 'table', 'thumbnails', 'show' );

	public function process($args){
		

		if( isset($args['scaffold']) )
			$this -> scaffold = $args['scaffold'];
		else
			$this -> scaffold = DEFAULT_SCAFFOLD;

		require_once( SCAFFOLD_DIR . $this -> scaffold . 'controller.php');
		require_once( SCAFFOLD_DIR . $this -> scaffold . 'model.php');
		require_once( SCAFFOLD_DIR . $this -> scaffold . 'view.php' );

		if( isset($args['mvc']) ||  isset($args['m'])  ||  isset($args['v'])  || isset($args['c'])   ){
			foreach ( $args as $arg => $value) {
				if ($arg == 'c' || $arg == 'm' || $arg == 'v' || $arg == 'mvc' ) 
					$this -> generate( $value, $arg );
			}
			echo "Generation Complete. \n";
		}

		if( isset($args['undo']) )
			$this -> undo($args['undo']);
		if( isset($args['redo']) )
			$this -> redo($args['redo']);
		if( isset($args['table']) )
			$this -> makeTable($args['table']);
		if( isset($args['undotable']) )
			$this -> deleteTable($args['undotable']);
		if( isset($args['opendb']) )
			Database::open() -> openDB( $args['opendb'] );
		if( isset($args['h']) or  isset($args['help']) or  empty( $args) )
			$this -> help();
		if( isset($args['link']) &&  isset($args['to'])  )
			$this -> linkTables( $args['link'], $args['to'] );
		if( isset($args['unlink']) &&  isset($args['to'])  )
			$this -> unlinkTables( $args['unlink'], $args['to'] );
	}


	public function create( $file, $data ){
		$handle = fopen($file,'w') or die ("Can't open file.");
		fwrite($handle,$data);
		fclose($handle);
	}


	public function generate( $name, $type ){

		if ( strpos( $type, 'c' ) !== false ){
			echo "Creating controller for $name...\n";
			$class = $name . 'Controller';

			$controller_scaffold = new Controller();
			$controller_scaffold  -> name = $name;
			$controller_scaffold  -> uname = ucwords ( $name );
			$template = $controller_scaffold -> scaffold();

			$path =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_CONTROLLER_PATH . $name . '.php';
			$this -> create($path, $template);
		}

		if ( strpos ( $type, 'm') !== false ) {
			echo "Creating model for $name...\n";

			$model_scaffold = new Model();
			$model_scaffold  -> name = $name;
			$model_scaffold  -> uname = ucwords ( $name );
			$template = $model_scaffold -> scaffold();

			$path =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_MODEL_PATH . $name . '.php';
			$this -> create($path, $template);
		}

		if ( strpos( $type, 'v') !== false ){
			echo "Creating view for $name...\n";
			$dir =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_VIEW_PATH . $name . '/';
			mkdir( $dir , 0755 ) or die("Couldn't create directory");

			$view_scaffold = new View();
			$view_scaffold  -> name = $name;
			$view_scaffold  -> uname = ucwords ( $name );

			foreach ($this -> views as $view)
				$this -> create($dir . $view . '.php' , $view_scaffold -> scaffold( $view ) );
		}

		echo "Completed \n";
	}


	public function undo( $name ){
		$paths[] =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_CONTROLLER_PATH . $name . '.php';
		$paths[] =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_MODEL_PATH. $name . '.php';
		foreach ($this -> views as $view)
			$paths[] =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_VIEW_PATH . $name . '/' . $view . '.php';
		$dir =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_VIEW_PATH . $name;
		foreach ($paths as $path){
			unlink($path);
			echo "Removed $path \n";
		}
		rmdir($dir);
		echo "Removed $name directory\n";
	}

	public function redo( $name ) {
		Database::open() -> get_columns( $name );
		$this -> undo ( $name );
		$this -> generate( $name, 'mvc');
	}

	public function makeTable($name){
		$names = $name . 's';
		Database::open() -> query = "create table $names ( id integer not null primary key auto_increment, $name varchar(128) not null );";
		Database::open() -> run();
		echo "Created $name table!\n";
	}

	public function linkTables( $table, $foreign_table ){
		$table = $table . 's';
		#ADD CONSTRAINT {$foreign_table}_id 
		$sql = <<<SQL
		ALTER TABLE $table
			ADD COLUMN {$foreign_table}_id integer(11) UNSIGNED,
			ADD FOREIGN KEY ( {$foreign_table}_id )
			REFERENCES  {$foreign_table}s(id)
SQL;
		Database::open() -> query = $sql;
		Database::open() -> run();

	}

	public function unlinkTables( $table, $foreign_table ){
		$table = $table . 's';
		$sql = <<<SQL
		ALTER TABLE $table
			DROP FOREIGN KEY {$foreign_table}_id,
			DROP {$foreign_table}_id 
SQL;
		Database::open() -> query = $sql;
		Database::open() -> run();
	}

	public function deleteTable($name){
		if( !empty($name) ){
			Database::open() -> query = "drop table $name".'s'; 
			Database::open() -> run();
			echo "Deleted $name table! \n";
		}
	}

	public function help(){

		$help = <<<HELP

	NAME
		gimiMVC - An MVC scaffold generator and Database management tool for miniMVC
	
	DESCRIPTION
		gimiMVC allows the generation of MVC scaffolds within the application directory specified
		in the applications config/ file ( ie. app/default.php ). It also allows instant database
		management using the current settings in config/database.php.

	USAGE
		./gimiMVC [option] [name]	
	
	OPTIONS
		Scaffold Generation:
		-m "blah"		Create model for blah.
		-c "blah"		Create controller for blah.
		-v "blah"		Create view for blah.
		--mvc "blah"		Create model,view and controller for blah.
		--table "blah"		Generate table and column for blah.
		--scaffold "name"	Identify the scaffold to use. ( Default is specified in config/generator.php )

		Update and Undo:
		--redo "blah"		Regenerate MVC scaffold for blah. 
		--undo "blah"		Delete MVC scaffold for blah. 
		--undotable "blah"	Delete database and column for blah. 

		Model Linking:
		--link X 		Allows linking of X's table to another via foreign key id reference. Requires --to
		--to Y 			Links X to Y by creating Y foreign ID column in X's table.
		--unlink X 		Removes linking of X's table to another by dropping foreign key + column. Requires --to

		Instant Query:
		--opendb '<SQL QUERY>'	Provides one-line execution of SQL queries using config/database.php settings.

		Help:
		-h or --help 		Displays this help text.

HELP;
			echo $help;
	}

}

?>
