<?php

class TestController extends Controller{

	function __construct(){
		$this -> useModel('test');
	}
	
	function index(){
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
		$this -> useView('test', $data);
	}
	
}

?>
