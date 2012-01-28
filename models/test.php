<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
	}

	function orderOpts(){
		$this -> data['order_opts']['Id'] = "test/gallery/" . $this->page . "+id+ASC";
		$this -> data['order_opts']['Test'] = "test/gallery/" . $this->page . "+test+ASC";
	}
}

?>
