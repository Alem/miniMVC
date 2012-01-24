<?php

class TestController extends Controller{


	function __construct(){
		// Is assigned name,classname,filename, and model after instantiation.
	}


	function index(){
		$this -> useView();
	}


	function form(){
		$this -> useView('form');
	}


	function mform(){
		$this -> useView('mform');
	}


	function post(){
		$form_fields = ( count( $_POST ) > 1 ) ? $form_fields = array('id','test') : $form_fields = array('test');
		$this -> user = new User;
		if ( $this -> user -> timeSince('post', 5) ){
			$this -> user -> timeSince('post'); 
			$this -> model -> insert( $_POST, $form_fields);
			$this -> model -> data['content'] = "Post Added.";
		}
		$this -> prg('show');
	}


	function add($item){
		$this -> model -> insert($item);
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}


	function del($value, $column = null){
		$this -> model -> remove($value,$column);
		$this -> model -> data['content'] = "$value Deleted.";
		$this -> prg('show');
	}
	

	function set($old, $new, $column_old = null, $column_new = null){
		$this -> model -> update( $old, $new, $column_old, $column_new);
		$this -> model -> data['content'] = "$old changed to $new.";
		$this -> show();
	}


	function show(){
		$query_result = $this -> model -> select ('*', 'id', array( 'col'=>'id','sort' => 'DESC') );
		$this -> model -> data["show"] = $query_result;
		$this -> useView('gallery');
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
