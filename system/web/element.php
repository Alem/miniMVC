<?php
/**
 * Element class file
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/**
 * The Element class extends the basic HTML class 
 * and Prints HTML structures specific to the conventions and settings of this framework.
 *
 */
class Element extends HTML{


	/**
	 * loadCSS - Includes all the CSS files listed in DEFAULT_CSS in the application config
	 *
	 * @return string 	The CSS stylesheet HTML links
	 */
	public static function loadCSS( ){	
		$loadedCSS = null;
		if( defined('DEFAULT_CSS')  ){ 
			foreach( explode( ",", DEFAULT_CSS ) as $name )
				$loadedCSS .= HTML::linkCSS( $name );
		}
		return $loadedCSS;
	}


	/**
	 * loadJS - Includes all the Javascript files listed in DEFAULT_JAVASCRIPT in the application config
	 *
	 * @return string 	The Javascript scripts
	 */
	public static function loadJS( ){	
		$loadedJS = null;
		if( defined('DEFAULT_JAVASCRIPT')  ){ 
			foreach( explode( ",", DEFAULT_JAVASCRIPT ) as $name )
				$loadedJS .= HTML::linkJS( $name );
		}
		return $loadedJS;
	}


	/**
	 * saved_field 
	 * @todo delete? is this useful? specific for forms, place it in a class for forms?
	 */
	function saved_field( $name,$model ){
		if (isset( $model -> saved_fields[$name] ))
			return $value = $model -> saved_fields[$name];
		else
			return null;
	}


	/**
	 * select - Prints Select list
	 * @todo Too specific for SQL returned array's $row['id'] $row[ FIELD ] form. REWORK
	 */
	public static function select ( $name , $data , $selected_value ){
		$select = "<select id = '$name-field' name = '{$name}_id'>";

		foreach ( $data as $row )
			$options[ $row['id'] ] = $row[ $name ];

		if ( isset ( $options ) )
			$select .=  HTML::options ( $options, $selected_value  );
		else
			$select .= '<option value = "" > </option>';

		$select .= '</select>';
		
		return $select;
	}


	/**
	 * pager - Prints a page listing given the model data.
	 *
	 * Given the model and the name of the controller the pager prints an HTML page listing
	 * Note: Uses bootstrap styling
	 *
	 * @param object model 		The model containing the data, order, search string, and current page.
	 * @param string method 	The name of the method that each page link will use.	
	 * @param string controller	The name of the controller that each page link will use.	
	 * @todo Seems almost complete.
	 */
	public static function pager( $model, $method = 'gallery' , $controller = CONTROLLER){

		if( empty(  $model->lastpage ))
			return null;

		$count = count( $model -> data );
		$prev_page = $page - 1;
		$next_page = $page + 1;

		$order =& $model -> order;
		$search =& $model -> search;
		$page =&  $model -> page;
		$lastpage =&  $model -> lastpage;

		$pagination= <<<top
	<div class="pagination">
	<ul>
top;
		if($page != 1)
			$pagination .= <<<previous
		<li class="prev"><a href="$controller/gallery/{$prev_page}{$order}{$search}">&larr; Previous</a></li>
previous;
		for( $i = $page, $pages = null; $i <= $lastpage; $i++) 
			$pagination .= <<<pages
		<li><a href="payment/gallery/$i{$order}{$search}">$i</a></li>
pages;
		if( $page != $lastpage )
			$pagination .= <<<nextpage
		<li class="prev"><a href="$controller/gallery/{$next_page}{$order}{$search}">Next &rarr;</a></li>
nextpage;
		$pagination .= <<<bottom
		</ul>
	</div>
bottom;
		return  $pagination;
	}

}
?>
