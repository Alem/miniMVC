<?php

##
##
##

class Controller{

	function __construct($uri){
		$this->uri = $uri;
	}

	function useController($controllerClass){
		$controller_filename = SERVER_ROOT . '/controllers/' . $this->uri['controller'] . '.php';

		if (file_exists($controller_filename)) { 
			require_once($controller_filename);
		}else{
			echo "$controller_filename does not exist";
		}

		$controller = new $controllerClass();

		if ( method_exists($controller, $this->uri['method'])) {
			$controller->{ $this->uri['method'] }( $this->uri['var'] );
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
