<?php

class Test extends Model{

	function __construct(){
		parent::__construct();

		$data['content'] = "This is test controller model data<br/>";
		$data['l_sidebar'] = "<a href ='?test'>Index </a><br/><br/>";
		$data['r_top_sidebar'] =  "<a href='?test/form' > Add stuff</a>";
		$data['r_bot_sidebar'] = "Placeholder text";
		$this -> data = $data;
	}

}

?>
