<?php

/**
 * Access
 *
 * Determines if the user has the permission/access level to 
 * perform an action or view an item.
 *
 * References the $permissions array which must follow the format:
 * 	
 * 	permissions =>
 * 		roles =>
 * 			role => array( permission, permission )
 * 			role => array( permission )
 * 		actions =>
 * 			permission => array ( action, action )
 * 			permission => array ( action )
 * 	
 *	Example
 *	---------
 * 	public $permissions = array(
 *		'roles' => array(
 *			0	=> array('c', 'r', 'u', 'd'),
 *			1	=> array('', 'r', '', ''),
 *		),
 *		'actions' => array(
 *			'c'	=> array( 'post', 'form' ),
 *			'r'	=> array( 'show', 'gallery' ),
 *			'u' 	=> array( 'edit' ),
 *			'd'	=> array( 'del')
 *		)
 *	);
 */

class AccessControl{

	public function defineRoles( array $roles ){
		return $this -> permissions['roles'] = $roles;
	}

	public function defineActions( array $actions ){
		return $this -> permissions['actions'] = $actions;
	}

	public function setRole( $role ){
		return $this -> default_role = $role;
	}

	// permission - Checks if supplied permission level is appropriate for user type
	//
	// Checks user type against the $rule['roles'] array's ROLE:PERMISSION pairs 
	// to determine if user has sufficient permission.
	//
	// $action_permission - The level of permission required, ex c/r/u/d.
	// $session_var - The session variable that sets the role.

	public function permission( $required_permission, $user_role = null  ){

		if ( !isset ( $user_role ) && isset( $this -> default_role) )
			$user_role = $this -> default_role;

		foreach ( $this -> permissions['roles'] as $role => $role_permissions ){

			foreach ( $role_permissions as $role_permission ){

				if ( 
					( $required_permission === $role_permission ) 
					&& ( $user_role === $role ) 
				)
				return true;
			}
		}
		return false;
	}


	// permission - Checks if action is appropriate for user type
	//
	// Checks supplied action against the $rule['actions'] array's PERMISSION:ACTIONS pairs 
	// ensuring that the supplied action's required permission is met by the user_type
	// (done by Acess::permission)
	//
	// $method - The name of the method
	// $session_var - The session variable that sets the role.

	public function action( $action, $user_role = null ){
		foreach ( $this -> permissions['actions'] as $required_permission => $actions ){

			if ( in_array( $action, $actions ) ){
				if ( $this -> permission ( $required_permission, $user_role ) )
					return true;
			}
		}
		return false;
	}
}

?>
