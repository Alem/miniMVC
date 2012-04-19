<?php

/**
 * Contains html printing functions
 * 
 * todo This is garbage
 */


class HTML{


	public static function linkCSS( $name){	
		return $css = "<link type='text/css' rel='stylesheet' href='" . DEFAULT_MEDIA_PATH . "css/{$name}.css'/> ";
	}


	public static function linkJS( $name ){	
		return $script = "<script type = 'text/javascript' src ='" . DEFAULT_MEDIA_PATH . "js/{$name}.js'; '> </script>";
	}


	public static function input( $name, $value = null , $type = 'text'){
		return $input = "<input id = '$name-field' type ='$type' name = '$name' type = '$type'  value = '$value'>";
	}

	public static function textarea( $name, $value = null, $type = 'text' , $rows = 10, $cols = 50) {
		return $textarea = "<textarea id = '$name-field' name = '$name' type = '$type' rows = '$rows' cols = '$cols' >$value</textarea>";
	}
	

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
