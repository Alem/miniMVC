<?php

class Test extends Model{

	function __construct(){
		parent::__construct();

		$data['content'] = "This is test controller model data<br/>";
		$data['l_sidebar'] = "<a href ='?test'>Index </a><br/><br/>";
		$data['l_sidebar'] .= "<a href='?test/say/hello' >Test Method: Say</a>";
		$data['r_top_sidebar'] = "<a href='?test/db' >Test Method: Database</a>";
		$data['r_bot_sidebar'] = "<a href='?test/countto/3' >Test Method: Count to</a><br/><br/>";
		$data['r_bot_sidebar'] .= "<a href='?test/show' >Test Method: Show</a>";
		$this -> data = $data;
	}

}

?>
