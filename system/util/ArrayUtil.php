<?php

class ArrayUtil
{

	/**
	 * makeReadable - Formats array for clean and readable output
	 *
	 * @param array $array 		The array.
	 * @param bool  $make_oneline 	Reduces array to one line.
	 * @return array  		The formatted array.
	 */
	public static function makeReadable( array $array, $make_oneline = false )
	{
		$printed_array = print_r($array,true);

		$search = array( 'Array'  );
		#$search = array( 'Array' , '(' , ')' , '[' , ']' , '>' );
		$formatted_array = str_replace( $search , '' , $printed_array);

		$formatted_array = preg_replace('#\s+=>\s+#','=>', $formatted_array);

		if( $make_oneline )
			$formatted_array = str_replace("\n",' ', $formatted_array);

		return $formatted_array;
	}


	/**
	 * getValsfromKeys() - Extracts values from an array using the keys provided by another array
	 *
	 * Retrieves the values from associative array, array, 
	 * using keys provided by an numerically index array of keys. 
	 * Returns it as an numerically index array of matched-key=>value pairs.
	 *
	 * @param array $array 	The array from which the relevant values, referenced by keys, are extracted
	 * @param array $keys 	The array of keys to extract from the array
	 * @return array
	 */
	public static function filterByKeys( array $array, array $keys )
	{
		return array_intersect_key( $array , array_flip( $keys ) );
	}
}

?>
