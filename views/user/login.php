<div class = "row" >
	<div class = "span6">
		<h2> Login </h2>
		<br/>
		<form class = "form-stacked" action = "?user/login" method = "post">
			<label>User:</label>
			<input name = "username" type = "text" /> <br/>
			<label>Password:</label>
			<input name = "password" type = "password" />  <br/><br/>
			<p> <input class = "btn primary Large" type = "submit" value = "Login"/> </p>
			<span class = "help-block"> Cause we really missed you. </span>
		</form>
	</div>

	<div class = "span3">
		<br/>
		<br/>
		<br/>
		<h1> or </h1>
	</div>

	<div class = "span4">
		<h2> Register </h2>
		<br/>
		<form class = "form-stacked" action = "?user/register" method = "post">
			<label>User:</label>
			<input name = "username" type = "text" /> <br/>
			<label>Password:</label>
			<input name = "password" type = "password" />  <br/><br/>
			<label>Verify Password:</label>
			<input name = "verify_password" type = "password" />  <br/><br/>
			<label>E-mail:</label>
			<input name = "email" type = "email" />  <br/><br/>

			<span id = 'human' style ='display:none' > 
				Don't fill, robots only: 
				<input type = "text" name ="address"/>
			</span>

			<p> <input class = "btn success Large" type = "submit" value = "Register"/> </p>
			<span class = "help-block"> Quick &amp; easy, <br/>
				No e-mail verification required. </span>
		</form>
	</div>
</div>

<div class = "row" >
	<p>
	</p>
</div>
