<?php

class Debugger{

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
		if ( 
			( DEBUG_LEVEL === 2 )
			|| ( 
				( DEBUG_LEVEL === 1) 
				&& ( Session::open() -> get('username') == SITE_ADMIN ) 
			)
		) {
			$this -> record['Error'] = print_r( error_get_last(), true);

			$table = <<<TABLE
			<br/>
			<table class="table table-bordered">
				<thead>
					<th><h3>Debug</h3></th>
					<th><h3>Session</h3></th>
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
		$file = fopen( SERVER_ROOT . DEFAULT_LOG_PATH . 'debugger.log', 'a') or die("Coudn't open debugger.log");
		$this -> log_entry  = '[' . date('Y-m-d H:i:s', mktime()) . '][line:';
		$this -> log_entry .= $this->line_number_arr[0]['line'].'|'.$this->log_level.']:\t'.$message.'\n';
		fwrite($file, $this->log_entry);
		fclose($file);
	}

}


?>
