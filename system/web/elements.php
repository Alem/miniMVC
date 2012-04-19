<?php

/**
 * Element
 * 
 * Prints specific HTML structures
 *
 */

class Element extends HTML{


	public static function loadCSS( ){	
		$loadedCSS = null;
		if( defined('DEFAULT_CSS')  ){ 
			foreach( explode( ",", DEFAULT_CSS ) as $name )
				$loadedCSS .= HTML::linkCSS( $name );
		}
		return $loadedCSS;
	}


	public static function loadJS( ){	
		$loadedJS = null;
		if( defined('DEFAULT_JAVASCRIPT')  ){ 
			foreach( explode( ",", DEFAULT_JAVASCRIPT ) as $name )
				$loadedJS .= HTML::linkJS( $name );
		}
		return $loadedJS;
	}


	function saved_field( $name,$model ){
		if (isset( $model -> saved_fields[$name] ))
			return $value = $model -> saved_fields[$name];
		else
			return null;
	}


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


	public static function pager( $model ){
		if( empty(  $model->lastpage ))
			return null;
		$count = count($model -> data);
		$name  = CONTROLLER;
		$order =& $model -> order;
		$search =& $model -> search;
		$page =&  $model -> page;
		$lastpage =&  $model -> lastpage;
		$prev_page = $page - 1;
		$next_page = $page + 1;

		$pagination= <<<top
	<div class="pagination">
	<ul>
top;
		if($page != 1)
			$pagination .= <<<previous
		<li class="prev"><a href="$name/gallery/{$prev_page}{$order}{$search}">&larr; Previous</a></li>
previous;
		for( $i = $page, $pages = null; $i <= $lastpage; $i++) 
			$pagination .= <<<pages
		<li><a href="payment/gallery/$i{$order}{$search}">$i</a></li>
pages;
		if( $page != $lastpage )
			$pagination .= <<<nextpage
		<li class="prev"><a href="$name/gallery/{$next_page}{$order}{$search}">Next &rarr;</a></li>
nextpage;
		$pagination .= <<<bottom
		</ul>
	</div>
bottom;
		return  $pagination;
	}


}


?>
