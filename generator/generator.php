<?php


class Generator{

	public $views = array( 'index','form','table','thumbnails','show');

	function create($file,$data){
		$handle = fopen($file,'w') or die ("Can't open file.");
		fwrite($handle,$data);
		fclose($handle);
	}

	function generate($name,$type){

		if ( strpos( $type, 'c' ) !== false ){
			echo "Creating controller for $name...\n";
			$class = $name . 'Controller';

			$controller_scaffold = new Controller();
			$template = $controller_scaffold -> scaffold( $name );

			$path =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_CONTROLLER_PATH . $name . '.php';
			$this -> create($path, $template);
		}

		if ( strpos ( $type, 'm') !== false ) {
			echo "Creating model for $name...\n";

			$model_scaffold = new Model();
			$template = $model_scaffold -> scaffold($name);

			$path =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_MODEL_PATH . $name . '.php';
			$this -> create($path, $template);
		}

		if ( strpos($type, 'v') !== false ){
			echo "Creating view for $name...\n";
			$dir =  SERVER_ROOT . DEFAULT_APPLICATION_PATH . DEFAULT_VIEW_PATH . $name . '/';
			mkdir( $dir , 0755 ) or die("Couldn't create directory");

			$view_scaffold = new View();

			foreach ($this -> views as $view)
				$this -> create($dir . $view . '.php' , $view_scaffold -> scaffold( $name , $view ) );
		}

		echo "Completed \n";
	}


	function undo($name){
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

	function redo( $name) {
		Database::open() -> get_columns( $name );
		$this -> undo ( $name );
		$this -> generate( $name, 'mvc');
	}

	function makeTable($name){
		$names = $name . 's';
		Database::open() -> query = "create table $names (	id integer not null primary key auto_increment, $name varchar(128) not null );";
		Database::open() -> run();
		echo "Created $name table!\n";
	}

	function linkTables($table, $foreign_table){
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

	function unlinkTables($table,$foreign_table){
		$table = $table . 's';
		$sql = <<<SQL
		ALTER TABLE $table
			DROP FOREIGN KEY {$foreign_table}_id,
			DROP {$foreign_table}_id 
SQL;
		Database::open() -> query = $sql;
		Database::open() -> run();
	}

	function deleteTable($name){
		if( !empty($name) ){
			Database::open() -> query = "drop table $name".'s'; 
			Database::open() -> run();
			echo "Deleted $name table! \n";
		}
	}

}

?>
