<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
		$this -> nav();
		$this -> sidebar();
	}

	function nav(){
		$this -> data['nav']['Form'] = "test/form";
		$this -> data['nav']['Multi-Form'] = "test/mform";
		$this -> data['nav']['Gallery'] = "test/show";
	}

	function sidebar(){
		$this -> data['l_sidebar']['Form'] = "test/form";
		$this -> data['l_sidebar']['Multi-Form'] = "test/mform";
		$this -> data['l_sidebar']['Gallery'] = "test/show";
		$this -> data['l_sidebar']['Say 123'] = "test/say/123";
		$this -> data['l_sidebar']['DB Test'] = "test/db/select * from tests";
	}

	function orderOpts(){
		$this -> data['order_opts']['Id'] = "test/order/id+ASC";
		$this -> data['order_opts']['Test'] = "test/order/test+ASC";
	}
}

?>
