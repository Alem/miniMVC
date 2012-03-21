<?php

/*
 * Access
 *
 * Determines if the user has the permission/access level to perform an action or view an item.
 * References the $permissions array in the controller which follows the format
 *
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
 *
 * LEGEND
 *
 * USER_TYPE 	ROLE
 *	0 	admin
 *	1 	manager
 *	2 	tenant
 *	3 	auth
 */

class Access{

	// permission - Checks if supplied permission level is appropriate for user type
	//
	// Checks user type against the $rule['roles'] array's ROLE:PERMISSION pairs 
	// to determine if user has sufficient permission.
	//
	// $action_permission - The level of permission required, ex c/r/u/d.
	// $session_var - The session variable that sets the role.

	function permission( $action_permission, $session_var = 'user_type' ){

		if ( Session::open() -> get( $session_var ) !== false )
			$session_role = Session::open() -> get( $session_var );
		else
			$session_role = '';

		foreach ( $this -> controller -> permissions['roles'] as $role => $role_permissions ){
			foreach ( $role_permissions as $role_permission ){
				if ( 
					( $action_permission == $role_permission ) 
					&& ( $session_role === $role ) 
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

	function action( $method = __METHOD__ , $session_var = 'user_type' ){
		foreach ( $this -> controller -> permissions['actions'] as $action_permission => $actions ){
			if ( in_array( $method, $actions ) ){
				if ( $this -> permission ( $action_permission, $session_var ) )
					return true;
			}
		}
		return false;
	}

}
