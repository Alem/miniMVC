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

	function welcome(){
		$welcome = <<<WELCOME
		<div class="alert-message container success"> 
		<a class="close" href="?user">×</a>
		<p><strong>Welcome!</strong> You are now logged in as {$_SESSION['username']}.</p>
		</div>
WELCOME;
		return $welcome;
	}

	function goodbye(){
		$goodbye = <<<GOODBYE
		<div class="alert-message container info"> 
		<a class="close" href="?user">×</a>
		<p>You successfully logged out. <strong>Seeya!</strong></p>
		</div>
GOODBYE;
		return $goodbye;
	}

	function fail(){
		$fail = <<<FAIL
		<div class="alert-message container danger"> 
		<a class="close" href="?user">×</a>
		<p><strong>Nope!</strong> Wrong, wrong wrong. Do it again.</p>
		</div>
FAIL;
		return $fail;
	}
}


?>
