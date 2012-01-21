<h1> Test View </h1>
<h2> Welcome to <?php echo SITE_NAME; ?> </h2>
<p>
	This data below has been passed to this view 
	by its controller and was generated/retrieved by its model.
</p>

<h3>ADD AN ITEM: </h3> 
<form action = "?test/post/" method="post">
<input id = "item" name = "item" type="text" />
<input type = "submit" value = "Add"/>
</form>

<?php	if( isset( $data['show']) ):	?>
<h2>List</h2>
<ul>
<?php foreach( $data['show'] as $row) :	?>
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

<?php	echo $data['content'];	?>
