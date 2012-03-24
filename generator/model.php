<?php

class Model extends Generator{


	function scaffold(){

		$uname = $this -> uname;
		$name = $this -> name;

		$joining = null;
		$external_select = null;
		$external_table_arrays = null;

		if ( isset( Database::open() -> filtered_columns ) ):

			$table_columns = implode( "','" , Database::open() -> table_columns );
			$form_columns  = implode( "','" , Database::open() -> filtered_columns );
		
			foreach ( Database::open() -> filtered_columns  as $column ):
				if (  ( preg_match( '/_id/', $column ) )  ):
					$stripped_column = preg_replace( '/_id/', '', $column);

/*********************** BEGIN HTML ****************************************************************/
					$external_table_arrays .= <<<external

			'{$stripped_column}' => array( '$stripped_column' ),
external;
######################## END HTML   #################################################################
/*********************** BEGIN HTML ****************************************************************/
					$external_select .= <<<select

		\$this -> select ( \$this -> columns['external']['{$stripped_column}'], '{$stripped_column}s' );

select;
######################## END HTML   #################################################################
/*********************** BEGIN HTML ****************************************************************/
					$joining .= <<<JOINING

		\$this -> joining ('{$stripped_column}s', '$column', 'id', 'LEFT OUTER' );
JOINING;
######################## END HTML   #################################################################

				endif;
			endforeach;
			
			if ( !empty ( $external_table_arrays ) ):
/*********************** BEGIN HTML ****************************************************************/
			$getBlankForX = <<<externalFunction

	/**
	 * get{$uname}sForX - Retrieves $uname(s) and orders them by the specified external column
	 *
	 * @param string  \$external_column  The name of the external column
	 * @param integer \$id               The primary ID of the $uname to delete.
	 * @param mixed   \$user_id          The value that matches the row's value for the 'ownership' column.
	 * @return array  \$x                The array of table values organized for each external column value
	 */
	function get{$uname}sForX ( \$external_column , \$id = null,  \$user_id = null ) {
		\$result = \$this -> get{$uname}( \$id , \$user_id );
		
		foreach ( \$result as \$row ){
			if ( !isset ( \$x[ \$row[ \$external_column ]['total'] ] ) )
				\$x[ \$row[ \$external_column  ] ][ 'total' ] = 0;

			foreach( \$this -> column['table'] as \$table_field ) 
				\$x[\$row[\$external_column ]] [\$row['id']] [\$table_field] = \$row[ \$table_field ]; 

			\$x[ \$row[ \$external_column ] ][ 'total' ]++; 
		}

		return \$x;
	}
externalFunction;
######################## END HTML   #################################################################
			else:
				$getBlankForX = null;
			endif;


		else:
			$table_columns =& $name;
			$form_columns =& $name;
		endif;

		$model =  <<<MODEL
<?php

class $uname extends Model{

	/**
 	 * @var array The columns array identifies and classifies the columns of the tables the Model interacts with. 
	 */
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


	/**
 	 * insertPOST - Inserts POST from form into table.
	 *
	 * @return integer The Primary ID of the last/recently inserted row. 
	 */
	function insertPOST( \$user_id = null ){
		if ( isset( \$user_id ) ){
			\$_POST[ \$this -> columns['ownership'] ] = \$user_id;
			\$this -> columns['form'][] = \$this -> columns['ownership'];
		}

		\$this	-> insert( \$_POST, \$this -> columns['form'] ) 
			-> run();

		return \$this -> last_insert_id;
	}


	/**
 	 * delete$uname - Deletes $uname from table
	 *
	 * @param integer \$id      The primary ID of the $uname to delete.
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 */
	function delete$uname ( \$id, \$user_id = null) {
		\$this  -> remove() 
			-> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> where(\$user_id, \$this -> columns['ownership'] );
		\$this -> run();
	}


	/**
 	 * update$uname - Updates the specified $uname
	 *
	 * @param integer \$id      The primary ID of the $uname to update.
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 */
	function update$uname(\$id = null, \$user_id = null ) {
		\$this 	-> update( \$_POST, \$this -> columns['form'] );

		if ( isset( \$id ) )
			\$this	-> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> where(\$user_id, \$this -> columns['ownership'] );

		\$this -> run();
	}


	/**
 	 * get$uname - Retrieves the $uname from table
	 *
	 * @param  integer \$id      The primary ID of the $uname to retrieve.
	 * @param  mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 * @return array             The array of matching table rows returned by the SQL query.
	 */
	function get$uname(\$id = null, \$user_id = null ) {
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


	/**
 	 * gallery$uname - Displays multiples items from the table
	 *
	 * @param integer \$page        Current page, defaults to 1
	 * @param string  \$order_col   The column to order by
	 * @param string  \$order_sort  The sort to use
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 * @return array                The 'pagination-friendly' array returned by the Model::page method.
	 */
	function gallery$uname( \$order_col, \$order_sort, \$page, \$user_id = null){
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


	$getBlankForX 
}
?>
MODEL;
		return $model;
	}

}


?>
