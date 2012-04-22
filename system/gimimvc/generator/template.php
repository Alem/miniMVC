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
class Template {

	public function __construct( $name ){
			$this  -> name = $name;
			$this  -> uname = ucwords ( $name );
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
			$this -> fileCache = new fileCache();
		return $this -> fileCache;
	}

}

interface TemplateInterface{

	public function genearate();

	public function undo( $name );

	public function redo( $name );
}

?>
