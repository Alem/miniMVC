<?php
/**
 * Generator class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/** 
 * The Generator handles the generation of MVC scaffolds.
 *
 * @todo This system needs major improvements in terms of configurability
 */
class Generator{

	public $args;

	public function queryTool(){
		if ( !isset( $this -> query_tool ) )
			$this -> query_tool = new QueryTool(); 
		return $this -> query_tool;
	}

	public function process($args){


		if( isset($args['scaffold']) )
			$this -> scaffold = $args['scaffold'];
		else
			$this -> scaffold = DEFAULT_SCAFFOLD;

		require_once( SCAFFOLD_DIR . $this -> scaffold . 'controller.php');
		require_once( SCAFFOLD_DIR . $this -> scaffold . 'model.php');
		require_once( SCAFFOLD_DIR . $this -> scaffold . 'view.php' );

		if( isset($args['table']) )
			$this -> queryTool() -> makeTable($args['table']);

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
		if( isset($args['undotable']) )
			$this -> queryTool() -> deleteTable($args['undotable']);
		if( isset($args['opendb']) )
			$this -> queryTool() -> openDB( $args['opendb'] );
		if( isset($args['h']) or  isset($args['help']) or  empty( $args) )
			$this -> help();
		if( isset($args['link']) &&  isset($args['to'])  )
			$this -> queryTool() -> linkTables( $args['link'], $args['to'] );
		if( isset($args['unlink']) &&  isset($args['to'])  )
			$this -> queryTool() -> unlinkTables( $args['unlink'], $args['to'] );
	}


	public function create( $file, $data ){
		$handle = fopen($file,'w') or die ("Can't open file.");
		fwrite($handle,$data);
		fclose($handle);
	}


	public function generate( $name, $type ){

		if ( strpos( $type, 'c' ) !== false ){
			echo "Creating controller for $name...\n";

			$controller_scaffold = new Controller( $name );
			$template = $controller_scaffold -> scaffold();

			$path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CONTROLLER_PATH . $name . '.php';
			$this -> create($path, $template);
		}

		if ( strpos ( $type, 'm') !== false ) {
			echo "Creating model for $name...\n";

			$model_scaffold = new Model( $name );
			$template = $model_scaffold -> scaffold();

			$path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODEL_PATH . $name . '.php';
			$this -> create($path, $template);
		}

		if ( strpos( $type, 'v') !== false ){
			echo "Creating view for $name...\n";
			$dir =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_CONTENT_PATH . $name . '/';
			mkdir( $dir , 0755 ) or die("Couldn't create directory");

			$view_scaffold = new View( $name );

			foreach ($view_scaffold -> views as $view)
				$this -> create($dir . $view . '.php' , $view_scaffold -> scaffold( $view ) );
		}

		echo "Completed \n";
	}


	public function undo( $name ){
		$paths[] =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CONTROLLER_PATH . $name . '.php';
		$paths[] =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODEL_PATH. $name . '.php';

		$view_scaffold = new View( $name );
		foreach ($view_scaffold -> views as $view)
			$paths[] =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_CONTENT_PATH . $name . '/' . $view . '.php';
		$dir =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_CONTENT_PATH . $name;
		foreach ($paths as $path){
			unlink($path);
			echo "Removed $path \n";
		}
		rmdir($dir);
		echo "Removed $name directory\n";
	}

	public function redo( $name ) {
		$this -> queryTool() -> getFormattedColumns( $name );
		$this -> undo ( $name );
		$this -> generate( $name, 'mvc');
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
		gimiMVC [option] [name]	
	
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
