<?php if ( isset( $this -> model -> welcomeMsg ) ): ?>
		<div class="alert-message success"> 
		<a class="close" href="?user">×</a>
		<p><strong>Welcome!</strong> You are now logged in as <?php echo $this -> model -> welcomeMsg ?>.</p>
		</div>
<?php endif; ?>

<div class = "row" >
	<h2> User Profile </h2>
	<br/>
	<div class = "span9">
		<p>
		<span class = "label" > User: </span><br/>
		<?php echo $_SESSION['username'] ?> 
		</p>
		<span class = "label" > E-mail: </span><br/>
		<?php echo $_SESSION['email'] ?> 
		</p>
	</div>

	<div class = "span4">
		<a href = "?user/logout" class = "btn danger large" >Logout </a>
	</div>
</div>
