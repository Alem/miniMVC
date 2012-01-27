<h2> User Profile </h2>
<p>
<span class = "label" > User: </span><br/>
<?php echo $_SESSION['username'] ?> 
</p>
<span class = "label" > E-mail: </span><br/>
<?php echo $_SESSION['email'] ?> 
</p>
<a href = "?user/logout" class = "btn danger" >Logout </a>
