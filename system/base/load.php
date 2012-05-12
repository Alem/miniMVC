<?php
/**
 * Load class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Load class provides a configurable factory method that efficently loads the named component
 * as well as the method to assign the component instance as a property of a supplied object.
 *
 * todo Is the Load::toObject safe? difficult to test?
 */

class Load
{

	/**
	 * @var array Paths for application components
	 */
	public static function path( $component , $name , $ext = '.php' )
	{
		$paths = array(
			'controller' 	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_CONTROLLER_PATH,
			'model' 	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODEL_PATH,
			'view'		=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_CONTENT_PATH,
			'template'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_TEMPLATE_PATH,
			'shared'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_SHARED_PATH,
			'module'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODULE_PATH,
			'library'	=> SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_LIBRARY_PATH,
			'system'	=> SERVER_ROOT . DEFAULT_SYSTEM_PATH
		);
		return $paths[ $component ] . $name . $ext;
	}

	/**
	 * component - Simple factory method for application components (controllers,models,modules,libraries)
	 *
	 * If given in form 'dir/name', the class name will be derived from the portion after the '/'
	 *
	 * @param string $type 		The type of component to load
	 * @param string $name 		The name of the component
	 * @param bool 	 $instantiate 	If true, instantiates the component
	 * @return object 		The instance of the component
	 */
	public static function component( $type, $name , $instantiate = true )
	{
		$filepath = load::path ( $type, $name );

		if ( file_exists( $filepath ) ) {

			require_once( $filepath );

			if ( $DS = strpos( $name, '/' ) )
				$classname = substr ( $name, $DS + 1 );
			else
				$classname =& $name;

			if ( $instantiate === true )
			{
				if ( $type === 'controller' )
					$classname =  $classname . 'Controller';
				return $component = new $classname;
			}
		}else
			return null;
	}


	/**
	 * toObject - Uses load::component to load an instance of the named class to the supplied object
	 *
	 * Instantiates the named class if not already assigned to the supplied object and returns the new object property.
	 * Otherwise it returns the named object property.
	 *
	 * @param object $object 	The object onwhich the instance will be loaded to
	 * @param string $type 		The type of component
	 * @param string $name 		The name of the component
	 * @return object 		The instance of the component
	 */
	public static function toObject( $object, $type , $name, $instantiate = true )
	{
		if ( !isset ( $object -> loaded[ $type ][$name] ) )
			$object -> loaded[$type][$name] = Load::component( $type , $name, $instantiate );

		return $object -> loaded[$type][$name];
	}

}
?>
