<?php

class View extends Generator{

	function scaffold( $return ) {

		$name = $this -> name;
		$uname = $this -> uname;


		if ( isset( Database::open() -> filtered_columns ) )
			$this -> viewbits ( Database::open() -> filtered_columns, $name );
		else
			$this -> viewbits ( $name , $name );


/*********************** BEGIN HTML ****************************************************************/
		$index = <<<VIEW
<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
<hr>

<br/>

<div class ='row'>
	<div class ='span7'>
		<h2>$uname</h2>
	</div>
	<div class ='span4 well'>
	</div>
</div>
VIEW;
######################## END HTML   #################################################################

/*********************** BEGIN HTML ****************************************************************/
		$form = <<<VIEW
<div class = 'row' >
	<div class = 'span4' >
		<form class = "form-stacked" action = "$name/post/" method = "post">
					{$this -> inputs}
			<p>
			<?php if (Session::open() -> get('editing_{$name}_id') ): ?>
			<input class = "Primary btn large btn-primary btn-large" type = "submit" value = "Update"/>
			<?php else: ?>
			<input class = "Primary btn large btn-primary btn-large" type = "submit" value = "Submit"/>
			<?php endif; ?>
			</p>

		</form>
	</div>
	<div class = 'span5 well' >
		<?php if (Session::open() -> get('editing_{$name}_id') ): ?>
		<h1>Edit</h1>
		<?php else: ?>
		<h1>Add</h1>
		<?php endif; ?>
		<hr>
		<br/>
		<p>
		The content you enter here is added directly to the database.
		</p>
		<p>
		The data is parameterized and the columns are whitelisted to match valid table columns.
		</p>

	</div>
</div>
VIEW;
######################## END HTML   #################################################################

		
/*********************** BEGIN HTML ****************************************************************/
		$search = <<<VIEW
VIEW;
######################## END HTML   #################################################################


/*********************** BEGIN HTML ****************************************************************/
		$thumbnails_seg = <<<VIEW
<br/>
<div class =''>
	<ul class = 'thumbnails' >
	<?php foreach( \$model -> data as \$row) :	?>
		<li class = 'span3'>
		<div class =' <?php if ( !isset ( \$model -> show ) ) echo 'thumbnail'; ?> '>
			<div class ='caption'>
				<p>
					{$this -> thumbnails}
				</p>
			<br/>
			<p>
			<?php if ( !isset ( \$model -> show ) ): ?>
			<a class ='btn btn-success' href='$name/show/<?php echo \$row['id'] ?>'>View</a>
			<?php else: ?>
				<?php if ( \$this -> access -> permission ('u') ): ?>
				<a class ='btn btn-info' href='$name/edit/<?php echo \$row['id'] ?>'>Edit</a> 
				<?php endif; ?>
				<?php if ( \$this -> access -> permission ('d') ): ?>
				<a class ='btn btn-danger' href='$name/del/<?php echo \$row['id'] ?>'>Delete</a>
				<?php endif; ?>
			<?php endif; ?>
			</p>
			</div>
		</div>
		<li>
	<?php endforeach; ?>
	</ul>
</div>
VIEW;
######################## END HTML   #################################################################


/*********************** BEGIN HTML ****************************************************************/
		$table_seg = <<<VIEW
<table class ='table table-striped'>
	<tr>

			{$this -> table_heads}

<?php if (
	!isset ( \$model -> show ) 
	|| (  \$this -> access -> permission ('u') || \$this -> access -> permission ('d') )
): ?>
<th> Action </th>
<?php endif; ?>
</tr>
	<?php foreach( \$model -> data as \$row) :	?>
	<tr>

			{$this -> table_cells}

	<td>
	<?php if ( !isset ( \$model -> show ) ): ?>
	<a class ='btn btn-success' href='$name/show/<?php echo \$row['id'] ?>'>View</a>
	<?php else: ?>
	<?php if ( \$this -> access -> permission ('u') ): ?>
	<a class ='btn btn-info' href='$name/edit/<?php echo \$row['id'] ?>'>Edit</a> 
	<?php endif; ?>
	<?php if ( \$this -> access -> permission ('d') ): ?>
		<a class ='btn btn-danger' href='$name/del/<?php echo \$row['id'] ?>'>Delete</a>
		<?php endif; ?>
	<?php endif; ?>
	</td>

		</tr>
		<?php endforeach; ?>

	</table>
VIEW;
######################## END HTML   #################################################################


		$last_letter = $uname[ strlen( $uname) - 1 ];

		if ( $last_letter == 's' || $last_letter == 'y' )
			$gallery_title = "$uname Gallery";
		else
			$gallery_title = $uname . 's';



/*********************** BEGIN HTML ****************************************************************/
		$gallery_top = <<<VIEW
<div class = 'row'>
	<?php if ( isset ( \$model -> show ) ): ?>
		<h1>$uname</h1>
	<?php else: ?>
		<h2> $gallery_title</h2>
	<?php endif; ?>
		<hr>
		<br/>

	<?php	if( isset( \$model -> data) ):	?>

	<div class = 'span3'>
		<?php if ( !isset ( \$model -> show ) && \$this -> access -> permission('c') ): ?>
		<p><a class ='btn btn-primary' href='$name/form'>Add $uname</a></p>
		<?php elseif ( isset ( \$model -> show ) ): ?>
		<p><a class ='btn-danger btn-large' href='$name/gallery'>Back to $gallery_title</a></p>
		<?php endif; ?>
		<br/>
	</div>
</div>

VIEW;
######################## END HTML   #################################################################



/*********************** BEGIN HTML ****************************************************************/
		$gallery_bottom = <<<VIEW
<?php echo \$this -> helper -> paginate() ?>

<?php else: ?>
No results!
<?php endif; ?>
VIEW;
######################## END HTML   #################################################################

/*********************** BEGIN HTML ****************************************************************/
		$show = <<<VIEW
<div class = 'row'>
	<h1>$uname</h1>
	<hr>

	<br/>
	<div class = 'well span6' >
		<?php foreach( \$model -> data as \$row) :	?>
		<p>
			{$this -> thumbnails}
		</p>
		<?php endforeach; ?>

		<br/>
		<hr>

		<?php if ( \$this -> access -> permission ('u') ): ?>
			<a class ='btn btn-info' href='$name/edit/<?php echo \$row['id'] ?>'>Edit</a> 
		<?php endif; ?>
		<?php if ( \$this -> access -> permission ('d') ): ?>
			<a class ='btn btn-danger' href='$name/del/<?php echo \$row['id'] ?>'>Delete</a>
		<?php endif; ?>
	</div>
	<br/>
	<div class = 'span3' >
		<p><a class ='btn-danger btn-large' href='$name/gallery'>Back to $gallery_title</a></p>
	</div>
</div>
VIEW;
######################## END HTML   #################################################################


/*********************** BEGIN HTML ****************************************************************/
		$about = <<<VIEW
<h1> About </h1>
<hr>
<br/>
<p>
<?php echo SITE_NAME ?> is a tiny framework created to make the web developers life easier. <br/>
In contrast to the popular large frameworks, <?php echo SITE_NAME; ?> is very tiny and includes only 
a bare-bones MVC structure with supplementary media.<br/> 
</p>
<p>
With its small size, <?php echo SITE_NAME; ?> doesn't get in your way and lets you <em>swiftly hack your way</em> to a new app.
</p>
<p>
If you notice any bugs or leaky faucets let us know at <a href = "mailto:<?php echo SITE_EMAIL ?>"> <?php echo SITE_EMAIL ?></a>.
</p>
VIEW;
######################## END HTML   #################################################################

		if ( $return == 'table' ){
			$table = $gallery_top . $table_seg . $gallery_bottom;
		}elseif ( $return == 'thumbnails'){
			$thumbnails = $gallery_top . $thumbnails_seg . $gallery_bottom;
		}


		return $$return;
	}


