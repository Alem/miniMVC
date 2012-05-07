<?php

class TestController extends Controller{


	/**
	 * index() - Loads default 'index' view
	 */
	function actionIndex(){
		$this -> view();
	}


	/**
	 * form() - Loads 'form' view
	 */
	function actionForm(){
		$this -> view('form');
	}


	/**
	 * post() - Recieves POST data and hands it to model for database insertion
	 */
	function actionPost(){
		$request = new Request();
		$this -> model() -> insertTest( $request -> post );
		$this -> prg('gallery');
	}


	/**
	 * del() - Directly remove database data 
	 */
	function actionDel($value ){
		$this -> model() -> deleteTest ( $value, 'id' );
		$this -> prg('gallery');
	}


	/** 
	 * edit() - Updates specified values
	 *
	 * @param string $ref 		Reference value
	 * @param string $new 		New value to be set
	 * @param string $ref_column 	Reference column 
	 * @param string $new_column  	Column of new value
	 */
	function actionEdit($ref, $new, $column_ref = null, $column_new = null){
		$this -> model() -> editTest($new, $new_column, $ref, $ref_column );
		$this -> show();
	}

	
	/**
	 * show() - Display all information for specifed primary Id
	 *
	 * Retrieves all data for specified id and passes it to 'gallery' view
	 */
	function actionShow( $id ){
		$this -> model() -> getTest($id);
		$this -> view('gallery');
	}


	/**
	 * gallery() - A gallery of items 
	 *
	 * Displays items 'tests' in gallery form.
	 *
	 * @param mixed  $page 		Current page, defaults to 1
	 * @param string $order_col 	The column to order by
	 * @param string $order_sort 	The sort to use
	 */
	function actionGallery($page = 1, $order_col = null, $order_sort = null){
		$this -> model() -> galleryTest( $order_col, $order_sort, $page  );
		$this -> view('gallery');
	}


	/**
	 * about() - Run of the mill 'about' page
	 */
	function actionAbout(){
		$this -> view('about');
	}

}

?>
