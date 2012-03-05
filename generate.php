#!/usr/bin/php
<?

// Hideous, gets job done.

require_once('config.php');


// MVC TEMPLATE GENERATION FUNCTIONS
// controller()
// model()
// view()

function controller($name, $short_name) {

	$controller = <<<CONT
<?php

class $name extends Controller{

	function __construct(){
		parent::__construct();
	}


	// index() - Loads default 'index' view
	
	function index(){
		\$this -> useView();
	}


	// form() - Loads 'form' view

	function form(){
		\$this -> useView('form');
	}


	// post() - Recieves POST data and hands it to model for database insertion
	
	function post(){
		\$this -> model -> insertPOST();
		\$this -> prg('gallery');
	}


	// add() - Directly insert data from URL. $short_name ONLY

	function add(\$item){
		\$this -> model -> insert(\$item) -> run();
		\$this -> show();
	}


	// del() - Directly remove database data from URL. $short_name ONLY

	function del(\$value, \$column = null){
		\$this -> model -> delete$short_name ( \$value, \$column );
		\$this -> prg('gallery');
	}


	// edit() - Updates specified values
	//
	// \$ref - Reference value
	// \$new - New value to be set
	// \$ref_column - Reference column 
	// \$new_column - Column of new value

	function edit(\$ref, \$new, \$column_ref = null, \$column_new = null){
		\$this -> model -> edit$short_name(\$new, \$new_column, \$ref, \$ref_column );
		\$this -> show();
	}

	
	// show() - Display all information for specifed primary Id
	//
	// Retrieves all data for specified id and passes it to 'gallery' view

	function show( \$id ){
		\$this -> model -> get$short_name(\$id);
		\$this -> useView('gallery');
	}


	// gallery() - A gallery of items
	//
	// Displays items '{$short_name}s' in gallery form.
	//
	// \$page - Current page, defaults to 1
	// \$order_col 	- The column to order by
	// \$order_sort 	- The sort to use

	function gallery(\$page = 1, \$order_col = null, \$order_sort = null){
		\$this -> model -> gallery$short_name( \$order_col, \$order_sort, \$page  );
		\$this -> useView('gallery');
	}


	// about() - Run of the mill 'about' page

	function about(){
		\$this -> useView('about');
	}


}
?>
CONT;

	return $controller;
}


function model($name,$l_name){
	$model =  <<<MODEL
<?php

class $name extends Model{

	function __construct(){
		parent::__construct();
	}

	function insertPOST(){
		\$form_fields = array_keys(\$_POST);
		\$this	-> insert( \$_POST, \$form_fields) 
			-> run();
	}

	function delete$name ( \$value, \$column ) {
		\$this  -> remove() -> where ( \$value, \$column ) -> run();
	}

	function edit$name(\$new, \$new_column, \$ref, \$ref_column ) {
		\$this  -> update( \$new, \$new_column) -> where(\$ref, \$ref_column) -> run();
	}

	function get$name(\$id ) {
		\$result = \$this -> select ('*') 
			-> where(\$id,'id') 
			-> run();
		\$this  -> set( 'data',  \$result);

		return \$result;
	}

	function gallery$name( \$order_col, \$order_sort, \$page){
		\$result = \$this -> select('*') 
			-> order( \$order_col, \$order_sort) 
			-> page(\$page, 6);

		\$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( \$order_col, \$order_sort )));

		\$this  -> set( 
			array( 
				'page' => \$page, 
				'order' => \$order_string,
				'lastpage' => \$result['pages'], 
				'data' => \$result['paged'],
			)
		);

		return \$result;
	}

}
?>
MODEL;
	return $model;
}


