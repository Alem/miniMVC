<?php

class TestController extends Controller
{

	/**
	 * index() - Loads default 'index' view
	 */
	function actionIndex()
	{
		$session = new Session();
		$config  = new Config();

		$this -> view('index', $this -> model() -> data + $session -> data + $config -> load('application') );
	}


	/**
	 * form() - Loads 'form' view
	 */
	function actionForm()
	{
		$session = new Session();
		$config  = new Config();

		$this -> view('form', $this -> model() -> data + $session -> data + $config -> load('application') );
	}


	/**
	 * post() - Recieves POST data and hands it to model for database insertion
	 */
	function actionPost()
	{
		$request = new Request();

		$this -> model() -> insertTest( $request -> post );
		$this -> prg('gallery');
	}


	/**
	 * del() - Directly remove database data 
	 */
	function actionDel($value )
	{
		$this -> model() -> deleteTest ( $value, 'id' );
		$this -> prg('gallery');
	}


	/**
	 * show() - Display all information for specifed primary Id
	 *
	 * Retrieves all data for specified id and passes it to 'gallery' view
	 */
	function actionShow( $id )
	{
		$session = new Session();
		$config  = new Config();


		$this -> model() -> getTest($id);
		$this -> view('gallery', $this -> model() -> data + $session -> data + $config -> load('application') );
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
	function actionGallery($page = 1, $order_col = null, $order_sort = null)
	{
		$session = new Session();
		$config  = new Config();

		$this -> model() -> galleryTest( $order_col, $order_sort, $page  );
		$this -> view('gallery', $this -> model() -> data + $session -> data + $config -> load('application') );
	}


	/**
	 * about() - Run of the mill 'about' page
	 */
	function actionAbout()
	{
		$session = new Session();
		$config  = new Config();

		$this -> view('about', $this -> model() -> data + $session -> data + $config -> load('application') );
	}

}

?>
