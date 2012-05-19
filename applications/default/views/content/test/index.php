<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
<hr>

<br/>

<div class ='row'>
	<div class ='span7'>

		<h2> How It Works</h2>
		<br/>
		<p>
		<ol>
			<li>User requests are recieved by index.php and routed to the correct controller and method along with any variables. </li>
			<br/>
			<li>Database data is then requested by the controller, which is retrieved and returned by the model, and passed through the view by the controller to the end user.</li>
			<br/>
			<li>The controller or its view may incorporate the use of the controllers/applications modules.</li>
		</ol>
		</p>

		<br/>
		<blockquote>
			<p>
			<h3>Add An Item</h3> 
			<br/>
			<form action = "test/post/" method="post">
				<input name = "<?php echo $this -> name ?>" type="text" />
				<input type = "submit" value = "Add"/>
			</form>
			</p>

			<?php	echo ( isset( $this -> model() -> data['content'] ) ) ? $this -> model() -> data['content'] : "";	?>
		</blockquote>
	
	</div>
	<div class ='span4'>
<p>
<img src='<?php echo DEFAULT_MEDIA_PATH .'img/miniMVC.png'?>' />
</p>
	</div>
</div>


