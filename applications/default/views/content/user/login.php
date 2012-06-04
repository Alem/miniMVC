<?php if ( $data['message'] === 'fail' ): ?>
		<div class="alert alert-danger">
		<a class="close" href="user">×</a>
		<strong>Nope!</strong> Wrong, wrong wrong. Do it again.
		</div>
<?php elseif ( $data['message'] === 'goodbye' ): ?>
		<div class="alert alert-info">
		<a class="close" href="user">×</a>
		You successfully logged out. <strong>Seeya!</strong>
		</div>
<?php elseif ( $data['message'] === 'taken' ): ?>
		<div class="alert alert-danger">
		<a class="close" href="user">×</a>
		<strong>Sorry</strong>, that name is already registered. Try choosing another.
		</div>
<?php endif; ?>

<div class = "row" >
	<div class = "span4">
		<h2> Login </h2>
		<br/>
		<form class = "form-stacked" action = "user/login" method = "post">
			<label>User:
			<input name = "username" type = "text" /></label>
			<label>Password:
			<input name = "password" type = "password" /> </label>
			<p> <input class = "btn-primary btn-large" type = "submit" value = "Login"/> </p>
			<span class = "help-block"> Cause we really missed you. </span>
		</form>
	</div>

	<div class = "span3">
		<br/>
		<br/>
		<br/>
		<h1> or </h1>
	</div>

	<div class = "span3">
		<h2> Register </h2>
		<br/>
		<form class = "form-stacked" action = "user/register" method = "post">
			<label>User:
			<input name = "username" type = "text" /> </label>
			<label>Password:
			<input name = "password" type = "password" /> </label>
			<label>Verify Password:
			<input name = "verify_password" type = "password" /> </label>
			<label>E-mail:
			<input name = "email" type = "email" /> </label>

			<span id = 'human' style ='display:none' >
				Don't fill, robots only:
				<input type = "text" name ="address"/>
			</span>

			<p> <input class = "btn-success btn-large" type = "submit" value = "Register"/> </p>
			<span class = "help-block"> Quick &amp; easy, <br/>
				No e-mail verification required. </span>
		</form>
	</div>
</div>

<div class = "row" >
	<p>
	</p>
</div>
