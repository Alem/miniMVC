#!/usr/bin/php
<?php

require_once('config.php');

class Scaffold {


	// MVC TEMPLATE GENERATION FUNCTIONS
	// controller()
	// model()
	// view()

	// FILE CREATION FUNCTIONS
	//
	// create()
	// generate()
	// undo()


	public $views = array( 'index','form','gallery');


	function create($file,$data){
		$handle = fopen($file,'w') or die ("Can't open file.");
		fwrite($handle,$data);
		fclose($handle);
	}


	function generate($name,$type){

		if ($type == 'c'){
			echo "Creating controller for $name...\n";
			$class = $name . 'Controller';
			$template = $this -> controller(ucwords($class), ucwords($name) );
			$path =  SERVER_ROOT . '/controllers/' . $name . '.php';
			$this -> create($path, $template);
		}

		if ($type == 'm'){
			echo "Creating model for $name...\n";
			$template = $this -> model(ucwords($name),$name);
			$path =  SERVER_ROOT . '/models/' . $name . '.php';
			$this -> create($path, $template);
		}

		if ($type == 'v'){
			echo "Creating view for $name...\n";
			$dir =  SERVER_ROOT . '/views/' . $name . '/';
			mkdir( $dir , 0755 ) or die("Couldn't create directory");
			foreach ($this -> views as $view)
				$this -> create($dir . $view . '.php' , $this -> view( $name, ucwords($name), $view ) );
		}

		echo "Completed \n";
	}


	function undo($name){
		$paths[] =  SERVER_ROOT . '/controllers/' . $name . '.php';
		$paths[] =  SERVER_ROOT . '/models/' . $name . '.php';
		foreach ($this -> views as $view)
			$paths[] =  SERVER_ROOT . '/views/' . $name . '/' . $view . '.php';
		$dir =  SERVER_ROOT . '/views/' . $name;
		foreach ($paths as $path){
			unlink($path);
			echo "Removed $path \n";
		}
		rmdir($dir);
		echo "Removed $name directory\n";
	}


