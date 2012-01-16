<?php

##
##
##

class Test extends Controller{

	function index(){
		$data = "This is a test controller";
		$this->useView('test',$data);
	}

	function countto($num){
		for($x=1; $x <= $num; $x++){
			echo $x;
		}
		return $content;
	}
	
	function say($phrase){
		$data = $phrase;
		$this->useView('test',$data);
	}
	
}

?>
