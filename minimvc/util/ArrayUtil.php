<?php
/**
 * ArrayUtil class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The ArrayUtil class is provides common functions for manipulating array type data.
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.util
 */
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
		$formatted_array = str_replace( $search , '' , $printed_array);

		$formatted_array = preg_replace('#\s+=>\s+#','=>', $formatted_array);

		if( $make_oneline )
			$formatted_array = str_replace("\n",' ', $formatted_array);

		return $formatted_array;
	}


	/**
	 * fetchFromArray() - Fetches key value from a supplied array. Returns false if not found.
	 *
	 * @param  array $array 	The array to fetch from
	 * @param  string $value  	The key value to check for.
	 * @return mixed 		Returns key value or false if not found.
	 */
	public function fetchFromArray( $array, $value )
	{
		if( isset( $array[$value] ) )
			return $array[$value];
		else
			return false;
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
	 * @param bool  $order 	If set to true, the resulting array will have keys in the same order as the values of $keys
	 * @return array
	 */
	public static function filterByKeys( array $array, array $keys, $order = true )
	{
		$key_indexed_array = array_flip( $keys );
		$filtered_array = array_intersect_key( $array , $key_indexed_array );

		if( $order )
			return array_merge($key_indexed_array, $filtered_array);
		else 
			return $filtered_array;
	}
}

?>
