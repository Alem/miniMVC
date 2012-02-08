<h2> Welcome to <?php echo SITE_NAME; ?> </h2>
<p>
	This data below has been passed to this view 
	by its controller and was generated/retrieved by its model.
</p>

<br/>

<p>
<h3>ADD AN ITEM: </h3> 
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
