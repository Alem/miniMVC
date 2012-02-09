<?php

class Logger{

	public $message = null;
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
		if (DB_DEBUG && ( isset($_SESSION['username']) && $_SESSION['username'] == SITE_ADMIN) ) {
			$error = print_r(error_get_last(),true);
			$display = <<<display
			 <pre><h3>DEBUGGING</h3>  
			{$this -> message}<br/>
			<h4>Last PHP Error</h4> $error
			</pre>
display;
			echo $display;
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
