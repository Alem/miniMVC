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
		$data['r_top_sidebar'] = urldecode($phrase);
		$data['r_bot_sidebar'] = "<a href='?test/'>Back</a>";
		$this -> useView('test', $data);
	}

		
	function db( $sql_query = "select * from test;" ){
		$sql_query = urldecode($sql_query);
		$test_dbquery = $this -> model -> query($sql_query);
		foreach ($test_dbquery as $row => $num){ 
			echo "<h2>Row $row</h2>";
			foreach ($num as $key => $val){ 
				echo $val . ": ". $key ."<br/>"; 
			}
		}
	}
	
}

?>
