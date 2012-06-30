<?php
/**
 * Processor class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The processor class recieves and routes miniMVC arguments.
 * It loads the appropriate classes( queryTool, scaffold component classes)
 * and runs the appropriate method.
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.gimimvc
 */
class Processor{

	/**
	 * The command line arguments recieved by gimiMVC
	 * @var array
	 */
	public $args;

	/**
	 * __construct() - Assigns received command line arguments to the $args property
	 *
	 * @param array $args  The command line arguments recieved by gimiMVC
	 */
	public function __construct( $args )
	{
		$this->args = $args;
	}

	/**
	 * process() - Processes command line arguments and executes appropriate methods.
	 *
	 * Commands fall into three catagories:
	 * 	1. Query Tool Commands: Use the QueryTool object
	 * 	2. Scaffolding Commands: Use loaded Scaffold derived tpl.php classes from scaffolds.
	 * 	3. General Commands: The help command.
	 */
	public function process()
	{
		$this->loadCoreConfig();

		$this->queryCommands();

		$this->scaffoldCommands();

		$this->generalCommands();
	}

	/**
	 * queryCommands() - Processes query commands
	 */
	public function queryCommands()
	{
		if( isset($this->args['table']) )
			$this->queryTool()->makeTable($this->args['table']);

		if( isset($this->args['undotable']) )
			$this->queryTool()->deleteTable($this->args['undotable']);

		if( isset($this->args['link']) &&  isset($this->args['to'])  )
			$this->queryTool()->linkTables( $this->args['link'], $this->args['to'] );

		if( isset($this->args['unlink']) &&  isset($this->args['to'])  )
			$this->queryTool()->unlinkTables( $this->args['unlink'], $this->args['to'] );

		if( isset($this->args['readschema']) )
			$this->queryTool()->importData( $this->args['readschema'] );

		if( isset($this->args['sql']) )
			$this->queryTool()->openDB( $this->args['sql'] );

		if( isset($this->args['q']) )
			$this->queryTool()->openDB( $this->args['q'] );
	}

	/**
	 * scaffoldCommands() - Processes scaffolding commands
	 */
	public function scaffoldCommands()
	{
		$scaffold_args = array( 'generate', 'redo', 'undo' );
		foreach( $scaffold_args as $scaffold_method )
		{
			if( isset( $this->args[ $scaffold_method ] ) )
			{
				echo ucwords($scaffold_method);
				echo ' method will be executed for each loaded component'."\n";
				foreach( $this->scaffolds()  as $scaffold )
					$scaffold->$scaffold_method();
			}
		}
	}

	/**
	 * generalCommands() - Processes general commands
	 *
	 * The x,run arguments result in execution of the
	 * application via cli
	 *
	 */
	public function generalCommands()
	{

		if(
			isset($this->args['h'])
			|| isset($this->args['help'])
			|| empty( $this->args)
		)
		{
			$this->help();
		}

		if(
			isset($this->args['x'])
			|| isset($this->args['run'])
		)
		{
			$uri = null;

			if( !empty($this->args['x']))
				$uri =  $this->args['x'];

			elseif( !empty($this->args['run']))
				$uri = $this->args['run'];

			$_SERVER['argv'][1] = $uri;
			require SERVER_ROOT . DEFAULT_SYSTEM_PATH . 'boot.php';
			echo "\n";
		}
	}


	/**
	 * loadCoreConfig() - Loads an application's core configuration file.
	 *
	 * Uses full path from '--useconfig' argument
	 * or uses the '-a' argument to construct the typical convention-compliant application
	 * configuration path.
	 *
	 * @return bool 	True if application config successfully included, otherwise false.
	 */
	public function loadCoreConfig()
	{
		if( isset($this->app_config_set ) )
			return true;

		if( !empty( $this->args['u'] ) )
			$path = GIMIMVC_ROOT . $this->args['u'];

		elseif( !empty( $this->args['useconfig'] ) )
			$path = GIMIMVC_ROOT . $this->args['useconfig'];

		elseif( 
			isset( $this->args['a'] ) 
			#&& ( isset( $this->args['useconfig'] ) || isset( $this->args['u'] ) ) 
		)
		{
			$path =  GIMIMVC_ROOT . 'applications/' . $this->args['a'] . '/config/core.php';
			#echo 'Assuming application config path: ' . $path . "\n";
		}

		if( isset( $path ) )
		{
			if( file_exists( $path ) )
			{
				require_once( $path );
				$this->app_config_set = true;
				#echo "Loaded application config: $path \n";
				return true;
			}
			else
			{
				echo "The application configuration file '$path' could be found." . "\n";
				return false;
			}
		}
	}


