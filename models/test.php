<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
	}

	function orderOpts(){
		$this -> data['order_opts']['Id'] = "test/order/id+ASC";
		$this -> data['order_opts']['Test'] = "test/order/test+ASC";
	}
}

?>
