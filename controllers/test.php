<?php

class TestController extends Controller{


	function __construct(){
		parent::__construct();
		$this -> model -> nav = $this -> menu -> nav();
		$this -> model -> sidebar = $this -> menu -> sidebar();
	}


	function index(){
		$this -> useView();
	}


	function form(){
		$this -> useView('form');
	}


	function post(){
		$form_fields = array_keys($_POST);
		$this -> useController( array( 'controller' => 'user', 'method' => 'start'), true );
		$num_posts  = $this -> user -> sessionGet('num_posts');
		if ( $num_posts < 15 ){
			$this -> user -> sessionSet('num_posts', ($num_posts+1) );
			$this -> model -> insert( $_POST, $form_fields) -> run();
			$this -> prg('gallery');
		}
		$this -> model -> data['content'] = "Already posted $num_posts times";
		$this -> useView();
	}


	function add($item){
		$this -> model -> insert($item) -> run();
		$this -> show();
	}


	function del($value, $column = null){
		$this -> model -> remove( $value, $column ) -> run();
		$this -> prg('gallery');
	}


	function set($old, $new, $column_old = null, $column_new = null){
		$this -> model -> update( $old, $new, $column_old, $column_new) -> run();
		$this -> show();
	}


	function show(){
		$result = $this -> model -> select ('*') -> run();
		$this -> model -> data = $result;
		$this -> useView('gallery');
	}


	function gallery($page = 1, $order_col = null, $order_sort = null){
		$result = $this -> model -> select('*') -> order( $order_col, $order_sort) -> page($page, 6);
		$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( $order_col, $order_sort )));
		$this -> model -> set( 
			array( 
				'page' => $page, 
				'order' => $order_string,
				'lastpage' => $result['pages'], 
				'data' => $result['paged'],
			)
		);
		$this -> model -> orderOpts(); 
		$this -> useView('gallery');
	}


	function about(){
		$this -> useView('about');
	}


	function db( $sql_query = "select * from test" ){
		$sql_query = urldecode($sql_query);
		$this -> model -> query = ($sql_query);
		$result = $this -> model -> run();
		$this -> model -> data['content'] =  "<pre>" . print_r($result, true). "</pre>";
		$this -> useView();
	}


	function say($phrase = 'You said nothing' ){
		$this -> model -> data['content'] = urldecode($phrase);
		$this -> useView();
	}
}

?>
