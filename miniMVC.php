#!/usr/bin/php
<?php

require_once('config/main.php');
require_once('generator/database.php');
require_once('generator/generator.php');
require_once('generator/controller.php');
require_once('generator/model.php');
require_once('generator/view.php');
require_once('generator/processor.php');

// Running Script
$args = getopt( 

	"c:m:v:p:u:h", 

	array(
		'mvc:', 
		'undo:', 
		'table:', 
		'undotable:', 
		'link:', 
		'unlink:', 
		'to:', 
		'user', 
		'help', 
		'opendb:', 
		'redo:'
	) 
);

$processor = new Processor();
$processor -> execute ($args);

?>

