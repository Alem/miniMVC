<?php

class TestController extends Controller{

	function __construct(){
		// Is assigned name,classname,filename, and model after instantiation.
	}

	function index(){
		$this -> useView('test/form');
	}

	function countto( $num ){
		for($x=1; $x <= $num; $x++){
			$this -> model -> data['content'] .= $x;
		}
		$this -> useView();
	}

	function say($phrase = 'You said nothing' ){
		$this -> model -> data['content'] = urldecode($phrase);
		$this -> model -> data['r_bot_sidebar'] = "<a href='?test/'>Back</a>";
		$this -> useView();
	}

	function form(){
		$item = $_POST['item'];
		$this -> model -> insert($item);
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}

	function add($item){
		$this -> model -> insert($item);
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}

	function del($id){
		$this -> model -> remove($id);
		$this -> model -> data['content'] = "$id Deleted.";
		$this -> show();
	}

	function show(){
		$query_result = $this -> model -> select ('*');
		$this -> model -> data['content'] = Array("show" => $query_result);
			foreach ($this -> model -> data['content']['show'] as $row){ 
				$this-> model -> data['content'] .= "<p>ID: ". $row['id'] . "<br/> Item: ". $row['test'] ."<br/>"; 
				$this-> model -> data['content'] .= "<a href='?test/del/" . $row['id'] . "/'> Delete</a></p>"; 
			}
		$this -> useView();
	}

	function db( $sql_query = "select * from test;" ){
		$sql_query = urldecode($sql_query);
		$test_dbquery = $this -> model -> query($sql_query);
		echo "<pre>" . print_r($test_dbquery, true). "</pre>";
	}

}

?>
