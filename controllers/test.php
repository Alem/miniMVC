<?php

class TestController extends Controller{

	function __construct(){
		// Is assigned name,classname,filename, and model after instantiation.
	}

	function index(){
		$this -> show();
	}

	function form(){
		$this -> useView('test/form');
	}

	function mform(){
		$this -> useView('test/mform');
	}

	// post - inserts POST data
	//
	// If POST contains multiple keys then insert as string of multiple values 1','2','3
	// and multiple columns defined as string. Could use POST keys but those can be tampered.
	// Otherwise insert directly. 
	// NOTE: Implement data sanitization. 

	function post(){
		// $multi = Array( 'id' => $_POST['id'], 'test' => $_POST['item'] );
		if( count($_POST) > 1 ){
			$multi = implode("','", $_POST);
			$multi_column = 'id,test';
			$this -> model -> insert($multi, $multi_column);
			$this -> model -> data['content'] = "Values Added.";
		}else{
			$item = $_POST['item'];
			$this -> model -> insert($item);
			$this -> model -> data['content'] = "$item Added.";
		}
		$this -> show();
	}

	function add($item){
		$this -> model -> insert($item);
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}

	function del($id){
		$this -> model -> remove($id);
		$this -> model -> data['content'] = "$id Deleted.";
		$this -> show();
	}
	
	function set($old, $new, $column_old = null, $column_new = null){
		$this -> model -> update( $old, $new, $column_old, $column_new);
		$this -> model -> data['content'] = "$old changed to $new.";
		$this -> show();
	}

	function show(){
		$query_result = $this -> model -> select ('*', 'id', array( 'col'=>'id','sort' => 'DESC') );
		$this -> model -> data["show"] = $query_result;
		$this -> useView();
	}

	function db( $sql_query = "select * from test;" ){
		$sql_query = urldecode($sql_query);
		$test_dbquery = $this -> model -> query($sql_query);
		echo "<pre>" . print_r($test_dbquery, true). "</pre>";
	}

	function countto( $num ){
			$this -> model -> data['content'] = null;
		for($x=1; $x <= $num; $x++){
			$this -> model -> data['content'] .= $x;
		}
		$this -> useView();
	}

	function say($phrase = 'You said nothing' ){
		$this -> model -> data['content'] = urldecode($phrase);
		$this -> model -> data['r_bot_sidebar'] = "<a href='?test/'>Back</a>";
		$this -> useView();
	}

}

?>
