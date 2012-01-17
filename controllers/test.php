<?php

##
##
##

class Test extends Controller{

	function __construct(){
	#	$this -> useModel('test');
	}
	
	function index(){
		$data = "This is a test controller";
		$this -> useView('test',$data);
	}

	function countto($num){
		for($x=1; $x <= $num; $x++){
			$data .= $x;
		}
		$this -> useView('test',$data);
	}
	
	function say($phrase){
		$data = $phrase;
		$this -> useView('test',$data);
	}
	
}

?>
