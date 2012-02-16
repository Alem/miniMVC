<h2> Welcome to <?php echo SITE_NAME; ?> </h2>

<br/>

<p>
<img src='<?php echo DEFAULT_MEDIA_PATH .'img/miniMVC.png'?>' />
</p>

<h3> How miniMVC Works</h3>
<p>
User requests are recieved by index.php and routed to the correct controller and method along with any variables. <br/>
Data is then requested by the controller, which is retrieved and returned by the model, and passed through the view by the controller
to the end user. The controller or its view my incorporate the usage of the controllers/applications modules.
</p>

<br/>

<p>
<h3>Add An Item</h3> 
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
	<a href='?test/del/<?php echo $row['id'] ?>/'> Delete</a></p> 
	</li>
<?php endforeach; ?>
</ul>

<?php else: ?>
<p>
	The database is empty. <br/> <a href="?test/form">Click here</a>  to add items.
</p>
<?php endif; ?>

<?php	echo ( isset( $this -> model -> data['content'] ) ) ? $this -> model -> data['content'] : "";	?>
