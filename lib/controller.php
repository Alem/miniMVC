<?php

##
##
##

class Controller{

	function __construct($query){
		$this->query = $query;
	}

	function useController($controllerClass){
		$controller_filename = SERVER_ROOT . '/controllers/' . $this->query['controller'] . '.php';

		if (file_exists($controller_filename)) { 
			require_once($controller_filename);
		}else{
			echo "$controller_filename does not exist";
		}

		if ( isset ($controllerClass) ){
			$controller = new $controllerClass;
		}else{
			$controller = new $this->query['controller'];
		}
		if ( method_exists($controller, $this->query['method'])) {
			$controller->{ $this->query['method'] }( $this->query['variable'] );
		}else{
			$controller->index();
		}
	}

	function useView($view, $data){
		require_once( SERVER_ROOT . '/views/' . $view . '.php');
	}

	function useModel($model){
		require_once(SERVER_ROOT . '/model/' . $model . 'php');
		$this -> $model = new $model;
	}
}

?>
