<?php

class Logger{

	public $record = null;
	private static $instance;

	private function __construct(){
	}

	public static function instantiate() {
		if ( !isset(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function display(){
		Session::open();
		if (DEBUG && ( isset($_SESSION['username']) && $_SESSION['username'] == SITE_ADMIN) ) {
			$this -> record['Error'] = print_r( error_get_last(), true);

			echo '<pre><h3>DEBUGGING</h3>' .  $this -> formatArray($this -> record) . '</pre>';
			echo '<pre><h3>SESSION</h3>' .  $this -> formatArray($_SESSION) . '</pre>';
			$_SESSION['last_logger'] = $this -> record;
		}
	}

	public function formatArray($array){
		$printed_array = print_r($array,true);
		$formatted_array = preg_replace('/(Array)||\(||\)||\[||\]||>/','',$printed_array);
		$formatted_array = preg_replace('/ =/',':', $formatted_array);
		return $formatted_array;
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
