<?php

class Processor{

	function __construct(){
	}


	function mvc($args){
		foreach ( $args as $arg => $value) {
			if ($arg == 'c' || $arg == 'm' || $arg == 'v') 
				$mvc[$arg] = $value; 
			elseif ($arg =='mvc')
				foreach( array('m','v','c') as $value)
					$mvc[$value] = $args[$arg];
		}
		return $mvc;
	}


	function help(){

		$help = <<<HELP

	miniMVC.php - Automatic MVC scaffold generator for miniMVC
	usage: ./miniMVC.php [option] [name]	

	Commands: 
	--mvc "blah"		: Create model,view and controller for blah.
	-m "blah"		: Create model for blah.
	-c "blah"		: Create controller for blah.
	-v "blah"		: Create view for blah.

	--undo "blah"		: Delete MVC scaffold for blah. 
	--redo "blah"		: Regenerate MVC scaffold for blah. 

	--opendb '<SQL QUERY>'	: Provides one-line execution of SQL queries using config/databse.php settings.

	--table "blah"		: Generate table and column for blah.
				 * Requires database settings to be configured in config.php.
	--undotable "blah"	: Delete database and column for blah. 
				 * Requires database settings to be configured in config.php.
				 
	--link X 		: Allows linking of X's table to another via foreign key id reference. Requires --to
	--to Y 			: Links X to Y by creating Y foreign ID column in X's table.
	--unlink X 		: Removes linking of X's table to another by dropping foreign key + column. Requires --to

	-h or --help 		: Displays this help text.

HELP;
		echo $help;
	}


	function execute($args){

		$Generator = new Generator();
		$Generator -> args = $args;

		if( isset($args['mvc']) ||  isset($args['m'])  ||  isset($args['v'])  || isset($args['c'])   ){

			$mvc = $this -> mvc($args);

			foreach ( $mvc as $type => $value) {
				$Generator -> generate($value, $type);
			}

			echo "Generation Complete. \n";
		}

		if( isset($args['undo']) ){
			$Generator -> undo($args['undo']);
		}

		if( isset($args['redo']) ){
			$Generator -> redo($args['redo']);
		}


		if( isset($args['table']) ){
			$Generator -> makeTable($args['table']);
		}

		if( isset($args['undotable']) ){
			$Generator -> deleteTable($args['undotable']);
		}

		if( isset($args['opendb']) ){
			Database::open() -> openDB( $args['opendb'] );
		}

		if( isset($args['h']) or  isset($args['help']) or  empty( $args) ){
			$this -> help();
		}

		if( isset($args['link']) &&  isset($args['to'])  ){
			$Generator -> linkTables( $args['link'], $args['to'] );
		}

		if( isset($args['unlink']) &&  isset($args['to'])  ){
			$Generator -> unlinkTables( $args['unlink'], $args['to'] );
		}

	}
}


?>
