<?php

class Model extends Generator{


	function scaffold($base_name){

		$name = ucwords ( $base_name );
		$l_name = $base_name;

		$joining = null;
		$external_select = null;
		$external_table_arrays = null;

		if ( isset( Database::open() -> filtered_columns ) ){

			$table_columns = implode( "','", Database::open() -> table_columns );
			$form_columns = implode( "','", Database::open() -> filtered_columns );

			foreach ( Database::open() -> filtered_columns  as $column ){

				if (  ( preg_match( '/_id/', $column ) )  ){
					$stripped_column = preg_replace( '/_id/', '', $column);
					$external_table_arrays .= <<<external

			'{$stripped_column}' => array( '$stripped_column' ),
external;
					$external_select .= <<<select

		\$this -> select ( \$this -> columns['external']['{$stripped_column}'], '{$stripped_column}s' );

select;
					$joining .= <<<JOINING

		\$this -> joining ('{$stripped_column}s', '$column', 'id', 'LEFT OUTER' );
JOINING;
				}
			}
		}else{
			$table_columns =& $l_name;
			$form_columns =& $l_name;
		}

		$model =  <<<MODEL
<?php

class $name extends Model{


	public \$columns = array(

		'table' => array ( '$table_columns' ),

		'form' => array ( '$form_columns' ),

		'ownership' => 'user_id',

		'external' => array(
			$external_table_arrays
		)
	);


	function __construct(){
		parent::__construct();
	}


	function insertPOST( \$user_id = null ){
		if ( isset( \$user_id ) ){
			\$_POST[ \$this -> columns['ownership'] ] = \$user_id;
			\$this -> columns['form'][] = \$this -> columns['ownership'];
		}

		\$this	-> insert( \$_POST, \$this -> columns['form'] ) 
			-> run();

		return \$this -> last_insert_id;
	}


	function delete$name ( \$id, \$user_id = null) {
		\$this  -> remove() 
			-> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> where(\$user_id, \$this -> columns['ownership'] );
		\$this -> run();
	}


	function update$name(\$id = null, \$user_id = null ) {
		\$this 	-> update( \$_POST, \$this -> columns['form'] );

		if ( isset( \$id ) )
			\$this	-> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> where(\$user_id, \$this -> columns['ownership'] );

		\$this -> run();
	}


	function get$name(\$id = null, \$user_id = null ) {
		\$this -> select ( \$this -> columns['table'] );
		$external_select
		\$this -> from();
		$joining

		if ( isset( \$id ) )
			\$this	-> where( \$id,'id' );
		if ( isset( \$user_id ) )
			\$this	-> where(\$user_id, \$this -> columns['ownership'] );

		\$result = \$this -> run();

		\$this  -> set( 'data',  \$result);

		return \$result;
	}


	function gallery$name( \$order_col, \$order_sort, \$page, \$user_id = null){
		\$this -> select ( \$this -> columns['table'] );
		$external_select
		\$this -> from();
		$joining

		if ( isset( \$user_id ) )
			\$this	-> where(\$user_id, \$this -> columns['ownership'] );
		\$result = \$this 
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

}


?>
