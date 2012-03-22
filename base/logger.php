<?php

/**
 * Logger class file.
 *
 * @author Zersenay Alem <info@alemmedia.com>
 * @link http://www.alemmedia.com/
 * @copyright Copyright &copy; 2008-2012 Alemmedia
 */


	public function trace($message) {
		$this -> log_level = 'debug';
		$this -> line_number_arr = debug_backtrace();
		$this -> write($message);
	}

	private function write($message) {
		$file = fopen( SERVER_ROOT . DEFAULT_LOG_PATH . 'debugger.log', 'a') or die("Coudn't open debugger.log");
		$this -> log_entry  = '[' . date('Y-m-d H:i:s', mktime()) . '][line:';
		$this -> log_entry .= $this->line_number_arr[0]['line'].'|'.$this->log_level.']:\t'.$message.'\n';
		fwrite($file, $this->log_entry);
		fclose($file);
	}








?>
