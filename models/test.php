<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
		$this -> nav();
		$this -> sidebar();
	}

	function nav(){
		$this -> data['nav']['Form'] = "test/form";
		$this -> data['nav']['Gallery'] = "test/show";
		$this -> data['nav']['About'] = "test/about";
	}

	function sidebar(){
		$this -> data['sidebar']['Login'] = "user";
		$this -> data['sidebar']['Form'] = "test/form";
		$this -> data['sidebar']['Multi-Form'] = "test/mform";
		$this -> data['sidebar']['Gallery'] = "test/show";
		$this -> data['sidebar']['Say 123'] = "test/say/123";
		$this -> data['sidebar']['DB Test'] = "test/db/select * from tests";
	}

	function orderOpts(){
		$this -> data['order_opts']['Id'] = "test/order/id+ASC";
		$this -> data['order_opts']['Test'] = "test/order/test+ASC";
	}
}

?>
