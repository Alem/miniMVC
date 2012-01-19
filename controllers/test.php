<?php

class TestController extends Controller{

	function __construct(){
		$this -> useModel('test');
	}
	
	function index(){
		$this -> useView('test/add');
	}

	function countto($num){
		for($x=1; $x <= $num; $x++){
			$this -> model -> data['content'] .= $x;
		}
		$this -> useView();
	}
	
	function say($phrase){
		$this -> model -> data['content'] = urldecode($phrase);
		$this -> model -> data['r_bot_sidebar'] = "<a href='?test/'>Back</a>";
		$this -> useView();
	}

	function add(){
		$item = $_POST['item'];
		$this -> model -> query("insert into test (item) values('$item');");
		$this -> model -> data['content'] = "$item Added.";
		$this->show();
	}

	function del($id){
		$this -> model -> query("delete from test (item) where id='$id';");
		$this -> model -> data['content'] = "$id Deleted.";
		$this->show();
	}
		
	function show( $sql_query = "select * from test;" ){
		$sql_query = urldecode($sql_query);
		$test_dbquery = $this -> model -> query($sql_query);
		foreach ($test_dbquery as $row => $num){ 
			$this-> model -> data['content'] .= "<h2>Row $row</h2>";
			foreach ($num as $id => $item){ 
				$this-> model -> data['content'] .= $id . ": ". $item ."<br/>"; 
				$this-> model -> data['content'] .= "<a href='?test/del/$item/'> Delete</a><br/>"; 
			}
		}
		$this -> useView();
	}
	
	function db( $sql_query = "select * from test;" ){
		$sql_query = urldecode($sql_query);
		$test_dbquery = $this -> model -> query($sql_query);
		echo "<pre>" . print_r($test_dbquery,true). "</pre>";
	}
	
}

?>
