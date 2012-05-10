<div class = 'row'>
	<div class = 'span5'>
		<h1> View</h1>
		<hr>
		<br/>
	</div>

	<div class = 'span6'>
		<br/>
		<br/>
		<?php if( isset(  $this->model()->page )): ?>
		<a class = 'btn btn-info' href='<?php echo 'test/gallery/' . $this-> model() -> page .  VAR_SEPARATOR . 'id' . VAR_SEPARATOR . 'ASC' ?>'>Order by ID</a> 
		<a class = 'btn btn-info' href='<?php echo 'test/gallery/' . $this-> model() -> page .  VAR_SEPARATOR . 'test' . VAR_SEPARATOR . 'ASC' ?>'>Order by Name</a>
		<?php endif; ?>

		<?php	if( isset( $this -> model() -> data) ):	?>
	</div>
</div>
<br/>
<br/>
<table class ='table table-striped'>
	<tr>
		<th> Id </th>
		<th> Item </th>
		<th> Action </th>
	</tr>
	<?php foreach( $this -> model() -> data as $row) :	?>
	<tr>
		<td>
			<?php foreach( $row as $column => $value) :	?>
			<?php echo $value ?> 
		</td>
		<td>
			<?php endforeach; ?>
			<br/> <a class ='btn btn-danger' href='<? echo $this -> name ?>/del/<?php echo $row['id'] ?>'> Delete</a></p> 
		</td>

	</tr>
	<?php endforeach; ?>

</table>

<?php Element::pager( $this -> model() ); ?>

<?php else: ?>
No results!
<?php endif; ?>
