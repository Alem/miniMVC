<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
	}

	function insertPOST(){
		$form_fields = array_keys($_POST);
		$this	-> SQL()
			-> insert( $_POST, $form_fields) 
			-> run();
	}

	function deleteTest ( $value, $column ) {
		$this  -> SQL() -> remove() -> where ( $value, $column ) -> run();
	}

	function editTest($new, $new_column, $ref, $ref_column ) {
		$this  -> SQL() -> update( $new, $new_column) -> where($ref, $ref_column) -> run();
	}

	function getTest($id ) {
		$result = $this -> SQL()
			-> select ('*') 
			-> from()
			-> where($id,'id') 
			-> run();

		$this  -> set( 'data',  $result);

		return $result;
	}

	function galleryTest( $order_col, $order_sort, $page){
		$result = $this -> SQL()
			-> select('*') 
			-> from()
			-> order( $order_col, $order_sort) 
			-> page ($page, 6);

		$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( $order_col, $order_sort )));

		$this  -> set( 
			array( 
				'page' => $page, 
				'order' => $order_string,
				'lastpage' => $result['pages'], 
				'data' => $result['paged'],
			)
		);

		return $result;
	}
}

?>
