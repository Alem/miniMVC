<?php if ( $data['message'] = 'welcome' ): ?>
		<div class="alert alert-success">
		<a class="close" href="user">×</a>
		<strong>Welcome!</strong> You are now logged in as <?php echo $data['session']['username'] ?>.
		</div>
<?php endif; ?>

<div class = "row-fluid" >
	<h1> Profile </h1>
	<br/>
	<br/>
	<div class = "span7">
		<p>
		<span class = "label" > User: </span><br/>
		<?php echo $data['session']['username'] ?>
		</p>
		<p>
		<span class = "label" > E-mail: </span><br/>
		<?php echo $data['session']['email'] ?>
		</p>
	</div>

	<div class = "span4">
		<a href = "user/logout" class = "btn-danger btn-large" >Logout </a>
	</div>
</div>
