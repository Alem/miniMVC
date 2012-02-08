<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
	}

	function orderOpts(){
		$this -> order_opts['Id'] = "test/gallery/" . $this->page .  VAR_SEPARATOR . "id" . VAR_SEPARATOR . "ASC";
		$this -> order_opts['Test'] = "test/gallery/" . $this->page .  VAR_SEPARATOR . "test" . VAR_SEPARATOR . "ASC";
	}
}

?>
