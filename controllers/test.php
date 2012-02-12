<?php

class TestController extends Controller{


	function __construct(){
		parent::__construct();
		$this -> model -> nav = $this -> menu -> nav();
		$this -> model -> sidebar = $this -> menu -> sidebar();
	}


	// index() - Loads default 'index' view
	
	function index(){
		$this -> useView();
	}


	// form() - Loads 'form' view

	function form(){
		$this -> useView('form');
	}


	// post() - Recieves POST data and hands it to model for database insertion
	
	function post(){
		$form_fields = array_keys($_POST);
		$this -> model 
			-> insert( $_POST, $form_fields) 
			-> run();
		$this -> prg('gallery');
	}


	// add() - Directly insert data from URL. TEST ONLY

	function add($item){
		$this -> model -> insert($item) -> run();
		$this -> show();
	}


	// del() - Directly remove database data from URL. TEST ONLY

	function del($value, $column = null){
		$this -> model -> remove() -> where ( $value, $column ) -> run();
		$this -> prg('gallery');
	}


	// edit() - Updates specified values
	//
	// $ref - Reference value
	// $new - New value to be set
	// $ref_column - Reference column 
	// $new_column - Column of new value

	function edit($ref, $new, $column_ref = null, $column_new = null){
		$this -> model -> update( $new, $new_column) -> where($ref, $ref_column) -> run();
		$this -> show();
	}

	
	// show() - Display all information for specifed primary Id
	//
	// Retrieves all data for specified id and passes it to 'gallery' view

	function show( $id ){
		$result = $this -> model 
			-> select ('*') 
			-> where($id,'id') 
			-> run();

		$this -> model -> data = $result;
		$this -> useView('gallery');
	}


	// gallery() - A gallery of items
	//
	// Displays items 'tests' in gallery form.
	//
	// $page - Current page, defaults to 1
	// $order_col 	- The column to order by
	// $order_sort 	- The sort to use

	function gallery($page = 1, $order_col = null, $order_sort = null){
		$result = $this -> model 
			-> select('*') 
			-> order( $order_col, $order_sort) 
			-> page($page, 6);

		$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( $order_col, $order_sort )));

		$this -> model -> set( 
			array( 
				'page' => $page, 
				'order' => $order_string,
				'lastpage' => $result['pages'], 
				'data' => $result['paged'],
			)
		);

		$this -> useView('gallery');
	}


	// about() - Run of the mill 'about' page

	function about(){
		$this -> useView('about');
	}

}

?>
