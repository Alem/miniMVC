<?php

class User extends Model{

	function welcome(){
		$welcome = <<<WELCOME
		<div class="alert-message success"> 
		<a class="close" href="?user">×</a>
		<p><strong>Welcome Back!</strong> You are now logged in as {$_SESSION['username']}.</div>
WELCOME;
		echo $welcome;
	}

	function goodbye(){
		$goodbye = <<<GOODBYE
		<div class="alert-message danger"> 
		<a class="close" href="?user">×</a>
		<p>You successfully logged out. <strong>Seeya!</strong></div>
GOODBYE;
		echo $goodbye;
	}

}



?>