function view($name,$u_name, $return) {
	$index = <<<VIEW
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
			<form action = "?$name/post/" method="post">
				<input name = "<?php echo \$this -> name ?>" type="text" />
				<input type = "submit" value = "Add"/>
			</form>
			</p>

			<?php	if( isset( \$this -> model -> data['show']) ):	?>
			<h2>List</h2>
			<ul>
				<?php foreach( \$this -> model -> data['show'] as \$row) :	?>
				<li>
				<?php foreach( \$row as \$column => \$value) :	?>
				<b> <?php echo \$column; ?>:  <?php echo \$value; ?> </b>
				<?php endforeach; ?>
				<br/>
				<a href='?$name/del/<?php echo \$row['id'] ?>/'> Delete</a></p> 
				</li>
				<?php endforeach; ?>
			</ul>

			<?php else: ?>
			<p>
			The database is empty. <br/> <a href="?$name/form">Click here</a>  to add items.
			</p>
			<?php endif; ?>

			<?php	echo ( isset( \$this -> model -> data['content'] ) ) ? \$this -> model -> data['content'] : "";	?>
		</blockquote>
	</div>
</div>


<p>
<img src='<?php echo DEFAULT_MEDIA_PATH .'img/miniMVC.png'?>' />
</p>
VIEW;

	$form = <<<VIEW
<div class = 'row' >
	<div class = 'span4' >
		<form class = "form-stacked" action = "?<?php echo \$this -> name ?>/post/" method = "post">
			<label>ID: </label>  <input id = "id" name = "id" type="text"> <br/>
			<label>Entry: </label> <br/>
			<textarea id = "<?php echo \$this -> name ?>-field" name = "<?php echo \$this -> name ?>" type="text" rows="10" cols="50" ></textarea>
			<p>
			<input class = "Primary btn large btn-primary btn-large" type = "submit" value = "Submit"/>
			</p>
		</form>
	</div>
	<div class = 'span5' >
		<h1>Add</h1>
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

	$gallery = <<<VIEW
<div class = 'row'>
	<div class = 'span5'>
		<h1> View</h1>
		<hr>
		<br/>
	</div>

	<div class = 'span6'>
		<br/>
		<br/>
		<?php if( isset(  \$this->model->page )): ?>
		<a class = 'btn btn-info' href='?<?php echo '$name/gallery/' . \$this-> model -> page .  VAR_SEPARATOR . 'id' . VAR_SEPARATOR . 'ASC' ?>'>Order by ID</a> 
		<a class = 'btn btn-info' href='?<?php echo '$name/gallery/' . \$this-> model -> page .  VAR_SEPARATOR . '$name' . VAR_SEPARATOR . 'ASC' ?>'>Order by Name</a>
		<?php endif; ?>

		<?php	if( isset( \$this -> model -> data) ):	?>
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
	<?php foreach( \$this -> model -> data as \$row) :	?>
	<tr>
		<td>
			<?php foreach( \$row as \$column => \$value) :	?>
			<?php echo \$value ?> 
		</td>
		<td>
			<?php endforeach; ?>
			<br/> <a class ='btn btn-danger' href='?<? echo \$this -> name ?>/del/<?php echo \$row['id'] ?>'> Delete</a></p> 
		</td>

	</tr>
	<?php endforeach; ?>

</table>

<div class="pagination">
	<ul>
		<?php if( isset(  \$this->model->page )): ?>
		<?php \$count = count(\$this -> model -> data); ?>
		<?php if(\$this -> model -> page != 1): ?>
		<li class="prev"><a href="?<?php echo \$this -> name ?>/gallery/<?php echo (\$this->model->page - 1).(\$this->model->order) ?>">&larr; Previous</a></li>
		<?php endif; ?>
		<?php for( \$i = \$this->model->page; \$i <= \$this -> model -> lastpage; \$i++) : ?>
		<li><a href="?<?php echo \$this -> name ?>/gallery/<?php echo \$i.(\$this->model->order)  ?>"><?php echo \$i ?></a></li>
		<?php endfor; ?>
		<?php if(\$this -> model -> page != \$this -> model -> lastpage): ?>
		<li class="next"><a href="?<?php echo \$this -> name ?>/gallery/<?php echo (\$this->model->page + 1).(\$this->model->order) ?>">Next &rarr;</a></li>
		<?php endif; ?>
		<?php endif; ?>
	</ul>
</div>

<?php else: ?>
No results!
<?php endif; ?>
VIEW;

	$about = <<<VIEW
<h1> About</h1>
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

	return $$return;
}


// DATABASE MODIFYING FUNCTIONS
// 
//  db_connect()
//  db_disconnect()
//  makeTable()
//  deleteTable()
//  openDB()

function db_connect(){
	$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
	mysql_select_db(DB_DATABASE, $link);
}

function db_disconnect(){
	$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);	
	mysql_close($link);
}

function makeTable($name){
	$names = $name . 's';
	$query = "create table $names (	id integer not null primary key auto_increment, $name varchar(128) not null );";
	db_connect();
	mysql_query($query) or die("Query failed: $query \nReason: " . mysql_error() ." \n" );
	echo "Created $name table!\n";
	db_disconnect();
}

function deleteTable($name){
	if( ($name !='') && ($name != '*') ){
		$query = "drop table $name".'s'; 
		db_connect();
		mysql_query($query) or die("Query failed: $query \nReason: " . mysql_error() ." \n" );
		db_disconnect();
		echo "Deleted $name table! \n";
	}
}

