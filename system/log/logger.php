<?php
/**
 * Logger class file.
 *
 * @author Z. Alem <info@alemcode.com>
 */

/**
 * The Logger singleton records data by assignment to the $record array
 * and displays it at the end of the applications execution.
 *
 * It also displays all session variables.
 *
 * It's display level is determined by DEBUG_LEVEL in the main configuration file(config/main.php)
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
	 * @var string 		Log filepath
	 */
	public $log_file = null;


	/**
	 * @var integer 	Tracks the number of lines written per application run
	 */
	public $lines_written = 0;


	/**
	 * @var float 		The start time of the application in microseconds.
	 */
	public $start_time = 0;


	/**
	 * @var string 		The seperator for the csv/tsv log.
	 */
	public $seperator = '|';


	/**
	 * @var array 		Holds configuration array
	 */
	public $config = array(
		'error'		=> false,
		'warn'		=> false,
		'info'		=> false,
		'debug'		=> false,
		'display_debug'	=> false,
		'max_log_size'	=> 50000,
		'max_old_logs'	=> 3,
	);


	/**
	 * __construct - Privately held an called only by Logger::open for singleton functionality
	 */
	private function __construct()
	{
	}

	public function __destruct()
	{
		$this->rotate();
	}

	/**
	 * open - Creates singleton instance of Logger
	 *
	 * @return object
	 */
	public static function open()
	{
		if( !isset(self::$instance) )
			self::$instance = new self();
		return self::$instance;
	}


	/**
	 * setStartTime - Sets the start time of the application run
	 *
	 * @param float file 	The time
	 */
	public static function setStartTime( $start_time )
	{
		self::open()->start_time = $start_time;
	}

	/**
	 * setLogFile - Sets log file path
	 *
	 * @param string file 	The log file path
	 */
	public static function setLogFile( $file )
	{
		self::open()->log_file = $file;
	}

	/**
	 * setConfig - Sets configuration array
	 */
	public static function setConfig( array $config )
	{
		self::open()->config = $config + self::open()->config;
	}


	/**
	 * debug - Records debug information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function debug( $title, $details )
	{
		self::open()->record['debug'][][ $title ] = $details;
		self::open()->write( 'debug', $title, $details );
	}


	/**
	 * error - Records error information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function error( $title, $details )
	{
		self::open()->record['error'][][ $title ] = $details;
		self::open()->write( 'error', $title, $details );
	}


	/**
	 * info - Records info information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function info( $title, $details )
	{
		self::open()->record['info'][][ $title ] = $details;
		self::open()->write( 'info', $title, $details );
	}


	/**
	 * warn - Records warn information
	 *
	 * @param string $title 	The title of the log entry
	 * @param mixed  $details	The log entry details
	 */
	public static function warn( $title, $details )
	{
		self::open()->record['warn'][][ $title ] = $details;
		self::open()->write( 'warn', $title, $details );
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

		if( $this->config[$level] )
		{
			if( is_array( $details ) )
				$details = ArrayUtility::makeReadable( $details, true);

			$timer_end = microtime(true);
			$time = $timer_end - $this->start_time;

			$line = date( 'G:i:s T D M j Y' )  . $this->seperator . $time . $this->seperator . $level . $this->seperator;
			$line .= $title . $this->seperator . $details . "\n";

			file_put_contents( $this->log_file, $line, FILE_APPEND );

			$this->lines_written++;
		}
	}


	/**
	 * rotate - If log file size exceeds maximum, rotate.
	 *
	 * If the log exists and its size exceeds the maximum
	 * rename log by suffixing it with number.
	 * If that file exists, rename it by incrementing that number until
	 * the maximum number of old logs reached.
	 * Starting with application.log.1
	 */
	public function rotate()
	{
		if( file_exists( $this->log_file ) )
		{
			$log_size = filesize( $this->log_file );

			if( $log_size > $this->config['max_log_size'] )
			{
				if( !file_exists( $this->log_file . '.' . 1 ) )
					rename( $this->log_file, $this->log_file . '.' . 1 );
				else
				{

					for( $i =  $this->config['max_old_logs']; $i > 0; $i-- )
					{
						if ( file_exists( $this->log_file . '.' . $i ) )
						{
							if( $i == $this->config['max_old_logs'] )
								unlink( $this->log_file . '.' . $i );
							else
								rename( $this->log_file . '.' . $i, $this->log_file . '.' .( $i + 1 ) );
						}
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
		if( !self::open()->config['display_debug'] )
			return false;
		else
		{
			$log_lines 	= null;

			$log = fopen( self::open()->log_file, "r");

			while(($line = fgetcsv($log, 0 , self::open()->seperator ) ) !== false )
			{
				$single_line = "<tr>";

				foreach( $line as $cell )
					$single_line .= "<td>" . htmlspecialchars($cell) . "</td>";

				$single_line.= "</tr>\n";

				$log_lines = $single_line . $log_lines;

			}

			fclose($log);

			$session 	= new Session( $autostart = false );
			$session_dump 	= ArrayUtility::makeReadable( print_r( $session->data, true) );

			if( !empty( $session_dump ) )
				$session_dump = '<h2> Session Dump </h2><pre>' . $session_dump . '</pre><br/>';

			$table = <<<TABLE
			<br/>
			$session_dump
			<h2>Application Log</h2>
			<table class="table table-bordered">
				<thead>
					<th>Date</th>
					<th>Script-Time</th>
					<th>Level</th>
					<th>Title</th>
					<th>Details</th>
				</thead>
				<tbody>
					$log_lines
				</tbody>
			</table>
TABLE;
			echo $table;
		}
	}

}

?>
