<?php

class User extends Model{


	function getUser( $username, $password) {
		return $this -> SQL() 
			-> select('*') 
			-> from()
			-> where( array($username, $password), array('user','password'))
			-> run();
	}

	function addUser( $username, $password, $email) {
		return $this -> SQL() 
			-> insert( array($username, $password,$email), array('user', 'password','email') )
			-> run();
	}


	function deleteUser ( $username, $password ) {
		$this -> SQL() -> remove() -> where ( $username, $password )  -> run();
	}


	function editUser($new, $new_column, $ref, $ref_column = 'user' ) {
		$this  -> SQL() -> update( $new, $new_column) -> where($ref, $ref_column) -> run();
	}

}


?>
