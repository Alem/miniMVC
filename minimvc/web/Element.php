<?php
/**
 * Element class file
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Element class extends the basic HTML class
 * and Prints HTML structures specific to the conventions and settings of this framework.
 *
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.web
 */
class Element extends HTML
{

	/**
	 * loadCSS - Includes all the CSS files listed in DEFAULT_CSS in the application config
	 *
	 * @param  array  $names 	The array of CSS files to include
	 * @param  string $content 	Optionally provide a string for HTML CSS links to be appended to
	 * @return string 		The CSS stylesheet HTML links
	 */
	public static function loadCSS( $names, $content = null )
	{
		foreach( $names as $name )
			$content .= self::linkCSS( $name ) . "\n";
		return $content;
	}

	/**
	 * loadJS - Includes all the Javascript files listed in DEFAULT_JAVASCRIPT in the application config
	 *
	 * @param  array  $names 	The array of JS files to include
	 * @param  string $content 	Optionally provide a string for HTML CSS links to be appended to
	 * @return string 		The Javascript scripts
	 */
	public static function loadJS( $names, $content = null )
	{
		foreach( $names as $name )
			$content .= self::linkJS( $name ) . "\n";
		return $content;
	}

	/**
	 * sortable - Creates link for sorting results.
	 *
	 * @param string $column   		The column to sort by
	 * @param string $current_order 	The current sort
	 * @param string $controller_unitname   The unit name of the controller
	 * @param string $method 		The name of the method
	 * return string 			The sortable link.
	 *
	 * @todo  Complete this
	 */
	public static function sortable( $column, $current_order, $controller_unitname, $method )
	{
		/*
		$first_variable = current(explode('/',VARIABLE));
		$string =  $controller . URI_SEPARATOR . $method . URI_SEPARATOR;
		$string .= $first_variable . VAR_SEPARATOR . $column . VAR_SEPARATOR;
		$string .=( stristr( $current_order, $column . VAR_SEPARATOR . 'ASC' ) ) ? 'DESC' : 'ASC';
		 */
		return  $string;
	}

	/**
	 * select - Prints Select list
	 *
	 * @param string $options 	The HTML option list
	 * return string 		The sortable link.
	 *
	 * @todo  Complete this
	 */
	public static function select( $options )
	{
		$select = <<<SELECT
		<select id = '$name-field' name = '{$name}_id'>";
			$options;
		</select>
SELECT;
		return $select;
	}


	/**
	 * pager - Prints a page listing given the model data.
	 *
	 * Given the model and the name of the controller the pager prints an HTML page listing
	 * Note: Uses bootstrap styling
	 *
	 * @param array  $settings 	The model containing the keys: data, order, search string, and current page.
	 * @param string $method 	The name of the method that each page link will use.
	 * @param string $controller	The name of the controller that each page link will use.
	 *
	 * @fixme  This settings array is a heavy parameter. Consider accepting Router object to auto-det'n controller/method/search
	 */
	public static function pager( $settings, $method = null , $controller = null)
	{
		$default_settings = array(
			'data' => null,
			'page' => 1,
			'lastpage' => null,
			'search' => null,
			'order' => null,
		);

		$settings = $settings + $default_settings;

		if( empty( $settings['lastpage'] ) )
			return null;

		$count = count( $settings['data'] );
		$prev_page = $settings['page'] - 1;
		$next_page = $settings['page'] + 1;

		$pagination= <<<top
	<div class="pagination">
		<ul>
top;
		if( $settings['page'] != 1 )
		{
			$pagination .= <<<previous
			<li class="prev"><a href="$controller/gallery/{$prev_page}{$settings['order']}
			{$settings['search']}">&larr; Previous</a></li>
previous;
		}

		for( $i = $settings['page'], $pages = null; $i <= $settings['lastpage']; $i++)
		{
			$pagination .= <<<pages
			<li><a href="$controller/gallery/$i{$settings['order']}{$settings['search']}">$i</a></li>
pages;
		}

		if( $settings['page'] != $settings['lastpage'] )
		{
			$pagination .= <<<nextpage
			<li class="prev"><a href="$controller/gallery/{$next_page}{$settings['order']}
			{$settings['search']}">Next &rarr;</a></li>
nextpage;
		}

		$pagination .= <<<bottom
		</ul>
	</div>
bottom;
		return  $pagination;
	}

}
?>
