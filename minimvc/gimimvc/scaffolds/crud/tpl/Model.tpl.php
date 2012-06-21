<?php
/**
 * Model Scaffold class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 *
 */
class Model extends Scaffold 
{

	public function initialize()
	{
		$path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_MODEL_PATH;
		$this->file( ucwords($this->name), $path );
	}

	public function manageExternalLinks()
	{
		$joining = null;
		$external_select = null;
		$external_table_arrays = null;

		if ( isset( $this->queryTool()->linked_columns ) ):
			foreach ( $this->queryTool()->linked_columns  as $stripped_column ):
				//--------------------------------------START CODE
				$external_table_arrays .= <<<external

			'{$stripped_column}' => array( '$stripped_column' ),
external;
		//--------------------------------------END CODE
		//--------------------------------------START CODE
		$external_select .= <<<select

		\$this->SQL()->select ( \$this->fields['external']['{$stripped_column}'], '{$stripped_column}s' );

select;
		//--------------------------------------END CODE
		//--------------------------------------START CODE
		$joining .= <<<JOINING

		\$this->SQL()->joining ('{$stripped_column}s', '{$stripped_column}_id', 'id', 'LEFT OUTER' );
JOINING;
		//--------------------------------------END CODE
			endforeach;
			endif;
			return array(
				'external_table_array'  => $external_table_arrays,
				'external_select' 	=> $external_select,
				'joining' 		=> $joining
			);
	}


	public function retrieve()
	{

		$uname = $this->uname;
		$name = $this->name;
		$table_columns = null;
		$form_columns = null;
		$getBlankForX = null;
		$saved_fields = null;

		$external_table_data = $this->manageExternalLinks();
		$joining 		= $external_table_data['joining'];
		$external_select 	= $external_table_data['external_select'];
		$external_table_arrays 	= $external_table_data['external_table_array'];

		if ( !empty ( $this->queryTool()->table_columns ) )
			$table_columns = implode( "','" , $this->queryTool()->table_columns );
		if ( !empty ( $this->queryTool()->filtered_columns ) )
			$form_columns  = implode( "','" , $this->queryTool()->filtered_columns );
		if ( !empty ( $this->queryTool()->filtered_columns ) )
		{
			$array = $this->queryTool()->filtered_columns;
		        #if( isset( $this->queryTool()->linked_columns ) )
			#	$array = $array + $this->queryTool()->linked_columns;
			$saved_fields  = "'" . implode( "' => null,\n\t\t\t'" , $array ) . "' => null \n";
		}

/*
		if ( !empty ( $external_table_data ) ):
endif;
 */

$model =  <<<MODEL
<?php

class $uname extends Model implements ICRUD
{

	/**
	 * The fields array identifies and classifies the fields of the tables the Model interacts with.
	 * @var array
	 */
	public \$fields = array(

		'table' => array ( '$table_columns' ),

		'form' => array ( '$form_columns' ),

		'ownership' => 'user_id',

		'external' => array(
			$external_table_arrays
		)
	);


	public \$data = array(
		'saved_fields' => array( 
			$saved_fields 
		)
	);


	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * create - Inserts $uname data into table.
	 *
	 * @return integer The Primary ID of the last/recently inserted row.
	 */
	public function create( \$data, \$user_id = null )
	{
		if ( isset( \$user_id ) )
		{
			\$data[ \$this->fields['ownership'] ] = \$user_id;
			\$this->fields['form'][] = \$this->fields['ownership'];
		}

		\$this	-> SQL()
			-> insert( \$data, \$this->fields['form'] )
			-> run();

		return \$this->SQL()->last_insert_id;
	}


	/**
	 * delete - Deletes $uname from table
	 *
	 * @param integer \$id      The primary ID of the $uname to delete.
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 */
	public function delete( \$id, \$user_id = null)
	{
		\$this 	-> SQL()
			-> remove()
			-> where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> SQL()->where(\$user_id, \$this->fields['ownership'] );
		\$this->SQL()->run();
	}


	/**
	 * update - Updates the specified $uname
	 *
	 * @param integer \$id      The primary ID of the $uname to update.
	 * @param mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 */
	public function update( \$data, \$id = null, \$user_id = null )
	{
		\$this 	-> SQL()->update( \$data, \$this->fields['form'] );

		if ( isset( \$id ) )
			\$this	-> SQL()->where( \$id, 'id' );
		if ( isset( \$user_id ) )
			\$this	-> SQL()->where(\$user_id, \$this->fields['ownership'] );

		\$this->SQL()->run();
	}


	/**
	 * retrieve - Retrieves the $uname from table
	 *
	 * @param  integer \$id      The primary ID of the $uname to retrieve.
	 * @param  mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 * @return array             The array of matching table rows returned by the SQL query.
	 */
	public function retrieve(\$id = null, \$user_id = null )
	{
		\$this->SQL()->select ( \$this->fields['table'] );
		$external_select
		\$this->SQL()->from();
		$joining

		if ( isset( \$id ) )
			\$this	-> SQL()->where( \$id,'id' );
		if ( isset( \$user_id ) )
			\$this	-> SQL()->where(\$user_id, \$this->fields['ownership'] );

		\$result = \$this->SQL()->run();

		\$this->set( 'data',  \$result);

		return \$result;
	}


	/**
	 * listing - Displays multiples items from the table
	 *
	 * @param integer \$page        Current page, defaults to 1
	 * @param string  \$order_col   The column to order by
	 * @param string  \$order_sort  The sort to use
	 * @param mixed   \$user_id     The value that matches the row's value for the 'ownership' column.
	 * @param array   \$search      An array containing search columns and search values.
	 * @return array                The 'pagination-friendly' array returned by the Model::page method.
	 */
	public function listing( \$order_col, \$order_sort, \$page, \$search = null, \$user_id = null)
	{
		\$this->SQL()->select ( \$this->fields['table'] );
		$external_select
		\$this->SQL()->from();
		$joining

		if ( !empty( \$search ) )
		{
			\$this	-> SQL()->where(\$search['values'], \$search['columns'] );
			\$search_string = '?' . \$search['query_string'];
		}else
			\$search_string = null;

		if ( isset( \$user_id ) )
			\$this	-> SQL()->where(\$user_id, \$this->fields['ownership'] );

		\$result = \$this
			-> SQL()
			-> order( \$order_col, \$order_sort)
			-> page( \$page, 6);

		\$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( \$order_col, \$order_sort )));

		\$this->set(
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

}
?>
MODEL;
return $model;
	}

}

?>