	/**
	 * loadScaffold() - Loads the scaffold configuration file.
	 *
	 * Sets scaffold name and loads the scaffold configuration file
	 * 'config.php' found in each scaffold directory.
	 *
	 * @return array $config 	The scaffold configuration array.
	 */
	public function loadScaffold()
	{
		if( isset($this->args['scaffold']) )
			$scaffold['name'] = $this->args['scaffold'];
		else
			$scaffold['name'] = DEFAULT_SCAFFOLD;

		$scaffold['config'] = require_once( SCAFFOLD_DIR . $scaffold['name'] . '/' . 'Config.php' );

		if( isset( $scaffold ) )
		{
			echo 'Loaded configuration file for the scaffold: \'' . $scaffold['name'] . "'\n";
			return $scaffold;
		}
		else
			return null;
	}

	/**
	 * scaffolds() - Loads the scaffolds referencing the scaffold configuration file.
	 *
	 * @return array 	An array containining the instantied scaffold objects.
	 */
	public function scaffolds()
	{
		$scaffold = $this->loadScaffold();

		if( isset( $this->args['component'] ) )
			$components = explode( ',', $this->args['component'] );
		else
		{
			$components = $scaffold['config']['components'];
		}

		if( isset( $this->args['unit'] ) )
			$unit = $this->args['unit'];
		elseif( isset( $this->args['a'] ) )
			$unit = $this->args['a'];
		else
			$unit = null;

		foreach( $components as $component )
		{
			require_once( SCAFFOLD_DIR . $scaffold['name'] . '/' . $scaffold['config']['scaffold_dir'] . $component . '.tpl.php' );
			$loaded_components[ $component ] = new $component( $unit, $scaffold['config'] );
			echo 'Loaded scaffold component: ' . $component . "\n";
		}

		return $loaded_components;
	}

	/**
	 * queryTool() - Grants access to single instance of QueryTool object.
	 *
	 * Loads the application configuration to ensure database information
	 * is available to QueryTool.
	 *
	 * @return QueryTool  Instance of QueryTool
	 */
	public function queryTool()
	{
		if( !isset( $this->query_tool ) )
		{
			$database = new Database();
			$dbquery  = new DbQuery( $database );
			$this->query_tool = new QueryTool( $dbquery );
		}
		return $this->query_tool;
	}

	/**
	 * help() - Prints help message.
	 */
	public function help()
	{

		$help = <<<HELP

 NAME
    gimiMVC - A command-line multi-tool for miniMVC

 DESCRIPTION
    gimiMVC allows the generation of MVC scaffolds, or simple command
    line execution of the application specified. It also provides 
    database management functions using the 'default' settings
    in config/database.php.

 USAGE
    gimiMVC -a [application] [option]

 OPTIONS
    
    Required:
    -a [application]          Sets the application to use. 

    General:
    --useconfig, -u [PATH]    Manually set application config path
    --run,  -x [URI]          Execute the application (URI optional).
    -h, --help                Displays this help text.

    Scaffolding:
    --unit                    Name of the unit.
    --generate                Generate scaffold.
    --redo                    Regenerate scaffold.
    --undo                    Delete scaffold.
    --component "model"       Override scaffold default components 
                              and load the specified component.
    --scaffold "name"         Set the scaffold to use. Default is 
                              specified in config/generator.php.

    Database Manipulation:
    --table "blah"            Generate table for a unit.
    --undotable "blah"        Delete database for a unit.
    --link X                  Allows linking of X's table to another
                              via foreign key id reference. 
                              Requires --to.
    --unlink X                Removes linking of X's table to another
                              by dropping foreign key + column. 
                              Requires --to.
    --to Y                    Establishes endpoint for link/unlink
    --sql, -q '<QUERY>'       Provides CLI execution of SQL queries.
                              Uses config/database.php 'default' values.


HELP;
		echo $help;
	}

}

?>
