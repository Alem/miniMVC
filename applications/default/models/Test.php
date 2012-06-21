<?php

class Test extends Model
{

	/**
	 * create() - Inserts test data into 'tests' table
	 *
	 * @param string $data 	The data to be inserted
	 */
	function create( $data )
	{
		$form_fields = array_keys( $data );
		$this	-> SQL()
			-> insert( $data, $form_fields)
			-> run();
	}

	/**
	 * retrieve() - Retrieves record from table 'tests' matching the supplied ID value
	 *
	 * @param mixed $id 	The ID of the record
	 * @return array 	The select SQL query result.
	 */
	function retrieve($id ) {
		$result = $this->SQL()
			-> select ('*')
			-> from()
			-> where($id,'id')
			-> run();

		$this ->set( 'data',  $result);

		return $result;
	}

	/**
	 * update() -  Updates a row from 'tests' table
	 *
	 * @param string $new 		The new value to be set
	 * @param string $new_column 	The column of the new value.
	 * @param string $ref 		The value of a column for the record to be updated
	 * @param string $ref_column 	The name of the column
	 */
	function update($new, $new_column, $ref, $ref_column ) {
		$this ->SQL()->update();
	}


	/**
	 * delete() -  Deletes a row from 'tests' table
	 *
	 * @param string $value 	The value of a column for the record to be deleted
	 * @param string $column 	The name of the column
	 */
	function delete( $value, $column ) {
		$this ->SQL()->remove()->where ( $value, $column )->run();
	}

	/**
	 * listing() - Retrieves several records from table 'tests' returning them in a pagination friendly array
	 *
	 * @param mixed $order_col 	The column to order the results by.
	 * @param mixed $order_sort 	The type of sort.
	 * @param mixed $page 		The page to start on.
	 * @return array 		The pagination-friendly select SQL query result.
	 */
	function listing( $order_col, $order_sort, $page)
	{
		$result = $this->SQL()
			-> select('*')
			-> from()
			-> order( $order_col, $order_sort)
			-> page ($page, 6);

		$order_string = VAR_SEPARATOR . implode( VAR_SEPARATOR, array_filter(array( $order_col, $order_sort )));

		$this ->set(
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
