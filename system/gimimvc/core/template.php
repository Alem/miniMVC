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
abstract class Template 
{


	/**
	 * __construct() - Establishes default unit name, fileCache name and extension.
	 */
	public function __construct( $name )
	{
		$this -> name = $name;
		$this -> uname = ucwords ( $name );
		$this -> fileCache() -> ext  = '.php';
		$this -> fileCache() -> id   = $name;
	}


	/**
	 * queryTool() - Grants access to single QueryTool instance.
	 *
	 * Runs QueryTool::getFormattedColumns() to profile the MVC unit's table.
	 *
	 * @return QueryTool Instance of QueryTool.
	 */
	public function queryTool()
	{
		if ( !isset( $this -> query_tool ) )
		{
			$this -> query_tool = new QueryTool(); 
			$this -> query_tool -> getFormattedColumns( $this -> name );
		}
		return $this -> query_tool;
	}


	/**
	 * fileCache() - Grants access to single instance of fileCache.
	 *
	 * fileCache is used by each template to create the scaffold file.
	 *
	 * @return FileCache 	Instance of FileCache.
	 */
	public function fileCache()
	{
		if ( !isset( $this -> fileCache ) ) 
			$this -> fileCache = new FileCache( $set_defaults = false );
		return $this -> fileCache;
	}


	/**
	 * generate() -  The default generate method used to create the template's scaffold file.
	 */
	public function generate()
	{
		echo 'Generating: ';
		echo $this->fileCache()->path . $this->fileCache()->id . $this->fileCache()->ext . "\n";
		$this -> fileCache() -> create( $this -> scaffold() );
	}


	/**
	 * undo() -  The default undo method used to delete the template's scaffold file.
	 */
	public function undo()
	{
		echo 'Removing: ';
		echo $this->fileCache()->path . $this->fileCache()->id . $this->fileCache()->ext . "\n";
		$this -> fileCache() -> clear();
	}


	/**
	 * redo() -  The default redo method used to regenerate the template's scaffold file.
	 */
	public function redo()
	{
		$this -> undo();
		$this -> generate();
	}


	/**
	 * scaffold() -  Extending template classes must override this function 
	 *
	 * Should be overriden to return the template's scaffold contents as a string.
	 */
	public function scaffold()
	{
	}
}

?>
