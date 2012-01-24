<h1> Gallery</h1>

<?php	if( isset( $this -> model -> data['show']) ):	?>
<table>
<tr>

<?php $i = 0; ?>
<?php foreach( $this -> model -> data['show'] as $row) :	?>
	<td>
	<?php foreach( $row as $column => $value) :	?>
		<?php echo $column ?>:  <?php echo $value ?> 
	<?php endforeach; ?>
	<br/> <a href='?<? echo $this -> name ?>/del/<?php echo $row['id'] ?>/'> Delete</a></p> 
	</td>

	<?php $i++; ?>
	<?php if ( $i == 2 ): ?> 
	</tr><tr>
	<?php $i = 0; ?>
	<?php endif;?>

<?php endforeach; ?>

</tr>
</table>
<?php endif; ?>
