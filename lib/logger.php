<?php

class Logger{

	public $record = null;
	private static $instance = null;

	private function __construct(){
	}

	public static function instantiate() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function display(){
		if (DEBUG && ( isset($_SESSION['username']) && $_SESSION['username'] == SITE_ADMIN) ) {
			$this -> record['Error'] = print_r( error_get_last(), true);
			$this -> record['Session'] = print_r( $_SESSION, true);
			echo '<pre><h3>DEBUGGING</h3>' . print_r(array_filter( $this -> record), true) . '</pre>';
		}
	}

	public function trace($message) {
		$this -> log_level = 'debug';
		$this -> line_number_arr = debug_backtrace();
		$this -> write($message);
	}

	private function write($message) {
		$file = fopen( SERVER_ROOT . DEFAULT_LOG_PATH . 'logger.log', 'a') or die("Coudn't open logger.log");
		$this -> log_entry  = '[' . date('Y-m-d H:i:s', mktime()) . '][line:';
		$this -> log_entry .= $this->line_number_arr[0]['line'].'|'.$this->log_level.']:\t'.$message.'\n';
		fwrite($file, $this->log_entry);
		fclose($file);
	}

}


?>
