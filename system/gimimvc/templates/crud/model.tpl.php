<?php
/**
 * Model template class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 *
 */
class Model extends Template
{

	public function __construct( $name )
	{
		parent::__construct( $name );

		$this -> fileCache() -> path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODEL_PATH;  
	}


	public function manageExternalLinks()
	{
		$joining = null;
		$external_select = null;
		$external_table_arrays = null;

		if ( isset( $this -> queryTool() -> linked_columns ) ):
			foreach ( $this -> queryTool() -> linked_columns  as $stripped_column ):
				//--------------------------------------START HTML
				$external_table_arrays .= <<<external

			'{$stripped_column}' => array( '$stripped_column' ),
external;
		//--------------------------------------END HTML
		//--------------------------------------START HTML
		$external_select .= <<<select

		\$this -> SQL() -> select ( \$this -> columns['external']['{$stripped_column}'], '{$stripped_column}s' );

select;
		//--------------------------------------END HTML
		//--------------------------------------START HTML
		$joining .= <<<JOINING

		\$this -> SQL() -> joining ('{$stripped_column}s', '{$stripped_column}_id', 'id', 'LEFT OUTER' );
JOINING;
		//--------------------------------------END HTML
endforeach;
endif;
return array(
	'external_table_array'  => $external_table_arrays,
	'external_select' 	=> $external_select,
	'joining' 		=> $joining
);
	}

	public function scaffold()
	{

		$uname = $this -> uname;
		$name = $this -> name;
		$table_columns = null;
		$form_columns = null;
		$getBlankForX = null;

		$external_table_data = $this -> manageExternalLinks();
		$joining 		= $external_table_data['joining'];
		$external_select 	= $external_table_data['external_select'];
		$external_table_arrays 	= $external_table_data['external_table_array'];

		if ( !empty ( $this -> queryTool() -> table_columns ) )
			$table_columns = implode( "','" , $this -> queryTool() -> table_columns );
		if ( !empty ( $this -> queryTool() -> filtered_columns ) )
			$form_columns  = implode( "','" , $this -> queryTool() -> filtered_columns );
		if ( !empty ( $this -> queryTool() -> filtered_columns ) )
			$saved_fields  = "'" . implode( "' => null,\n'" , $this -> queryTool() -> filtered_columns ) . "' => null \n";


		if ( !empty ( $external_table_data ) ):


			$getBlankForX =<<<externalFunction

	/**
	 * get{$uname}sByX - Retrieves $uname(s) and orders them by the specified external column
	 *
	 * @param string  \$external_column  The name of the external column
	 * @param integer \$id               The primary ID of the $uname to delete.
	 * @param mixed   \$user_id          The value that matches the row's value for the 'ownership' column.
	 * @return array  \$x                The array of table values organized for each external column value
	 */
	public function get{$uname}sByX ( \$external_column , \$id = null,  \$user_id = null ) 
	{
		\$result = \$this -> get{$uname}( \$id , \$user_id );

		foreach ( \$result as \$row )
		{
			if ( !isset ( \$x[ \$row[ \$external_column ]['total'] ] ) )
				\$x[ \$row[ \$external_column  ] ][ 'total' ] = 0;

			foreach( \$this -> column['table'] as \$table_field ) 
				\$x[\$row[\$external_column ]] [\$row['id']] [\$table_field] = \$row[ \$table_field ]; 

			\$x[ \$row[ \$external_column ] ][ 'total' ]++; 
		}

		return \$x;
	}
externalFunction;
endif;

$model =  <<<MODEL
<?php

class $uname extends Model
{

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

	
	public \$saved_fields = array( $saved_fields );


	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * insert$uname - Inserts $uname data into table.
	 *
	 * @return integer The Primary ID of the last/recently inserted row. 
	 */
	public function insert$uname( \$data, \$user_id = null )
	{
		if ( isset( \$user_id ) )
		{
			\$data[ \$this -> columns['ownership'] ] = \$user_id;
			\$this -> columns['form'][] = \$this -> columns['ownership'];
		}

		\$this	-> SQL() 
			-> insert( \$data, \$this -> columns['form'] ) 
			-> run();

		return \$this -> SQL() -> last_insert_id;
	}


	/**
	 * delete$uname - Deletes $uname from table
	 *
	 * @param integer \$id      The primary ID of the $uname to delete.
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 */
	public function delete$uname ( \$id, \$user_id = null)
	{
		\$this 	-> SQL() 
			-> remove() 
			-> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> SQL() -> where(\$user_id, \$this -> columns['ownership'] );
		\$this -> SQL() -> run();
	}


	/**
	 * update$uname - Updates the specified $uname
	 *
	 * @param integer \$id      The primary ID of the $uname to update.
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 */
	public function update$uname( \$data, \$id = null, \$user_id = null ) 
	{
		\$this 	-> SQL() -> update( \$data, \$this -> columns['form'] );

		if ( isset( \$id ) )
			\$this	-> SQL() -> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> SQL() -> where(\$user_id, \$this -> columns['ownership'] );

		\$this -> SQL() -> run();
	}


	/**
	 * get$uname - Retrieves the $uname from table
	 *
	 * @param  integer \$id      The primary ID of the $uname to retrieve.
	 * @param  mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 * @return array             The array of matching table rows returned by the SQL query.
	 */
	public function get$uname(\$id = null, \$user_id = null ) 
	{
		\$this -> SQL() -> select ( \$this -> columns['table'] );
		$external_select
		\$this -> SQL() -> from();
		$joining

		if ( isset( \$id ) )
			\$this	-> SQL() -> where( \$id,'id' );
		if ( isset( \$user_id ) )
			\$this	-> SQL() -> where(\$user_id, \$this -> columns['ownership'] );

		\$result = \$this -> SQL() -> run();

		\$this -> set( 'data',  \$result);

		return \$result;
	}


	/**
	 * gallery$uname - Displays multiples items from the table
	 *
	 * @param integer \$page        Current page, defaults to 1
	 * @param string  \$order_col   The column to order by
	 * @param string  \$order_sort  The sort to use
	 * @param mixed   \$user_id     The value that matches the row's value for the 'ownership' column.
	 * @param array   \$search      An array containing search columns and search values.
	 * @return array                The 'pagination-friendly' array returned by the Model::page method.
	 */
	public function gallery$uname( \$order_col, \$order_sort, \$page, \$search = null, \$user_id = null)
	{
		\$this -> SQL() -> select ( \$this -> columns['table'] );
		$external_select
		\$this -> SQL() -> from();
		$joining

		if ( !empty( \$search ) )
		{
			\$this	-> SQL() -> where(\$search['values'], \$search['columns'] );
			\$search_string = '?' . \$search['query_string'];
		}else
			\$search_string = null;

		if ( isset( \$user_id ) )
			\$this	-> SQL() -> where(\$user_id, \$this -> columns['ownership'] );

		\$result = \$this 
			-> SQL() 
			-> order( \$order_col, \$order_sort)
			-> page( \$page, 6);

		\$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( \$order_col, \$order_sort )));

		\$this -> set( 
			array( 
				'page' 	 => \$page, 
				'order'  => \$order_string,
				'search' => \$search_string,
				'lastpage' => \$result['pages'], 
				'data'	 => \$result['paged'],
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
