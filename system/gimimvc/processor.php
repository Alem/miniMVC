<?php
/**
 * Processor class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The processor class recieves and routes miniMVC arguments.
 * It loads the appropriate classes ( queryTool, template component classes)
 * and runs the appropriate method.
 *
 */
class Processor{

	public $args;

	public function __construct( $args ){
		$this -> args = $args;
	}

	public function process(){

		// QueryTool Commands
		if( isset($this -> args['table']) )
			$this -> queryTool() -> makeTable($this -> args['table']);
		if( isset($this -> args['undotable']) )
			$this -> queryTool() -> deleteTable($this -> args['undotable']);
		if( isset($this -> args['link']) &&  isset($this -> args['to'])  )
			$this -> queryTool() -> linkTables( $this -> args['link'], $this -> args['to'] );
		if( isset($this -> args['unlink']) &&  isset($this -> args['to'])  )
			$this -> queryTool() -> unlinkTables( $this -> args['unlink'], $this -> args['to'] );
		if( isset($this -> args['opendb']) )
			$this -> queryTool() -> openDB( $this -> args['opendb'] );
	

		// Scaffolding Commands
		if( isset( $this -> args['generate'] ) ){
			echo 'Generate method will be executed for each loaded component' . "\n";
			foreach ( $this -> scaffolds()  as $scaffold )
				$scaffold -> generate();
		}
		if( isset( $this -> args['redo'] ) ){
			echo 'Redo method will be executed for each loaded component' . "\n";
			foreach ( $this -> scaffolds() as $scaffold )
				$scaffold -> redo();
		}
		if( isset( $this -> args['undo'] ) ){
			echo 'Undo method will be executed for each loaded component' . "\n";
			foreach ( $this -> scaffolds() as $scaffold )
				$scaffold -> undo();
		}

		// General Commands
		if( isset($this -> args['h']) or  isset($this -> args['help']) or  empty( $this -> args) )
			$this -> help();
	}


	/**
	 * Specifies application configuration file
	 * Uses full path from appconfig argument
	 * or uses app argument to construct probable config path.
	 */
	public function loadAppConfig(){
		if ( isset ($this -> app_config_set ) || !isset ( $this -> args['useconfig']) )
			return true;

		if( !empty($this -> args['useconfig'] ) )
			$path = GIMIMVC_ROOT . $this -> args['useconfig'];
		elseif ( isset( $this -> args['a'] ) ){
			$path =  'applications/' . $this -> args['a'] . '/config/app.php';
			echo 'Assuming application config path: ' . $path . "\n";
		}

		if ( file_exists( $path ) ){
			require_once( $path );
			$this -> app_config_set = true;
			echo "Loaded application config: $path \n";
			return true;
		}else{
			echo "The application configuration file '$path' could be found." . "\n";
			return false;
		}
	}

	public function loadScaffoldConfig(){
		
		if( isset($this -> args['scaffold']) )
			$this -> scaffold = $this -> args['scaffold'];
		else
			$this -> scaffold = DEFAULT_SCAFFOLD;

		$config = require_once( SCAFFOLD_DIR . $this -> scaffold . '/' . 'config.php' );

		if ( isset ( $config ) ){
			echo 'Loaded configuration file for the scaffold: ' . $this -> scaffold . "\n";
			return $config;
		}else
			return null;
	}

	public function scaffolds(){

		$this -> loadAppConfig();
		$config = $this -> loadScaffoldConfig();	

		if ( isset( $this -> args['component'] ) )
			$components = explode (  ',' , $this -> args['component']);
		else
			$components = $config['components'];

		if ( isset ( $this -> args['unit'] ) )
			$unit = $this -> args['unit'];
		elseif ( isset ( $this -> args['a'] ) )
			$unit = $this -> args['a'];
		else
			$unit = null;

		foreach( $components as $component ){
			require_once( SCAFFOLD_DIR . $this -> scaffold . '/' . $component . '.tpl.php' );
			$loaded_components[ $component ] = new $component( $unit );
			echo 'Loaded scaffold component: ' . $component . "\n";
		}
		return $loaded_components;
	}


	public function queryTool( ){
		$this -> loadAppConfig();

		if ( !isset( $this -> query_tool ) )
			$this -> query_tool = new QueryTool(); 
		return $this -> query_tool;
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
		General:
		-a 'appname' 		Sets the application name. Assumes conventions  to load the configuration file.
		--useconfig 'PATH'	Use application configuration file. 
					If the application does not follow typical conventions, --useconfig can be used to manually set the path.

		Scaffolding:
		--unit 			Name of the MVC unit
		--generate 		Generate MVC scaffold 
		--redo 			Regenerate MVC scaffold
		--undo 			Delete MVC scaffold 
		--component "model"	Override scaffold default components and load specified component.
		--scaffold "name"	Set the scaffold to use. ( Default is specified in config/generator.php )

		Table Creation/Deletion:
		--table "blah"		Generate table and column for blah.
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
