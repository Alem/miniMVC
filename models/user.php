<?php

class User extends Model{


	function getUser( $username, $password) {
			return $this -> select('*') 
			-> where( array($username, $password), array('user','password'))
			-> run();
		}

	function addUser( $username, $password, $email) {
			return $this -> insert( array($username, $password,$email), array('user', 'password','email') )
				-> run();
		}

}


?>
