#!/usr/bin/php
<?php

require_once('generator/generator.php');

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
		'redo:',
		'scaffold:'
	) 
);

$generator = new Generator();
$generator -> process ($args);

?>

