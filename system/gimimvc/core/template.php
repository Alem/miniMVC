<?php
/**
 * Template class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/** 
 * The template class provides the basic functionality for all gimiMVC
 * template files ( *.tpl.php ).
 *
 */
abstract class Template {

	public function __construct( $name ){
			$this -> name = $name;
			$this -> uname = ucwords ( $name );
			$this -> fileCache() -> ext  = '.php';
	}

	public function queryTool(){
		if ( !isset( $this -> query_tool ) ){
			$this -> query_tool = new QueryTool(); 
			$this -> query_tool -> getFormattedColumns( $this -> name );
		}
		return $this -> query_tool;
	}

	public function fileCache(){
		if ( !isset( $this -> fileCache ) ) 
			$this -> fileCache = new fileCache( $set_defaults = false );
		return $this -> fileCache;
	}

	public function generate(){
	}

	public function undo(){
	}

	public function redo(){
		$this -> undo();
		$this -> generate();
	}
}

?>