	function viewbits( $column, $name ){
		if ( is_array($column) ){
			$this -> inputs = null;
			$this -> table_heads = null;
			$this -> table_cells = null;
			$this -> thumbnails = null;
			foreach( Database::open() -> filtered_columns as $single_column ){
				$this -> viewbits ( $single_column, $name );
			}
		}else{

			$uc_column = ucwords( $column );

/*********************** BEGIN HTML ****************************************************************/
			$printed_column = <<<printed
	<?php echo \$row['$column'] ?> 
printed;
######################## END HTML   #################################################################

			$helper = 'input';

			if ( preg_match( '/_id/', $column ) ){
				$column_id = $column;
				$column = preg_replace( '/_id/', '', $column);
				$uc_column = ucwords( $column );
				$printed_column = <<<printed
	<a href ='$column/show/<?php echo \$row['$column_id'] ?>'> <?php echo \$row['$column'] ?> </a>
printed;
				$helper = 'select';
			}

/*********************** BEGIN HTML ****************************************************************/
			$this -> inputs .= <<<input

	<label>$uc_column: <?php echo \$this -> helper -> $helper ('$column') ?> </label>
input;
######################## END HTML   #################################################################


/*********************** BEGIN HTML ****************************************************************/
			$this -> table_heads.= <<<heads
	<th> 
	<a href='<?php 
		echo '$name/gallery/' . \$model -> page .  VAR_SEPARATOR . '$column' . VAR_SEPARATOR;
		echo ( stristr( \$model -> order, '$column/ASC' ) ) ? 'DESC' : 'ASC' 
		?>'
	>
	$uc_column
	</a>  
	</th>
heads;
######################## END HTML   #################################################################


/*********************** BEGIN HTML ****************************************************************/
			$this -> table_cells.= <<<cells

	<td> $printed_column </td>
cells;
######################## END HTML   #################################################################


/*********************** BEGIN HTML ****************************************************************/
			$this -> thumbnails .= <<<tbs

			<span class = 'label' >$uc_column:</span>
			$printed_column
			<br/>

tbs;

/*********************** BEGIN HTML ****************************************************************/
			$this -> show .= <<<show

	<h3>$uc_column:</h3>
	$printed_column
	<br/>

show;
######################## END HTML   #################################################################
		}
	}




}


?>
