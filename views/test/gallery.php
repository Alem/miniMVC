<h2> Gallery</h2>

<?php if((isset($this -> model -> order_opts) )): ?>
<?php $max = count($this -> model -> order_opts); ?>
<?php $i = 0; ?>
<?php foreach( $this -> model -> order_opts as $type => $href ): ?>
<?php $i++; ?>

<a href='?<?php echo $href?>'> Order by <?php echo $type ?></a> <?php if ( $i != $max ): ?> | <?php endif; ?>

<?php endforeach;?>
<? endif;?>

<?php	if( isset( $this -> model -> data) ):	?>
<table>
<tr>

<?php $i = 0; ?>
<?php foreach( $this -> model -> data as $row) :	?>
	<td>
	<?php foreach( $row as $column => $value) :	?>
		<?php echo $column ?>:  <?php echo $value ?> 
	<?php endforeach; ?>
	<br/> <a href='?<? echo $this -> name ?>/del/<?php echo $row['id'] ?>'> Delete</a></p> 
	</td>

	<?php $i++; ?>
	<?php if ( $i == 2 ): ?> 
	</tr><tr>
	<?php $i = 0; ?>
	<?php endif;?>

<?php endforeach; ?>

</tr>
</table>

<div class="pagination">
  <ul>
	<?php if( isset(  $this->model->page )): ?>
	<?php $count = count($this -> model -> data); ?>
		<?php if($this -> model -> page != 1): ?>
		<li class="prev"><a href="?<?php echo $this -> name ?>/gallery/<?php echo ($this->model->page - 1).($this->model->order) ?>">&larr; Previous</a></li>
		<?php endif; ?>
		<?php for( $i = $this->model->page; $i <= $this -> model -> lastpage; $i++) : ?>
		<li><a href="?<?php echo $this -> name ?>/gallery/<?php echo $i.($this->model->order)  ?>"><?php echo $i ?></a></li>
		<?php endfor; ?>
		<?php if($this -> model -> page != $this -> model -> lastpage): ?>
		<li class="next"><a href="?<?php echo $this -> name ?>/gallery/<?php echo ($this->model->page + 1).($this->model->order) ?>">Next &rarr;</a></li>
		<?php endif; ?>
	<?php endif; ?>
  </ul>
</div>

<?php else: ?>
No results!
<?php endif; ?>
