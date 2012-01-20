<h1> A Test View </h1>
<h2> Paragraph</h2>
<?php	echo $data['content'];	?>

<?php	if( isset( $data['show']) ):	?>
	<h2>List</h2>
	<?php foreach( $data['show'] as $row) :	?>
		<?php foreach( $row as $column => $value) :	?>
			<b> <?php echo $column; ?> :  <?php echo $value; ?> </b>
			<br/>
		<?php endforeach; ?>
		<a href='?test/del/<?php echo $row['id'] ?>/'> Delete</a></p> 
	<?php endforeach; ?>
<?php endif; ?>

