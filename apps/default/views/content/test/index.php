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

	</div>
	<div class ='span4'>
		<blockquote>
			<p>
			<h3>Add An Item</h3> 
			<br/>
			<form action = "?test/post/" method="post">
				<input name = "<?php echo $this -> name ?>" type="text" />
				<input type = "submit" value = "Add"/>
			</form>
			</p>

			<?php	if( isset( $this -> model -> data['show']) ):	?>
			<h2>List</h2>
			<ul>
				<?php foreach( $this -> model -> data['show'] as $row) :	?>
				<li>
				<?php foreach( $row as $column => $value) :	?>
				<b> <?php echo $column; ?>:  <?php echo $value; ?> </b>
				<?php endforeach; ?>
				<br/>
				<a href='test/del/<?php echo $row['id'] ?>/'> Delete</a></p> 
				</li>
				<?php endforeach; ?>
			</ul>

			<?php else: ?>
			<p>
			The database is empty. <br/> <a href="test/form">Click here</a>  to add items.
			</p>
			<?php endif; ?>

			<?php	echo ( isset( $this -> model -> data['content'] ) ) ? $this -> model -> data['content'] : "";	?>
		</blockquote>
	</div>
</div>


<p>
<img src='<?php echo DEFAULT_MEDIA_PATH .'img/miniMVC.png'?>' />
</p>
