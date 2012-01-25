<?php

class Test extends Model{

	function __construct(){
		parent::__construct();
		$data['l_sidebar'] = "<a href ='?test'>Index </a><br/><br/>";
		$data['r_top_sidebar'] =  "<a href='?test/form' > Form</a> <br/>";
		$data['r_bot_sidebar'] =  "<a href='?test/mform' > Multi Form</a> <br/>";
		$data['r_bot_sidebar'] .=  "<a href='?test/show' > Gallery</a>";
		$this -> data = $data;
	}

}

?>
