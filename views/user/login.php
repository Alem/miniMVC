<div class = "row" >
<div class = "span4">
<h2> Login </h2>
<form class = "form-stacked" action = "?user/login" method = "post">
<label>User:</label>
<input name = "username" type = "text" /> <br/>
<label>Password:</label>
<input name = "password" type = "password" />  <br/><br/>
<p> <input class = "btn success Large" type = "submit" value = "Login"/> </p>
<span class = "help-block"> Cause we really missed you. </span>
</form>
</div>

<div class = "span1">
<h1>  </h1>
</div>

<div class = "span4">
<h2> Register </h2>
<form class = "form-stacked" action = "?user/register" method = "post">
<label>User:</label>
<input name = "username" type = "text" /> <br/>
<label>Password:</label>
<input name = "password" type = "password" />  <br/><br/>
<label>Verify Password:</label>
<input name = "verify_password" type = "password" />  <br/><br/>
<label>E-mail:</label>
<input name = "email" type = "email" />  <br/><br/>
<p> <input class = "btn success Large" type = "submit" value = "Register"/> </p>
<span class = "help-block"> Quick &amp; easy, <br/>
No e-mail verification required. </span>
</form>
</div>
</div>
