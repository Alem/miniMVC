<?php

$libraryModel = <<<libraryModel
	/**
	 * insert - Inserts data into table.
	 *
	 * @return integer The Primary ID of the last/recently inserted row.
	 */
	public function insert( \$data, \$user_id = null )
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
	 * delete - Deletes  from table
	 *
	 * @param integer \$id      The primary ID of the  to delete.
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
	 * update - Updates the specified 
	 *
	 * @param integer \$id      The primary ID of the  to update.
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
	 * get - Retrieves the  from table
	 *
	 * @param  integer \$id      The primary ID of the  to retrieve.
	 * @param  mixed   \$user_id The value that matches the row's value for the 'ownership' column.
	 * @return array             The array of matching table rows returned by the SQL query.
	 */
	public function get(\$id = null, \$user_id = null )
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
	 * gallery - Displays multiples items from the table
	 *
	 * @param integer \$page        Current page, defaults to 1
	 * @param string  \$order_col   The column to order by
	 * @param string  \$order_sort  The sort to use
	 * @param mixed   \$user_id     The value that matches the row's value for the 'ownership' column.
	 * @param array   \$search      An array containing search columns and search values.
	 * @return array                The 'pagination-friendly' array returned by the Model::page method.
	 */
	public function gallery( \$order_col, \$order_sort, \$page, \$search = null, \$user_id = null)
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

libraryModel;


?>
