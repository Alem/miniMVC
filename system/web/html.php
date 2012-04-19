<?php
/**
 * HTML class file
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * Contains basic html printing functions
 * 
 */
class HTML{


	/**
	 * linkCSS - Prints a stylesheet link
	 *
	 * @param string name 	The name of the stylesheet ( excluding '.css' )
	 * @return string 	The stylesheet's HTML <link> node.
	 */
	public static function linkCSS( $name){	
		return $css = "<link type='text/css' rel='stylesheet' href='" . DEFAULT_MEDIA_PATH . "css/{$name}.css'/> ";
	}


	/**
	 * linkJS - Prints an HTML script node
	 *
	 * @param string name 	The name of the javascript file ( excluding '.js' )
	 * @return string 	The scripts HTML <script> node.
	 */
	public static function linkJS( $name ){	
		return $script = "<script type = 'text/javascript' src ='" . DEFAULT_MEDIA_PATH . "js/{$name}.js'; '> </script>";
	}


	/**
	 * input - Prints an HTML input field
	 *
	 * @param string name 	The name of the input field
	 * @param string value 	The default value of the field. ( Useful for saved/editing forms )
	 * @param string type 	The type of field
	 * @return string 	The HTML <input> node.
	 */
	public static function input( $name, $value = null , $type = 'text'){
		return $input = "<input id = '$name-field' type ='$type' name = '$name' type = '$type'  value = '$value'>";
	}


	/**
	 * textarea - Prints an HTML textarea field
	 *
	 * @param string name 		The name of the textarea 
	 * @param string value 		The default value of the textarea. ( Useful for saved/editing forms )
	 * @param string type 		The type of textarea
	 * @param integer rows 		The number of rows
	 * @param integer columns 	The number of columns
	 * @return string 		The HTML <textarea> node.
	 */
	public static function textarea( $name, $value = null, $type = 'text' , $rows = 10, $cols = 50) {
		return $textarea = "<textarea id = '$name-field' name = '$name' type = '$type' rows = '$rows' cols = '$cols' >$value</textarea>";
	}
	

	/**
	 * options - Creates an option list from a given array.
	 *
	 * @param array  array  		The option array in the form of array['value'] = 'Option Name'
	 * @param string selected_value 	The default selected option. ( Useful for saved/editing forms )
	 * @return string 			The HTML <option> nodes.
	 */
	public static function options ( $array, $selected_value ){
		$options = null;
		foreach ( $array as $key => $value) {
			$selected =  ( $value === $selected_value ) ? 'selected' : null;
			$options .= "<option value ='$key' $selected > $value </option> ";
		}
		return $options;
	}
}
?>