function openDB(){
	db_connect();
	echo "Connected to database: " . DB_DATABASE . "\n";
	echo "Enter a Query: \n";
	$query = fgets(STDIN);	
	mysql_query($query) or die("Query failed: $query \nReason: " . mysql_error() ." \n" );
	db_disconnect();
	echo "Query sucessfully executed.\n";
}

// FILE CREATION FUNCTIONS
//
// create()
// generate()
// undo()

function create($file,$data){
	$handle = fopen($file,'w') or die ("Can't open file.");
	fwrite($handle,$data);
	fclose($handle);
}

function generate($name,$type){

	if ($type == 'c'){
		echo "Creating controller for $name...\n";
		$class = $name . 'Controller';
		$template = controller(ucwords($class), ucwords($name) );
		$path =  SERVER_ROOT . '/controllers/' . $name . '.php';
		create($path, $template);
	}

	if ($type == 'm'){
		echo "Creating model for $name...\n";
		$template = model(ucwords($name),$name);
		$path =  SERVER_ROOT . '/models/' . $name . '.php';
		create($path, $template);
	}

	if ($type == 'v'){
		echo "Creating view for $name...\n";
		$dir =  SERVER_ROOT . '/views/' . $name . '/';
		mkdir( $dir , 0755 ) or die("Couldn't create directory");
		create($dir . 'index.php' , view( $name, ucwords($name), 'index' ) );
		create($dir . 'form.php' , view( $name, ucwords($name), 'form' ) );
		create($dir . 'gallery.php' , view( $name, ucwords($name), 'gallery' ) );
		create($dir . 'about.php' , view( $name, ucwords($name), 'about' ) );
	}

	echo "Completed \n";
}

function undo($name){
	$paths['c'] =  SERVER_ROOT . '/controllers/' . $name . '.php';
	$paths['m'] =  SERVER_ROOT . '/models/' . $name . '.php';
	$paths['vi'] =  SERVER_ROOT . '/views/' . $name . '/' . 'index.php';
	$paths['vg'] =  SERVER_ROOT . '/views/' . $name . '/' . 'gallery.php';
	$paths['vf'] =  SERVER_ROOT . '/views/' . $name . '/' . 'form.php';
	$paths['va'] =  SERVER_ROOT . '/views/' . $name . '/' . 'about.php';
	$dir =  SERVER_ROOT . '/views/' . $name;
	foreach ($paths as $path){
		unlink($path);
		echo "Removed $path \n";
	}
	rmdir($dir);
	echo "Removed $name directory\n";
}

// ARGUMENT PROCESSING  
// mvc()

function mvc($args){
	foreach ( $args as $arg => $value) {
		if ($arg == 'c' || $arg == 'm' || $arg == 'v') 
			$mvc[$arg] = $value; 
		elseif ($arg =='mvc')
			foreach( array('m','v','c') as $value)
				$mvc[$value] = $args[$arg];
	}
	return $mvc;
}


// PRINT MESSAGES
// 
// printHelp()

function printHelp(){
	$help = <<<HELP
	Generate.php - Automatic MVC scaffold generator for miniMVC
	usage: ./generate.php [option] [name]	

	Commands: 
	--mvc "blah"		: Create model,view and controller for blah.
	-m "blah"		: Create model for blah.
	-c "blah"		: Create controller for blah.
	-v "blah"		: Create view for blah.
	--table "blah"		: Generate table and column for blah.
				 * Requires database settings to be configured in config.php.
	--undo "blah"		: Delete MVC scaffold for blah. 
	--undotable "blah"	: Delete database and column for blah. 
				 * Requires database settings to be configured in config.php.
	-h or --help 		: Displays this help text.


HELP;
	echo $help;
}

// Running Script
//

$args = getopt("c:m:v:p:u:h", array('mvc:','undo:','table:','undotable:','crud','help','opendb'));

if( isset($args['mvc']) ||  isset($args['m'])  ||  isset($args['v'])  || isset($args['c'])   ){
	$mvc = mvc($args);
	foreach ( $mvc as $type => $value) {
		generate($value,$type);
	}
	echo "Generation Complete. \n";
}
if( isset($args['undo']) ){
	undo($args['undo']);
}

if( isset($args['table']) ){
	makeTable($args['table']);
}

if( isset($args['undotable']) ){
	deleteTable($args['undotable']);
}

if( isset($args['opendb']) ){
	openDB();
}

if( isset($args['h']) or  isset($args['help'])  ){
	printHelp();
}

?>
