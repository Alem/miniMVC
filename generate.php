#!/usr/bin/php
<?

require_once('config.php');

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

<form action = "?test/post/" method="post">
<label>ADD AN ITEM: </label> <br/>
<input id = "item" name = "item" type="text" />
<input type = "submit" value = "Add"/>
</form>

<p>
	<?php	echo \$data['content'];	?>
</p>

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
<br/>
<p>
	The database is empty. Try adding items.
</p>
<?php endif; ?>

VIEW;
	return $view;
}


function create($file,$data){
	$handle = fopen($file,'w') or die ("Can't open file.");
	fwrite($handle,$data);
	fclose($handle);
}

function makeTable($name){
	$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
	mysql_select_db(DB_DATABASE, $link);
	$names = $name.'s';
	$query = <<<QUERY
create table $names (
	id integer not null primary key auto_increment, 
	$name varchar(128) not null  
);
QUERY;
	mysql_query($query) or die("Query failed: $query. <br/> Reason:".mysql_error());
	mysql_close($link);
	echo "Created $name table!\n";
}

function deleteTable($name){
	if( ($name !='') && ($name != '*') ){
		$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
		mysql_select_db(DB_DATABASE, $link);
		$query = "drop table $name".'s'; 
		mysql_query($query) or die("Query failed: $query. <br/> Reason:".mysql_error());
		mysql_close($link);
		echo "Deleted $name table! \n";
	}
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


$args = getopt("c:m:v:p:u:h", array('mvc:','undo:','table:','undotable:','crud','help'));

$mvc['c'] = isset($args['c']) ? $args['c'] : null;
$mvc['m'] = isset($args['m']) ? $args['m'] : null;
$mvc['v'] = isset($args['v']) ? $args['v'] : null;
if ( isset($args['mvc']) ){
	$mvc['c'] =  $args['mvc'];
	$mvc['m'] =  $args['mvc'];
	$mvc['v'] =  $args['mvc'];
}
$check = count(array_filter($mvc));
if ($check){ 
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

if( isset($args['h']) or  isset($args['help'])  ){
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

?>
