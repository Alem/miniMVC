<?php

class CRUDLibrary extends Scaffold
{

	public function initialize()
	{
		$path =  SERVER_ROOT . DEFAULT_APPS_PATH . APP_PATH . DEFAULT_LIBRARY_PATH;
		$this->file( $this->name, $path );
	}

	public function getContent( $type )
	{
		$CRUDLibrary = <<<CRUDLibrary

	/**
	 * SQL() - Sets and retrieves QueryBuilder
	 *
	 * @param QueryBuilder \$QueryBuilder 	The QueryBuilder object to utilize in crud methods.
	 */
	public function SQL( \$SQL )
	{
		if( !isset( \$SQL  ) )
			\$this->SQL = \$SQL;

		return \$this->SQL;
	}

	/**
	 * insert - Inserts data into table.
	 *
	 * @return integer The Primary ID of the last/recently inserted row.
	 */
	public function insert( \$data, \$columns )
	{
		\$this->SQL()
			->insert( \$data, \$columns )
			->run();

		return \$this->SQL()->last_insert_id;
	}


	/**
	 * delete - Deletes  from table
	 *
	 * @param integer \$id      The primary ID of the  to delete.
	 * @param mixed   \$where   The array of column => value key pairs to match with WHERE clause
	 */
	public function delete( \$id, \$where = null)
	{
		\$this->SQL()
			->remove()
			->where( \$id, 'id' );

		if ( isset( \$where ) )
			\$this->SQL()->where( array_values( \$where) , array_keys( \$where ) );

		\$this->SQL()->run();
	}


	/**
	 * update - Updates the specified 
	 *
	 * @param array   \$where   The array of column => value key pairs to match with WHERE clause
	 */
	public function update( \$data, \$where = null )
	{
		\$this->SQL()->update( \$data, \$['form'] );

		if ( isset( \$where ) )
			\$this-> SQL()->where( array_values( \$where) , array_keys( \$where ) );
		\$this->SQL()->run();
	}


	/**
	 * get - Retrieves the  from table
	 *
	 * @param mixed   \$columns  The column values to retrieve
	 * @param array   \$where    The array of column => value key pairs to match with WHERE clause
	 * @return array             The array of matching table rows returned by the SQL query.
	 */
	public function get( \$columns = '*',  \$where = null )
	{
		\$this->SQL()->select( \$columns );
		\$this->SQL()->from();

		if ( isset( \$where ) )
			\$this->SQL()->where( array_values( \$where ) , array_keys( \$where ) );

		\$result = \$this->SQL()->run();

		\$this->set( 'data',  \$result);

		return \$result;
	}


	/**
	 * listing - Displays multiples items from the table
	 *
	 * @param integer \$page        Current page, defaults to 1
	 * @param array   \$order  	The column to order by, array key 'column', and its sort, array key 'sort'.
	 * @param mixed   \$user_id     The value that matches the row's value for the 'ownership' column.
	 * @param array   \$search      An array containing search columns and search values.
	 * @param array   \$where  	The array of column => value key pairs to match with WHERE clause
	 * @return array                The 'pagination-friendly' array returned by the Model::page method.
	 */
	public function listing( \$order, \$page, \$search = null, \$where = null)
	{
		\$this->SQL()->select ( \$this->model->fields['table'] );
		\$this->SQL()->from();

		if ( !empty( \$search ) )
			\$this->SQL()->where(\$search['values'], \$search['columns'] );

		if ( isset( \$where ) )
			\$this->SQL()->where( array_values( \$where ) , array_keys( \$where ) );

		\$result = \$this
			-> SQL()
			-> order( \$order['column'], \$order['sort'] )
			-> page( \$page, 6);

		return \$result;
	}


	/**
	 * paginateData() - Sets the results formatted for pagination by Element::pager
	 *
	 * @param integer \$page 		The page number
	 * @param array   \$order  		The column to order by, array key 'column', and its sort, array key 'sort'.
	 * @param array   \$search      	An array containing search columns and search values.
	 * @param integer \$results 		The results returned by QueryBuilder::page
	 */ 
	public function paginateData( \$page, \$order, \$search = null, \$result = null )
	{
		if ( !empty( \$search ) )
			\$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( \$order_col, \$order_sort )));
		else
			\$order_string = null;

		if ( !empty( \$search ) )
			\$search_string = '?' . \$search['query_string'];
		else
			\search_string = null;

		return array(
				'page' 	 => \$page,
				'order'  => \$order_string,
				'search' => \$search_string,
				'lastpage' => \$result['pages'],
				'data'	 => \$result['paged'],
			)
		);
	}


	/**
	 * getUnitByX - Retrieves unit(s) and orders them by the specified external column
	 *
	 * @param string  \$external_column  The name of the external column
	 * @param integer \$id               The primary ID of the unit
	 * @param array   \$where  	     The array of column => value key pairs to match with WHERE clause
	 * @return array  \$x                The array of table values organized for each external column value
	 */
	public function getUnitByX ( \$external_column , \$id = null,  \$where = null )
	{
		\$result = \$this->get( \$id , \$where );

		foreach ( \$result as \$row )
		{
			if ( !isset ( \$x[ \$row[ \$external_column ]['total'] ] ) )
				\$x[ \$row[ \$external_column  ] ][ 'total' ] = 0;

			foreach( \$this->column['table'] as \$table_field )
				\$x[\$row[\$external_column ]] [\$row['id']] [\$table_field] = \$row[ \$table_field ];

			\$x[ \$row[ \$external_column ] ][ 'total' ]++;
		}

		return \$x;
	}
externalFunction;
CRUDLibrary;
		return $CRUDLibrary;
	}

}
?>
