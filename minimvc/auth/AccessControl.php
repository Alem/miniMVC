<?php
/**
 * AccessControl class file
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * AccessControl objects allow controllers to define access controls for actions based on permission levels of user roles
 *
 * Determines if the user has the permission/access level to
 * perform an action or view an item.
 *
 * References the $permissions array which must follow the format:
 *
 * 	permissions =>
 * 		roles =>
 * 			role => array( permission, permission )
 * 		actions =>
 * 			permission => array( action, action )
 *
 * ------------------------------
 * Example:
 *
 * 	public $permissions = array(
 *
 *		'roles' => array(
 *			0	=> array('c', 'r', 'u', 'd'),
 *			1	=> array('', 'r', '', ''),
 *		),
 *
 *		'actions' => array(
 *			'c'	=> array( 'post', 'form' ),
 *			'r'	=> array( 'show', 'gallery' ),
 *			'u' 	=> array( 'edit' ),
 *			'd'	=> array( 'del')
 *		)
 *	);
 * ------------------------------
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.auth
 */
class AccessControl
{

	/**
	 * Defines permission levels for spefific roles,
	 * and matches actions to permission levels.
	 *
	 * @var array
	 */
	public $permissions = array(
		'roles'   => array(),
		'actions' => array()
	);

	/**
	 * defineRoles - Recieves the user role permissions
	 *
	 * @param array $roles    The array of user roles where the key is the user-type and the value is an array of permissions.
	 */
	public function defineRoles( array $roles )
	{
		return $this->permissions['roles'] = $roles;
	}

	/**
	 * defineActions - Recieves the list of actions classified by permission-level
	 *
	 * @param array $actions    The array of actions where the key is the permission and the value is an array of actions.
	 */
	public function defineActions( array $actions )
	{
		return $this->permissions['actions'] = $actions;
	}

	/**
	 * setRole - Defines the current user's role
	 *
	 * This value will be matched against a key in the AccessControl::permissions['roles'] array
	 * the matching permission set will be enforced by AccessControl::permission()
	 *
	 * @param mixed $role  The current user's role
	 */
	public function setRole( $role )
	{
		return $this->default_role = $role;
	}

	/**
	 * permission - Checks if the supplied permission level is granted for the user's user-type
	 *
	 * Checks user's user-type against the $rule['roles'] array's ROLE:PERMISSION pairs
	 * to determine if user has sufficient permission.
	 *
	 * @param mixed $required_permission 	The level of permission required, ex c/r/u/d.
	 * @param mixed $user_role 		The user's user-role
	 * @return bool 			Returns true if the defined user-role has the required permissions; otherwise false.
	 */
	public function permission( $required_permission, $user_role = null  )
	{

		if( ( $user_role === null ) && isset( $this->default_role) )
			$user_role = $this->default_role;

		return (
			isset( $this->permissions['roles'][$user_role] )
			&& in_array( $required_permission, $this->permissions['roles'][$user_role] )
		);
	}

	/**
	 * action - Checks if action is appropriate for user type
	 *
	 * Checks supplied action against the $rule['actions'] array's PERMISSION:ACTIONS pairs
	 * ensuring that the supplied action's required permission is met by the user_type
	 *(done by Acess::permission)
	 *
	 * @param mixed  $action 		The name of the action
	 * @param mixed  $user_role 		The session variable that sets the role.
	 * @return bool				Returns true if the defined user-role has the required permissions; otherwise false.
	 * @uses AccessControl::permission()	Checks $user_role against the action's permission level
	 */
	public function action( $action, $user_role = null )
	{
		foreach( $this->permissions['actions'] as $required_permission => $actions )
		{
			if( in_array( $action, $actions ) )
			{
				if( $this->permission( $required_permission, $user_role ) )
					return true;
			}
		}
		return false;
	}
}

?>
