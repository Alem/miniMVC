<?php

/**
 * Debugger class file.
 *
 * @author Zersenay Alem <info@alemmedia.com>
 * @link http://www.alemmedia.com/
 * @copyright Copyright &copy; 2008-2012 Alemmedia
 */


class Debugger{


	private static $instance;

	/**
	 * @var array Holds the debug data to be output
	 */
	public $record = array();

	private function __construct(){
	}

	/**
	 * instantiate - Creates singleton instance of debugger
	 *
	 * @return object
	 */

	public static function instantiate() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
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
				&& ( Session::open() -> get('username') == SITE_ADMIN ) 
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
					<th><h2>Application Debug</h2></th>
					<th><h2>Session Variables</h2></th>
				</thead>
				<tbody>
				<tr>
					<td><pre>{$this -> formatArray( $this -> record )}</pre></td>
					<td><pre>{$this -> formatArray( Session::open() -> data )}</pre></td>
				</tr>
				</tbody>
			</table>
TABLE;
			echo $table;
			Session::open() -> set('last_debugger', $this -> record);
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
		$formatted_array = preg_replace('/ =/',':', $formatted_array);
		return $formatted_array;
	}

}


?>
