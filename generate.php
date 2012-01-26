#!/usr/bin/php
<?
require_once('config.php');


// MVC TEMPLATE GENERATION FUNCTION
// controller()
// model()
// view()

function controller($name) {

	$crud = <<<CRUD

	function post(){
		\$item = \$_POST['item'];
		\$this -> model -> insert(\$item);
		\$this -> model -> data['content'] = "\$item Added.";
		\$this -> show();
	}

	function add(\$item){
		\$this -> model -> insert(\$item);
		\$this -> model -> data['content'] = "\$item Added.";
		\$this -> show();
	}

	function del(\$id){
		\$this -> model -> remove(\$id);
		\$this -> model -> data['content'] = "\$id Deleted.";
		\$this -> show();
	}

	function set(\$old, \$new, \$column_old = null, \$column_new = null){
		\$this -> model -> update( \$old, \$new, \$column_old, \$column_new);
		\$this -> model -> data['content'] = "\$old changed to \$new.";
		\$this -> show();
	}

	function show(){
		\$query_result = \$this -> model -> select ('*', 'id', array( 'col'=>'id','sort' => 'DESC') );
		\$this -> model -> data["show"] = \$query_result;
		\$this -> useView();
	}

CRUD;

	$controller = <<<CONT
<?php

class $name extends Controller{
	function __construct(){
		// Is assigned name,classname,filename, and model after instantiation.
	}

	function index(){
		\$this -> useView(); 
	}

	$crud
}
?>
CONT;

	return $controller;
}


function model($name,$l_name){
	$model =  <<<MODEL
<?php

class $name extends Model{

	function __construct(){
		parent::__construct();
		\$data['content'] = "This is test controller model data<br/>";
		\$data['l_sidebar'] = "<a href ='?$l_name'>Index </a><br/><br/>";
		\$data['r_top_sidebar'] =  "Placeholder text";
		\$data['r_bot_sidebar'] = "Another Placeholder";
		\$this -> data = \$data;
	}

}
?>
MODEL;
	return $model;
}


function view($name,$u_name) {
	$view = <<<VIEW
<h1> $u_name View </h1>
<h2> Welcome to <?php echo SITE_NAME; ?> </h2>
<p>
	This data below has been passed to this view 
	by its controller and was generated/retrieved by its model.
</p>

<h3>ADD AN ITEM: </h3> 
<form action = "?$name/post/" method="post">
<input id = "item" name = "item" type="text" />
<input type = "submit" value = "Add"/>
</form>

<?php	if( isset( \$data['show']) ):	?>
<h2>List</h2>
<ul>
<?php foreach( \$data['show'] as \$row) :	?>
	<li>
	<?php foreach( \$row as \$column => \$value) :	?>
	<b> <?php echo \$column; ?>:  <?php echo \$value; ?> </b>
	<?php endforeach; ?>
	<br/>
	<a href='?$name/del/<?php echo \$row['id'] ?>/'> Delete</a></p> 
	</li>
<?php endforeach; ?>
</ul>

<?php else: ?>
<p>
	The database is empty. Try to adding items.
</p>
<?php endif; ?>

<?php	echo \$data['content'];	?>
VIEW;
	return $view;
}


// DATABASE MODIFYING FUNCTIONS
// 
//  db_connect()
//  db_disconnect()
//  makeTable()
//  deleteTable()
//  openDB()

function db_connect(){
	$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
	mysql_select_db(DB_DATABASE, $link);
}

function db_disconnect(){
	$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
	mysql_close($link);
}

function makeTable($name){
	$names = $name . 's';
	$query = "create table $names (	id integer not null primary key auto_increment, $name varchar(128) not null );";
	db_connect();
	mysql_query($query) or die("Query failed: $query \nReason: " . mysql_error() ." \n" );
	echo "Created $name table!\n";
	db_disconnect();
}

function deleteTable($name){
	if( ($name !='') && ($name != '*') ){
		$query = "drop table $name".'s'; 
		db_connect();
		mysql_query($query) or die("Query failed: $query \nReason: " . mysql_error() ." \n" );
		db_disconnect();
		echo "Deleted $name table! \n";
	}
}

function openDB(){
	db_connect();
	echo "Connected to database: " . DB_DATABASE . "\n";
	echo "Enter a Query: \n";
	$query = fgets(STDIN);	
	mysql_query($query) or die("Query failed: $query \nReason: " . mysql_error() ." \n" );
	db_disconnect();
	echo "Query sucessfully executed.\n";
}

// FILE CREATION FUNCTIONS
//
// create()
// generate()
// undo()

function create($file,$data){
	$handle = fopen($file,'w') or die ("Can't open file.");
	fwrite($handle,$data);
	fclose($handle);
}

function generate($name,$type){

	if ($type == 'c'){
		echo "Creating controller for $name...\n";
		$class = $name . 'Controller';
		$template = controller(ucwords($class));
		$path =  SERVER_ROOT . '/controllers/' . $name . '.php';
	}

	if ($type == 'm'){
		echo "Creating model for $name...\n";
		$template = model(ucwords($name),$name);
		$path =  SERVER_ROOT . '/models/' . $name . '.php';
	}

	if ($type == 'v'){
		echo "Creating view for $name...\n";
		$template = view( $name, ucwords($name) );
		$dir =  SERVER_ROOT . '/views/' . $name . '/';
		mkdir( $dir , 0755 ) or die("Couldn't create directory");
		$path =  $dir . 'index.php';
	}

	create($path, $template);
	echo "Completed \n";
}

function undo($name){
	$paths['c'] =  SERVER_ROOT . '/controllers/' . $name . '.php';
	$paths['m'] =  SERVER_ROOT . '/models/' . $name . '.php';
	$paths['v'] =  SERVER_ROOT . '/views/' . $name . '/' . 'index.php';
	$dir =  SERVER_ROOT . '/views/' . $name;
	foreach ($paths as $path){
		unlink($path);
		echo "Removed $path \n";
	}
	rmdir($dir);
	echo "Removed $name directory\n";
}

// ARGUMENT PROCESSING  
// mvc()

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


// PRINT MESSAGES
// 
// printHelp()

function printHelp(){
	$help = <<<HELP
	Generate.php - Automatic MVC scaffold generator for miniMVC
	usage: ./generate.php [option] [name]	

	Commands: 
	--mvc "blah"		: Create model,view and controller for blah.
	-m "blah"		: Create model for blah.
	-c "blah"		: Create controller for blah.
	-v "blah"		: Create view for blah.
	--table "blah"		: Generate table and column for blah.
				 * Requires database settings to be configured in config.php.
	--undo "blah"		: Delete MVC scaffold for blah. 
	--undotable "blah"	: Delete database and column for blah. 
				 * Requires database settings to be configured in config.php.
	-h or --help 		: Displays this help text.


HELP;
	echo $help;
}

// Running Script
//

$args = getopt("c:m:v:p:u:h", array('mvc:','undo:','table:','undotable:','crud','help','opendb'));

if( isset($args['mvc']) ||  isset($args['m'])  ||  isset($args['v'])  || isset($args['c'])   ){
	$mvc = mvc($args);
	foreach ( $mvc as $type => $value) {
		generate($value,$type);
	}
	echo "Generation Complete. \n";
}
if( isset($args['undo']) ){
	undo($args['undo']);
}

if( isset($args['table']) ){
	makeTable($args['table']);
}

if( isset($args['undotable']) ){
	deleteTable($args['undotable']);
}

if( isset($args['opendb']) ){
	openDB();
}

if( isset($args['h']) or  isset($args['help'])  ){
	printHelp();
}

?>
