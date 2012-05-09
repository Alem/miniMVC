<?php
/**
 * View template class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 *
 */
class View extends Template
{

	public $views = array( 'index', 'form', 'table', 'thumbnails', 'show' );

	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> fileCache() -> path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_VIEW_PATH . DEFAULT_CONTENT_PATH . $name . '/';  
	}


	public function generate()
	{
		echo 'Generating Directory: ';
		echo $this->fileCache()->path . "\n";
		mkdir ( $this -> fileCache() -> path );
		foreach ( $this -> views as $view )
		{
			echo 'Generating file for view: ' . $view . "\n";
			$this -> fileCache() -> create( $this -> scaffold( $view ), $view );
		}
	}


	public function undo()
	{
		foreach ( $this -> views as $view )
		{
			echo 'Removing file for view: ' . $view . "\n";
			$this -> fileCache() -> clear( $view );
		}
		echo 'Removing Directory: ';
		echo $this->fileCache()->path . "\n";
		rmdir ( $this -> fileCache() -> path );
	}


	public function scaffold( $return ) {

		$name = $this -> name;
		$uname = $this -> uname;


		if ( isset( $this -> queryTool() -> filtered_columns ) )
			$this -> viewbits ( $this -> queryTool() -> filtered_columns, $name );
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

		$search = <<<VIEW
		<form class = 'well form form-inline' action ='$name/gallery' method ='get' >
					<h4> Search {$uname}s</h4>
					<br/>
					<fieldset class="control-group">
						{$this->search_columns} <br/>
					</fieldset>
					<input class ='btn btn-large primary-btn' type='submit' value = 'Search'/>
		</form>
VIEW;
		######################## END HTML   #################################################################
		/*********************** BEGIN HTML ****************************************************************/
		$form = <<<VIEW
<div class = 'row' >

	<div class = 'span5 well' >
		<?php if (Session::open() -> get('editing_{$name}_id') ): ?>
			<h1>Edit</h1>
			<hr>
			<br/>
			<p>
				Change the values and press 'Update' to update the $uname.
			</p>
		<?php else: ?>
			<h1>Create $uname</h1>
			<hr>
			<br/>
			<p>
				Fill out the form to create a $uname.
			</p>
		<?php endif; ?>
	</div>

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
</div>
VIEW;
		######################## END HTML   #################################################################


		/*********************** BEGIN HTML ****************************************************************/
		$edit_delete = <<<VIEW
				<?php if ( \$this -> access() -> permission ('u') ): ?>
				<a class ='btn btn-info' href='$name/edit/<?php echo \$row['id'] ?>'>
					<i class = 'icon-pencil'></i> Edit
				</a> 
				<?php endif; ?>
				<?php if ( \$this -> access() -> permission ('d') ): ?>
				<a class ='btn btn-danger' href='$name/del/<?php echo \$row['id'] ?>'>
					<icon class = 'icon-trash'></i> Delete
				</a>
				<?php endif; ?>

VIEW;
		######################## END HTML   #################################################################


		/*********************** BEGIN HTML ****************************************************************/
		$thumbnails_seg = <<<VIEW
<br/>
<div class =''>
	<ul class = 'thumbnails' >
	<?php foreach( \$model -> data as \$row) :	?>
		<li class = 'span5'>
		<div class ='<?php if ( !isset ( \$model -> show ) ) echo 'thumbnail'; ?> '>
			<div class ='caption'>
				{$this -> thumbnails}
			<br/>
			<p>
			<?php if ( !isset ( \$model -> show ) ): ?>
			<a class ='btn btn-success' href='$name/show/<?php echo \$row['id'] ?>'>View</a>
			<?php else: ?>
				$edit_delete
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
		<?php if ( !isset ( \$model -> show )  || (  \$this -> access() -> permission ('u') || \$this -> access() -> permission ('d') ) ): ?>
		<th> Action </th>
		<?php endif; ?>
	</tr>
	<?php foreach( \$model -> data as \$row ) :	?>
	<tr>
		{$this -> table_cells}
		<td>
			<?php if ( !isset ( \$model -> show ) ): ?>
			<a class ='btn btn-success' href='$name/show/<?php echo \$row['id'] ?>'>View</a>
			<?php else: ?>
				$edit_delete
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
	<h2>$gallery_title</h2>
	<?php endif; ?>
	<hr/>
<?php	if( !empty( \$model -> data) ):	?>
	<div class = 'span3'>
		<?php if ( !isset ( \$model -> show ) && \$this -> access() -> permission('c') ): ?>
		<!-- <p><a class ='btn btn-primary btn-large' href='$name/form'><i class = 'icon-plus'></i> Add $uname</a></p> -->
		<?php elseif ( isset ( \$model -> show ) ): ?>
		<p><a class ='btn-danger btn-large' href='$name/gallery'>Go to $gallery_title</a></p>
		<?php endif; ?>
		<br/>
	</div>
</div>

<?php if ( isset ( \$model -> search ) ): ?>
<div class = 'well' >
	<i style ='font-size: 60px' class = 'icon-search'></i> 
	Seach results for: <?php echo implode ( ', ' , \$_GET ) ?>.
</div>
<?php endif; ?>
VIEW;
		######################## END HTML   #################################################################



		/*********************** BEGIN HTML ****************************************************************/
		$gallery_bottom = <<<VIEW
	<?php echo \$this -> module('base/helper') -> paginate() ?>
	<br/>
<?php else: ?>
	<div class = 'span2'>
		<br/>
		<br/>
		<p>
		<?php if ( !isset ( \$model -> search ) ): ?>
			<i style ='font-size: 700%' class = 'icon-folder-open'></i>
		<?php else: ?>
			<i style ='font-size: 700%' class = 'icon-question-sign'></i>
		<?php endif; ?>
		</p>
	</div>
	<div class = 'well span6'>


	<?php if ( !isset ( \$model -> search ) ): ?>
		<h3> Nothing here yet... </h3>
		<br/>
		<p>
			Looks like a $uname has not been created yet. <br/>
			<?php if ( !isset ( \$model -> show ) && \$this -> access() -> permission('c') ): ?>
			<br/>
			Press the "Add $uname" button to begin creating a $uname.
				<p>
					<a class ='btn btn-primary' href='$name/form'>
						<i class = 'icon-plus'></i> Add $uname
					</a>
				</p>
			<?php endif; ?>
	<?php else: ?>
		<h3> No matches found. </h3>
		<br/>
		<p> Sorry, we couldn't find any matches for your search. </p>
		<br/>
		<p><a class ='btn-success btn-large' href='$name/gallery'>Back to $gallery_title</a></p>
	<?php endif; ?>
	</div>
</div>
<?php endif; ?>

<?php if ( !( !isset ( \$model -> search ) && empty( \$model -> data) ) ): ?>
$search
<?php endif; ?>
VIEW;
		######################## END HTML   #################################################################

		/*********************** BEGIN HTML ****************************************************************/
		$show_table = <<<VIEW
		<table class = 'table'>
			{$this -> show_table }
		</table>
VIEW;
		$show_thumbnails = <<<VIEW
		<p>
			{$this -> thumbnails}
		</p>
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
			$show_table
		<?php endforeach; ?>
	</div>
	<br/>
	<div class = 'span3' >

		<p><a class ='btn-success btn-large' href='$name/gallery'>Go to $gallery_title</a></p>
		<br/>
		<hr>

		$edit_delete
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

		if ( $return == 'table' )
			$table = $gallery_top . $table_seg . $gallery_bottom;
		elseif ( $return == 'thumbnails')
			$thumbnails = $gallery_top . $thumbnails_seg . $gallery_bottom;

		return $$return;
	}




	public function viewbits( $column, $name )
	{
		if ( is_array($column) ):
			$this -> inputs = null;
		$this -> table_heads = null;
		$this -> table_cells = null;
		$this -> thumbnails = null;
		$this -> show_table = null;
		$this -> search_columns = null;
		foreach( $this -> queryTool() -> filtered_columns as $single_column ):
			$this -> viewbits ( $single_column, $name );
endforeach;
else:
	$uc_column = ucwords( $column );

/*********************** BEGIN HTML ****************************************************************/
$printed_column = <<<printed
	<?php echo \$row['$column'] ?> 
printed;
######################## END HTML   #################################################################

$helper = 'input';
$column_id = $column; // If external retains id, while column is stripped of id
$prefixed_column = $column; // For search. Prefixes if external

if ( preg_match( '/_id/', $column ) ):
	$column_id = $column;
$column = preg_replace( '/_id/', '', $column);
$prefixed_column = $column . 's.'	. $column;
$uc_column = ucwords( $column );
$printed_column = <<<printed
	<a href ='$column/show/<?php echo \$row['$column_id'] ?>'> <?php echo \$row['$column'] ?> </a>
printed;
$helper = 'select';
endif;

/*********************** BEGIN HTML ****************************************************************/
$this -> inputs .= <<<input

	<label>$uc_column: <?php echo html::$helper ('$column', \$model -> saved_fields['$column'] ) ?> </label>
input;
######################## END HTML   #################################################################
/*********************** BEGIN HTML ****************************************************************/
$this -> table_heads.= <<<heads
	<th> 
	<a href='<?php 
		echo '$name/gallery/' . \$model -> page .  VAR_SEPARATOR . '$column_id' . VAR_SEPARATOR;
		echo ( stristr( \$model -> order, '$column_id/ASC' ) ) ? 'DESC' : 'ASC';
		echo \$model -> search;
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
$this -> show_table .= <<<show
	<tr>
		<td> <span class = 'label' >$uc_column:</span> </td>
		<td> $printed_column</td>
	</tr>

show;
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
/*********************** BEGIN HTML ****************************************************************/
$this -> search_columns .= <<<search

	<label>$uc_column: <?php echo html::input ('$prefixed_column') ?> </label>
search;
######################## END HTML   #################################################################
endif;
	}




}


?>
