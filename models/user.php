<?php

class User extends Model{

	function welcome(){
		$welcome = <<<WELCOME
		<div class="alert-message success"> 
		<a class="close" href="?user">×</a>
		<p><strong>Welcome!</strong> You are now logged in as {$_SESSION['username']}.</p>
		</div>
WELCOME;
		echo $welcome;
	}

	function goodbye(){
		$goodbye = <<<GOODBYE
		<div class="alert-message info"> 
		<a class="close" href="?user">×</a>
		<p>You successfully logged out. <strong>Seeya!</strong></p>
		</div>
GOODBYE;
		echo $goodbye;
	}

	function fail(){
		$fail = <<<FAIL
		<div class="alert-message danger"> 
		<a class="close" href="?user">×</a>
		<p><strong>Nope!</strong> Wrong, wrong wrong. Do it again.</p>
		</div>
FAIL;
		echo $fail;
	}
}


?>
