<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
	}

	function orderOpts(){
		$this -> data['order_opts']['Id'] = "test/gallery/" . $this->page .  VARIABLE_SEPARATOR . "id" . VARIABLE_SEPARATOR . "ASC";
		$this -> data['order_opts']['Test'] = "test/gallery/" . $this->page .  VARIABLE_SEPARATOR . "test" . VARIABLE_SEPARATOR . "ASC";
	}
}

?>
