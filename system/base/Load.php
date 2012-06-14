<?php
/**
 * Load class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Load class provides a configurable abstracted factory method that efficently loads the named component
 * as well as the method to assign the component instance as a property of a supplied object.
 *
 */

class Load
{

	/**
	 * @var array 	Holds file paths for components
	 */
	public $paths = array();

	/**
	 * construct - Set defaults
	 *
	 * @param bool $use_defaults 	If set to true, uses config/core.php constants to construct default paths.
	 */
	public function __construct( $use_defaults = true )
	{
		$this->paths = array(
			'config' 	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_APP_CONFIG_PATH,
			'controller' 	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CONTROLLER_PATH,
			'model' 	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODEL_PATH,
			'content'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_CONTENT_PATH,
			'error'		=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_ERROR_PATH,
			'message'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_MESSAGE_PATH,
			'template'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_TEMPLATE_PATH,
			'shared'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_SHARED_PATH,
			'module'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODULE_PATH,
			'library'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_LIBRARY_PATH,
			'log'		=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_LOG_PATH,
			'require'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_REQUIRE_PATH,
			'system'	=> SERVER_ROOT . DEFAULT_SYSTEM_PATH
		);
	}

	/**
	 * path() - Returns path for application component
	 *
	 * @param string $component 	Type of component
	 * @param string $name 		Name of component
	 * @param string $ext 		Type of extension
	 * @return string 		The constructed file path of the component
	 */
	public function path( $component , $name , $ext = '.php' )
	{
		return $this->paths[ $component ] . $name . $ext;
	}

	/**
	 * component - Simple factory method for application components(controllers,models,modules,libraries)
	 *
	 * If given in form 'dir/name', the class name will be derived from the portion after the '/'
	 *
	 * @param string $type 		The type of component to load
	 * @param string $name 		The name of the component
	 * @param bool 	 $instantiate 	If true, instantiates the component
	 * @return object 		The instance of the component
	 */
	public function component( $type, $name , $instantiate = true )
	{
		$filepath = $this->path( $type, $name );

		if( file_exists( $filepath ) ) {

			require_once( $filepath );

			if( $DS = strpos( $name, '/' ) )
				$classname = substr( $name, $DS + 1 );
			else
				$classname =& $name;

			if( $instantiate === true )
				return $component = new $classname;
		}else
			return null;
	}

}
?>