	function controller($name, $short_name) {

		$controller = <<<CONT
<?php

class $name extends Controller{

	function __construct(){
		parent::__construct();
	}


	// index() - Loads default 'index' view

	function index(){
		#\$this -> useView();
		\$this -> prg('gallery');
	}


	// form() - Loads 'form' view

	function form(){
		\$this -> useView('form');
	}


	// post() - Recieves POST data and hands it to model for database insertion

	function post(){
		if ( \$id = Session::open() -> getThenDel('editing_clip_id') )
			\$this -> model -> update$short_name(\$id);
		else
			\$id = \$this -> model -> insertPOST();
		\$this -> prg('show', \$id);
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

	function edit(\$id){
			Session::open() -> set('editing_clip_id',\$id);
			\$this -> model -> saved_fields =  reset( \$this -> model -> get$short_name(\$id));
			\$this -> useView('form');
	}


	// show() - Display all information for specifed primary Id
	//
	// Retrieves all data for specified id and passes it to 'gallery' view

	function show( \$id ){
		\$this -> model -> get$short_name(\$id);
		\$this -> model -> set( 'show', true );
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


}
?>
CONT;
		$is_main = <<<is_main
	// about() - Run of the mill 'about' page

	function about(){
		\$this -> useView('about');
	}
is_main;
		return $controller;
	}


	function model($name,$l_name){
		$model =  <<<MODEL
<?php

class $name extends Model{

	public \$form_columns = array(
		'$l_name'	
	);

	function __construct(){
		parent::__construct();
	}

	function insertPOST(){
		\$form_fields = array_keys(\$_POST);
		\$this	-> insert( \$_POST, \$form_fields) 
			-> run();
		return \$this -> last_insert_id;
	}

	function delete$name ( \$value, \$column ) {
		\$this  -> remove() 
			-> where ( \$value, \$column ) 
			-> run();
	}

	function update$name(\$id ) {
		\$this 	-> update( \$_POST, \$this  -> form_columns ) 
			-> where(\$id, 'id') 
			-> run();
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
		<h2>$u_name</h2>
	</div>
	<div class ='span4 well'>
	</div>
</div>
VIEW;

	$form = <<<VIEW
<div class = 'row' >
	<div class = 'span4' >
		<form class = "form-stacked" action = "?<?php echo \$this -> name ?>/post/" method = "post">
			<label>Entry:
			<br/>
			<textarea id = "<?php echo \$this -> name ?>-field" name = "<?php echo \$this -> name ?>" type="text" rows="10" cols="50" ><?php if (isset( \$this -> model -> saved_fields['$name'] )) echo \$this -> model -> saved_fields['$name'] ?></textarea>
			</label>
			<p>
			<?php if (Session::open() -> get('editing_clip_id') ): ?>
			<input class = "Primary btn large btn-primary btn-large" type = "submit" value = "Update"/>
			<?php else: ?>
			<input class = "Primary btn large btn-primary btn-large" type = "submit" value = "Submit"/>
			<?php endif; ?>
			</p>
		</form>
	</div>
	<div class = 'span5 well' >
		<?php if (Session::open() -> get('editing_clip_id') ): ?>
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

	$gallery = <<<VIEW
<div class = 'row'>
	<div class = 'span5'>
	<?php if ( isset ( \$model -> show ) ): ?>
		<h1>$u_name</h1>
	<?php else: ?>
		<h1> Gallery</h1>
	<?php endif; ?>
		<hr>
		<br/>
	</div>

	<div class = 'span6'>
		<br/>
		<br/>
		<?php if( isset(  \$model->page )): ?>
		<a class = 'btn btn-info' href='<?php echo '$name/gallery/' . \$model -> page .  VAR_SEPARATOR . 'id' . VAR_SEPARATOR . 'DESC' ?>'>Order by Recency</a> 
		<a class = 'btn btn-info' href='<?php echo '$name/gallery/' . \$model -> page .  VAR_SEPARATOR . '$name' . VAR_SEPARATOR . 'ASC' ?>'>Order by Name</a>
		<?php endif; ?>

		<?php	if( isset( \$model -> data) ):	?>
	</div>
</div>

<?php if ( !isset ( \$model -> show ) ): ?>
<p><a class ='btn btn-large' href='$name/form'>Add $u_name</a></p>
<?php else: ?>
<p><a class ='btn-danger btn-large' href='$name/gallery'>Back to Gallery</a></p>
<?php endif; ?>
<br/>

<table class ='table table-striped'>
	<tr>
		<th> Id </th>
		<th> Item </th>
		<th> Action </th>
	</tr>
	<?php foreach( \$model -> data as \$row) :	?>
	<tr>
		<td>
			<?php foreach( \$row as \$column => \$value) :	?>
			<?php echo \$value ?> 
		</td>
		<td>
			<?php endforeach; ?>
			<?php if ( !isset ( \$model -> show ) ): ?>
			<a class ='btn btn-success' href='$name/show/<?php echo \$row['id'] ?>'>View</a>
			<?php else: ?>
			<a class ='btn btn-info' href='$name/edit/<?php echo \$row['id'] ?>'>Edit</a> 
			<a class ='btn btn-danger' href='$name/del/<?php echo \$row['id'] ?>'>Delete</a>
			<?php endif; ?>
		</td>

	</tr>
	<?php endforeach; ?>

</table>

<div class="pagination">
	<ul>
		<?php if( isset(  \$model->page )): ?>
		<?php \$count = count(\$model -> data); ?>
		<?php if(\$model -> page != 1): ?>
		<li class="prev"><a href="$name/gallery/<?php echo (\$model -> page - 1).(\$model -> order) ?>">&larr; Previous</a></li>
		<?php endif; ?>
		<?php for( \$i = \$model -> page; \$i <= \$model -> lastpage; \$i++) : ?>
		<li><a href="$name/gallery/<?php echo \$i.(\$model -> order)  ?>"><?php echo \$i ?></a></li>
		<?php endfor; ?>
		<?php if(\$model -> page != \$model -> lastpage): ?>
		<li class="next"><a href="$name/gallery/<?php echo (\$model->page + 1).(\$model->order) ?>">Next &rarr;</a></li>
		<?php endif; ?>
		<?php endif; ?>
	</ul>
</div>

<?php else: ?>
No results!
<?php endif; ?>
VIEW;

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
	return $$return;
}
}


class Database{


	// DATABASE MODIFYING FUNCTIONS
	// 
	//  run()
	//  makeTable()
	//  deleteTable()
	//  openDB()


	function run() {
		$db_link = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE,DB_USERNAME,DB_PASSWORD);
		$statement = $db_link -> query($this -> query);
	}


	function makeTable($name){
		$names = $name . 's';
		$this -> query = "create table $names (	id integer not null primary key auto_increment, $name varchar(128) not null );";
		$this -> run();
		echo "Created $name table!\n";
	}


	function deleteTable($name){
		if( !empty($name) ){
			$this -> query = "drop table $name".'s'; 
			$this -> run();
			echo "Deleted $name table! \n";
		}
	}


	function openDB(){
		echo "Connected to database: " . DB_DATABASE . "\n";
		echo "Enter a Query: \n";
		$this -> query = fgets(STDIN);	
		$this -> run();
		echo "Query sucessfully executed.\n";
	}

}


class Processor{


	function __construct(){
		$this -> scaffold = new Scaffold();
		$this -> database = new Database();
	}


	function help(){
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


	function execute($args){

		if( isset($args['mvc']) ||  isset($args['m'])  ||  isset($args['v'])  || isset($args['c'])   ){
			$mvc = $this -> mvc($args);
			foreach ( $mvc as $type => $value) {
				$this -> scaffold -> generate($value, $type);
			}
			echo "Generation Complete. \n";
		}

		if( isset($args['undo']) ){
			$this -> scaffold -> undo($args['undo']);
		}

		if( isset($args['table']) ){
			$this -> database -> makeTable($args['table']);
		}

		if( isset($args['undotable']) ){
			$this -> database -> deleteTable($args['undotable']);
		}

		if( isset($args['opendb']) ){
			$this -> database -> openDB();
		}

		if( isset($args['h']) or  isset($args['help'])  ){
			$this -> help();
		}

	}
}

// Running Script
$args = getopt( "c:m:v:p:u:h", array('mvc:', 'undo:', 'table:', 'undotable:', 'crud', 'help', 'opendb') );
$processor = new Processor();
$processor -> execute ($args);

?>
