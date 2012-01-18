<?php

class TestController extends Controller{

	function __construct(){
		$this -> useModel('test');
	}
	
	function index(){
		$test_dbquery = $this -> model -> query("select * from test;");
		$this -> useView('test', $this -> model -> data);
	}

	function countto($num){
		for($x=1; $x <= $num; $x++){
			$data['content'] .= $x;
		}
		$this -> useView('test', $data);
	}
	
	function say($phrase){
		$data['content'] = urldecode($phrase);
		$data['r_top_sidebar'] = urldecode($phrase);
		$data['r_bot_sidebar'] = "<a href='?test/'>Back</a>";
		$this -> useView('test', $data);
	}
	
}

?>
