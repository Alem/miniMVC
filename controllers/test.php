<?php

class TestController extends Controller{

	function __construct(){
		$this -> useModel();
	}

	function index(){
		$this -> useView('test/add');
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

	function add(){
		$item = $_POST['item'];
		$this -> model -> insert($item,'item');
		$this -> model -> data['content'] = "$item Added.";
		$this -> show();
	}

	function del($id){
		$this -> model -> remove($id,'id');
		$this -> model -> data['content'] = "$id Deleted.";
		$this -> show();
	}

	function show(){
		$test_dbquery = $this -> model -> select('*');
		if ( isset ($test_dbquery)){
			foreach ($test_dbquery as $row){ 
				$this-> model -> data['content'] .= "<p>ID: ". $row['id'] . "<br/> Item: ". $row['item'] ."<br/>"; 
				$this-> model -> data['content'] .= "<a href='?test/del/" . $row['id'] . "/'> Delete</a></p>"; 
			}
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
