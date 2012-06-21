<?php
/**
 * Scaffold class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Scaffold class provides the basic functionality for all gimiMVC
 * Scaffold files ( *.tpl.php ).
 */
abstract class Scaffold
{

	/**
	 * Holds the unit name
	 * @var string
	 */
	public $name;

	/**
	 * If set to true, removes scaffold's parent directory on undo.
	 * @var bool
	 */
	public $undo_directory = false;


	/**
	 * Holds File objects. Used by Scaffold::file()
	 * @var array
	 */
	public $file = array();


	/**
	 * __construct() - Establishes default unit name, file name and extension.
	 *
	 * @param string $name  	The name of the scaffold
	 * @param string $config  	The scaffold configuration array.
	 */
	public function __construct( $name, $config )
	{
		$this->name = $name;
		$this->uname = ucwords( $name );
		$this->config = $config;

		$this->initialize();
	}

	/**
	 * initialize - Extending Scaffold classes must override this function
	 *
	 * Defines what is to be done after construction
	 * Used to setup scaffold files, using file(), which are then manipulated by generate or undo.
	 *
	 * ex: $this->file( 'index', 'app/views/content/main/' );
	 */
	public function initialize()
	{
	}


	/**
	 * getContent() - Extending Scaffold classes must override this function
	 *
	 * Returns the Scaffold's scaffold contents as a string.
	 * Accepts the name of the scaffold file as a parameter and returns the appropriate
	 * scaffold content.
	 *
	 * ex: for the scaffold file 'index', $name = index and returned string is index content.
	 *
	 * @param string $name 	The name of the scaffold file to return content for
	 * return string
	 */
	public function getContent( $name )
	{
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
		if ( !isset( $this->query_tool ) )
		{
			$database =  new Database();
			$this->query_tool = new QueryTool( $database );
			$this->query_tool->getFormattedColumns( $this->name );
		}
		return $this->query_tool;
	}


	/**
	 * file() - Grants access to single instance of file.
	 *
	 * file is used by each Scaffold to write the scaffold file.
	 *
	 *
	 * @param string $name 	The name of the file
	 * @param string $path  The path that holds the file
	 * @return File 	Instance of file.
	 */
	public function file( $name = null, $path = null )
	{
		if( $name === null )
			$name = $this->name;

		if( is_array( $name ) )
		{
			foreach( $name as $single_name )
				$this->file( $single_name, $path );
		}
		else
		{
			if ( !isset( $this->file[$name] ) )
			{
				$this->file[$name] = new File();
				$this->file[$name]->ext = '.php';
				$this->file[$name]->id  = $name;
			}

			if( $path !== null )
				$this->file[$name]->path = $path;

			return $this->file[$name];
		}
	}


	/**
	 * generate() -  The default generate method used to write the Scaffold's scaffold file.
	 */
	public function generate()
	{
		foreach( $this->file as $name => $file )
		{
			if( !is_dir( $file->path ) )
			{
				echo "Creating directory: '" . $file->path . "'\n";
				mkdir( $file->path , 0777 , true );
			}

			if( isset( $file->id ) )
			{
				echo 'Generating: ' . $file->path . $file->id . $file->ext . "\n";
				$file->write( $this->getContent( $name ) );
			}
		}
	}

	/**
	 * undo() -  The default undo method used to delete the Scaffold's scaffold file.
	 */
	public function undo()
	{
		foreach( $this->file as $file )
		{
			echo 'Removing: ' . $file->path . $file->id . $file->ext . "\n";
			$file->delete();

			if(
				$this->undo_directory
				&& is_dir( $file->path )
				&& ( $file->isPathEmpty() )
			)
			{
				echo "Removing directory: '" . $file->path . "'\n" ;
				rmdir( $file->path );
			}
		}
	}


	/**
	 * redo() -  The default redo method used to regenerate the Scaffold's scaffold file.
	 */
	public function redo()
	{
		$this->undo();
		$this->generate();
	}

}

?>
