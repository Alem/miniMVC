<?php

class ArrayUtility
{

	/**
	 * makeReadable - Formats array for clean and readable output
	 *
	 * @param array $array 		The array.
	 * @param bool  $make_oneline 	Reduces array to one line.
	 * @return array  		The formatted array.
	 */
	public static function makeReadable($array, $make_oneline = false )
	{
		$printed_array = print_r($array,true);

		$search = array( 'Array' , '(' , ')' , '[' , ']' , '>' );
		$formatted_array = str_replace( $search , '' , $printed_array);

		$formatted_array = str_replace(' =',':', $formatted_array);

		if( $make_oneline )
			$formatted_array = str_replace("\n",' ', $formatted_array);

		return $formatted_array;
	}

}

?>
