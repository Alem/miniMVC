<?php

/**
 * Debug class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */


class Debug{


	private static $instance;

	/**
	 * @var array Holds the debug data to be output
	 */
	public $record = array();

	private function __construct(){
	}

	/**
	 * open - Creates singleton instance of debug
	 *
	 * @return object
	 */

	public static function open() {
		if ( !isset(self::$instance) )
			self::$instance = new self();
		return self::$instance;
	}

	/**
	 * display - Outputs debug data as a table: Debug ( CPU/MEM, Execution time, errors, constants ) and Session variables.
	 *
	 */

	public function display(){
		if ( 
			( DEBUG_LEVEL === 2 )
			|| ( 
				( DEBUG_LEVEL === 1) 
				&& ( Session::get('username') == SITE_ADMIN ) 
			)
		) {
			$this -> record['Error'] = print_r( error_get_last(), true);

			$constants = get_defined_constants(true);
			$this -> record['Constants'] = print_r( $constants['user'] , true );

			$table = <<<TABLE
			<br/>
			<hr>
			<table class="table table-bordered">
				<thead>
					<th style = 'width:50%' ><h2>Application Debug</h2></th>
					<th style = 'width:50%' ><h2>Session Variables</h2></th>
				</thead>
				<tbody>
				<tr>
					<td><pre>{$this -> formatArray( $this -> record )}</pre></td>
					<td><pre>{$this -> formatArray( $_SESSION )}</pre></td>
				</tr>
				</tbody>
			</table>
TABLE;
			echo $table;
			Session::set('second_last_debug', Session::get('last_debug') );
			Session::set('last_debug', $this -> record);
		}
	}


	/**
	 * formatArray - Formats array for clean and readable debugging output
	 *
	 * @return array The formatted array.
	 */

	public function formatArray($array){
		$printed_array = print_r($array,true);
		$formatted_array = preg_replace('/(Array)||\(||\)||\[||\]||>/','',$printed_array);
		/*
		$formatted_array = str_replace('Array','', $printed_array);
		$formatted_array = str_replace('(','', $formatted_array);
		$formatted_array = str_replace(')','', $formatted_array);
		$formatted_array = str_replace('[','', $formatted_array);
		$formatted_array = str_replace(']','', $formatted_array);
		$formatted_array = str_replace('>','', $formatted_array);
		$formatted_array = str_replace(' =',':', $formatted_array);
		 */
		return $formatted_array;
	}

}


?>
