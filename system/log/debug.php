<?php
/**
 * Debug class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/** 
 * The Debug singleton records data by assignment to the $record array
 * and displays it at the end of the applications execution.
 *
 * It also displays all session variables. 
 * 
 * It's display level is determined by DEBUG_LEVEL in the main configuration file (config/main.php)
 * Where 0 is no display, 1 is username 'admin' only, and 2 means it is visible to everyone.
 */
class Debug{


	/**
	 * @var array Holds the debug data to be output
	 */
	public $record = array();


	/**
	 * @var object Holds the single instance of Debug
	 */
	private static $instance;


	/**
	 * __construct - Privately held an called only by Debug::open for singleton functionality
	 */
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
	 * display - Outputs debug data as a table 
	 *
	 * The debug table outputs two columns:
	 * 	Debug   - MEM, Execution time, errors, constants, queries
	 * 	Session - All session variables, including previous table outputs saved to session.
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
	 * @return array  $array 	The formatted array.
	 */
	public static function formatArray($array){
		$printed_array = print_r($array,true);

		$search = array ( 'Array' , '(' , ')' , '[' , ']' , '>' );
		$formatted_array = str_replace( $search , '' , $printed_array);

		$formatted_array = str_replace(' =',':', $formatted_array);
		return $formatted_array;
	}

}


?>
