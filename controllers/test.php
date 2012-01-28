<?php

class TestController extends Controller{

	function __construct(){
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
		$this -> useController( array( 'controller' => 'user', 'method' => 'start'), true );
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
		$result = $this -> model -> select ('*');
		$this -> model -> data["show"] = $result;
		$this -> useView('gallery');
	}

	function gallery($page = 1, $order_col = null, $order_sort = null){
		$result = $this -> model -> paged_select('*', $page, 2 ,array($order_col,$order_sort));
		$this -> model -> data["show"] = $result['paged'];
		$this -> model -> page = $page;
		$this -> model -> order = '+'.$order_col;
		$this -> model -> lastpage = $result['pages']; 
		$this -> model -> orderOpts(); 
		$this -> model -> data['show_clips'] = false; 
		$this -> useView('gallery');
	}

	function order($column, $sort = 'DESC' ){
		$result = $this -> model -> select ('*', null, null, array( $column, $sort) );
		$this -> model -> data["show"] = $result;
		$this -> model -> orderOpts(); 
		$this -> useView('gallery');
	}

	function about(){
		$this -> useView('about');
	}

	function db( $sql_query = "select * from test;" ){
		$sql_query = urldecode($sql_query);
		$test_dbquery = $this -> model -> query($sql_query);
		echo "<a href='?'>Back</a><pre>" . print_r($test_dbquery, true). "</pre>";
	}

	function say($phrase = 'You said nothing' ){
		$this -> model -> data['content'] = urldecode($phrase);
		$this -> model -> data['r_bot_sidebar'] = "<a href='?test/'>Back</a>";
		$this -> useView();
	}
}

?>
