<?php
/**
 * Logger class file.
 *
 * @author Z. Alem <info@alemmedia.com>
 */

/** 
 * The Logger singleton records data by assignment to the $record array
 * and displays it at the end of the applications execution.
 *
 * It also displays all session variables. 
 * 
 * It's display level is determined by DEBUG_LEVEL in the main configuration file (config/main.php)
 * Where 0 is no display, 1 is username 'admin' only, and 2 means it is visible to everyone.
 */
class Logger
{


	/**
	 * @var object Holds the single instance of Logger
	 */
	private static $instance;


	/**
	 * @var array Holds the Logger data
	 */
	public $record = array();


	/**
	 * __construct - Privately held an called only by Logger::open for singleton functionality
	 */
	private function __construct()
	{
	}


	/**
	 * open - Creates singleton instance of Logger
	 *
	 * @return object
	 */
	public static function open() {
		if ( !isset(self::$instance) )
			self::$instance = new self();
		return self::$instance;
	}


	/**
	 * debug - Records debug information
	 */
	public static function debug( $title, $details ) {
		self::open() -> record['debug'][][ $title ] = $details;
	}


	/**
	 * error - Records error information
	 */
	public static function error( $title, $details ) {
		self::open() -> record['error'][][ $title ] = $details;
	}


	/**
	 * info - Records info information
	 */
	public static function info( $title, $details ) {
		self::open() -> record['info'][][ $title ] = $details;
	}


	/**
	 * warn - Records warn information
	 */
	public static function warn( $title, $details ) {
		self::open() -> record['warn'][][ $title ] = $details;
	}


	/**
	 * display - Outputs debug data as a table 
	 *
	 * The debug table outputs two columns:
	 * 	Debug   - MEM, Execution time, errors, constants, queries
	 * 	Session - All session variables, including previous table outputs saved to session.
	 */
	public static function display()
	{
		if ( 
			( DEBUG_LEVEL === 2 )
			|| ( 
				( DEBUG_LEVEL === 1) 
				&& ( Session::get('username') == SITE_ADMIN ) 
			)
		) {
			self::open() -> record['error'][] = print_r( error_get_last(), true);

			$constants = get_defined_constants(true);
			self::open() -> record['Constants'][] = print_r( $constants['user'] , true );

			$debug =& self::open() -> formatArray( self::open() -> record );
			$session =& self::open() -> formatArray( $_SESSION );
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
					<td>
						<pre>$debug</pre>
					</td>
					<td>
						<pre>$session</pre>
					</td>
				</tr>
				</tbody>
			</table>
TABLE;
			echo $table;
			Session::set('second_last_debug', Session::get('last_debug') );
			Session::set('last_debug', self::open() -> record);
		}
	}


	/**
	 * formatArray - Formats array for clean and readable debugging output
	 *
	 * @return array  $array 	The formatted array.
	 */
	public static function formatArray($array)
	{
		$printed_array = print_r($array,true);

		$search = array ( 'Array' , '(' , ')' , '[' , ']' , '>' );
		$formatted_array = str_replace( $search , '' , $printed_array);

		$formatted_array = str_replace(' =',':', $formatted_array);
		return $formatted_array;
	}

}


?>
