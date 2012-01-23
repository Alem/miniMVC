<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
		$data['content'] = "This is test controller model data<br/>";
		$data['l_sidebar'] = "<a href ='?test'>Index </a><br/><br/>";
		$data['r_top_sidebar'] =  "<a href='?test/form' > Form</a> <br/>";
		$data['r_bot_sidebar'] =  "<a href='?test/mform' > Multi Form</a>";
		$this -> data = $data;
	}

}

?>
