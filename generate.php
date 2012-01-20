#!/usr/bin/php
<?

require_once('config.php');

function controller($name) {
	$controller = <<<CONT
<?php

class $name extends Controller{
	function __construct(){
		// Is assigned name,classname,filename, and model after instantiation.
	}
	
	function index(){
		\$this -> useView(); 
	}

}
?>
CONT;

	$crud = <<<CRUD

	function form(){
		$item = $_POST['item'];
		$this -> model -> insert($item);
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}

	function add($item){
		$this -> model -> insert($item);
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}

	function del($id){
		$this -> model -> remove($id);
		$this -> model -> data['content'] = "$id Deleted.";
		$this -> show();
	}

	function show(){
		$query_result = $this -> model -> select ('*');
		$this -> model -> data['content'] = Array("show" => $query_result);
			foreach ($this -> model -> data['content']['show'] as $row){ 
				$this-> model -> data['content'] .= "<p>ID: ". $row['id'] . "<br/> Item: ". $row['test'] ."<br/>"; 
				$this-> model -> data['content'] .= "<a href='?test/del/" . $row['id'] . "/'> Delete</a></p>"; 
			}
		$this -> useView();
	}


CRUD;
	return $controller;
}


function model($name){
	$model =  <<<MODEL
<?php

class $name extends Model{
	function __construct(){
		parent::__construct();
		\$data['content'] = "This is a filler text for $name";
		\$this -> data = \$data;
	}
}
?>
MODEL;
	return $model;
}


function view() {
	$view = <<<VIEW
<h1>Title</h1>
<?php echo \$data['content']; ?>
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
	mysql_query($query) or die("Query failed: $query");
	mysql_close($link);
	echo "Created $name table!\n";
}

function deleteTable($name){
	if( ($name !='') && ($name != '*') ){
		$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
		mysql_select_db(DB_DATABASE, $link);
		$query = "drop table $name"; 
		mysql_query($query) or die('MySQL query failed');
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
		$template = model(ucwords($name));
		$path =  SERVER_ROOT . '/models/' . $name . '.php';
	}

	if ($type == 'v'){
		echo "Creating view for $name...\n";
		$template = view();
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
$args = getopt("c:m:v:p:u:",array('mvc:','undo:','table:','undotable:'));

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

?>
