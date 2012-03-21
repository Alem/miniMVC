<?php

/* Helper
 *
 * Contains html printing functions, many of which directly read specific model properties.
 *
 */


class Helper{



	function loadCss(){	
		 if( defined('DEFAULT_CSS')  ): 
			 $css = null;
		 	 $path = constant('DEFAULT_MEDIA_PATH');
			 foreach( explode( ",", DEFAULT_CSS ) as $src ): 
				 $css .= <<<css
		<link type="text/css" rel="stylesheet" href="{$path}css/{$src}.css"/>
css;
			 endforeach; 
			 return $css;
		 endif; 
	}


	function loadJs(){	
		 if( defined('DEFAULT_JAVASCRIPT')  ): 
			 $scripts = null;
		 	 $path = constant('DEFAULT_MEDIA_PATH');
			 foreach( explode( ",", DEFAULT_JAVASCRIPT ) as $src ): 
				 $scripts .= <<<script
		<script type = "text/javascript" src ='{$path}js/{$src}.js'; '> </script>
script;
			 endforeach; 
			 return $scripts;
		 endif; 
	}



	function saved_field( $name ){
		if (isset( $this -> controller -> model -> saved_fields[$name] ))
			return $value = $this -> controller -> model -> saved_fields[$name];
		else
			return null;
	}

	function input( $name, $type = 'text', $saved_field = true ){
		if ( $saved_field )
			$value = "value = '" . $this -> saved_field( $name ) . "'";
		else
			$value = null;
		$input = <<<INPUT
		<input type ='$type' name = '$name' type = '$type'  $value>
INPUT;
		return $input;
	}

	function textarea( $name, $type = 'text' , $rows = 10, $cols = 50 ) {
		$value = $this -> saved_field( $name );
		$textarea = <<<textarea
		<textarea id = "$name-field" name = "$name" type = "$type" rows = "$rows" cols = "$cols" >$value</textarea>
textarea;
		return $textarea;
	}
	

	function select ( $name ){
		$options = "<select name = '{$name}_id'>";

		foreach ( $this -> controller -> model -> $name as $row) {
			$selected =  ( $this -> saved_field( $name ) == $row[$name] ) ? 'selected' : null;
			$options .= <<<option
		<option value ='{$row['id']}' $selected > {$row[$name]} </option>
option;
		}

		$options .= "</select>";
		
		return $options;
	}

	function menuLinks( $menu ){
		$links = null;
		if ( isset( $this -> controller -> menu -> menus[$menu] ) ):
			foreach( $this -> controller -> menu -> menus[$menu] as $name => $href ):
				if (	
					isset(  $this -> controller -> menu -> active[$menu] ) 
					&& ( $name == $this -> controller -> menu -> active[$menu] )
				) 
					$active = 'class = "active"';
				else
					$active =  null;
				if ( $menu == 'breadcrumb'):
					$links .= <<<links

				<span class="divider">/</span>

links;
				endif;
				$links .= <<<links
				<li $active>
				<a href= "$href">$name</a>
				</li>
links;
			endforeach;
		endif;
		return $links;
	}

	function paginate(){
		if( empty(  $this -> controller -> model->lastpage ))
			return null;
		$count = count($this -> controller -> model -> data);
		$name =& $this -> controller -> name;
		$order =& $this -> controller -> model -> order;
		$page =&  $this -> controller -> model -> page;
		$lastpage =&  $this -> controller -> model -> lastpage;
		$prev_page = $page - 1;
		$next_page = $page + 1;
		$pagination= <<<top
	<div class="pagination">
	<ul>
top;
		if($page != 1)
		$pagination .= <<<previous
	<li class="prev"><a href="$name/gallery/{$prev_page}{$order}">&larr; Previous</a></li>
previous;
		for( $i = $page, $pages = null; $i <= $lastpage; $i++) 
		$pagination .= <<<pages
		<li><a href="payment/gallery/$i{$order}">$i</a></li>
pages;
		if( $page != $lastpage )
		$pagination .= <<<nextpage
		<li class="prev"><a href="$name/gallery/{$next_page}{$order}">Next &rarr;</a></li>
nextpage;
		$pagination .= <<<bottom
		</ul>
	</div>
bottom;
		return  $pagination;
	}



}


?>
