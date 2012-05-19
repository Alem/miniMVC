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
	 * @var object 		Holds the single instance of Logger
	 */
	private static $instance;


	/**
	 * @var array 		Holds the Logger data
	 */
	public $record = array();

	/**
	 * @var integer 	Tracks the number of lines written per application run
	 */
	public $lines_written = 0;


	/**
	 * @var array 		Max number of old logs to keep
	 */
	public $max_old_logs = 2;


	/**
	 * @var array Max log size in bytes
	 */
	public $max_log_size = 50000;


	/**
	 * __construct - Privately held an called only by Logger::open for singleton functionality
	 */
	private function __construct()
	{
		$this -> rotate();
	}


	/**
	 * open - Creates singleton instance of Logger
	 *
	 * @return object
	 */
	public static function open() 
	{
		if ( !isset(self::$instance) )
			self::$instance = new self();
		return self::$instance;
	}


	/**
	 * debug - Records debug information
	 * 
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function debug( $title, $details ) 
	{
		self::open() -> record['debug'][][ $title ] = $details;
		self::open() -> write( 'debug', $title, $details );
	}


	/**
	 * error - Records error information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function error( $title, $details ) 
	{
		self::open() -> record['error'][][ $title ] = $details;
		self::open() -> write( 'error', $title, $details );
	}


	/**
	 * info - Records info information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function info( $title, $details ) 
	{
		self::open() -> record['info'][][ $title ] = $details;
		self::open() -> write( 'info', $title, $details );
	}


	/**
	 * warn - Records warn information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function warn( $title, $details ) 
	{
		self::open() -> record['warn'][][ $title ] = $details;
		self::open() -> write( 'warn', $title, $details );
	}


	/**
	 * write - Writes to log file
	 *
	 * @param string $level 	The level of log entry
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public function write( $level, $title, $details ) 
	{

		if ( constant( 'LOGGER_' . strtoupper( $level) ) )
		{
			$line = date( 'D M j G:i:s T Y' )  . ' - '. $level . ' - ' . $title . ': ' . $details . "\n";

			$log_filepath = load::path( 'log', 'application', '.log' );
			file_put_contents( $log_filepath, $line, FILE_APPEND );

			$this -> lines_written++;
		}
	}


	/**
	 * rotate - If log file size exceeds maximum, rotate.
	 *
	 * If the log size exceeds the maximum
	 * rename log by suffixing it with number.
	 * If that file exists, rename it by incrementing that number.
	 */
	public function rotate()
	{
		$log_filepath = load::path( 'log', 'application', '.log' );
		if ( file_exists ( $log_filepath ) )
		{
			$log_size = filesize( $log_filepath );

			if ( $log_size > $this -> max_log_size )
			{
				if( !file_exists( $log_filepath . '.' . 1 ) )
					rename( $log_filepath, $log_filepath . '.' . 1 );
				else
				{

					for( $i =  $this -> max_old_logs; $i > 0; $i-- )
					{
						if( $i == $this -> max_old_logs )
							unlink( $log_filepath . '.' . $i );
						else
							rename( $log_filepath . '.' . $i, $log_filepath . '.' . ( $i + 1 ) );
					}
				}
			}
		}
	}


	/**
	 * showDebug - Outputs debug data as a table 
	 *
	 * todo Displaying previous requests, session data, etc.
	 */
	public static function showDebug()
	{
		$log_filepath 	= load::path( 'log', 'application', '.log' );
		$log 		= file($log_filepath);
		$total_lines 	= count( $log );
		$log_lines 	= null;

		for ( $i = ( $total_lines - self::open()->lines_written - 1 ); $i < $total_lines; $i++)  
			$log_lines .= $log[$i];

		$table = <<<TABLE
			<br/>
			<table class="table table-bordered">
				<thead>
					<th><h2>Application Log</h2></th>
				</thead>
				<tbody>
				<tr>
					<td>
						<pre>$log_lines</pre>
					</td>
				</tr>
				</tbody>
			</table>
TABLE;
		echo $table;
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
