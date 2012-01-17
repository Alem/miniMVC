<?php

class Controller{

	function __construct($query){
		$this->query = $query;

		#if (!isset($query['controller']) ){ $this->query['controller'] = 'main'; }

		$controller_filename = SERVER_ROOT . '/controllers/' . $this->query['controller'] . '.php';

		if ( file_exists($controller_filename) ) { 
			require_once($controller_filename);
			$controllerClass = ( $this -> query['controller'] ."Controller" );
			$controller = new $controllerClass; 

			if ( method_exists($controller, $this -> query['method']) ) {
				$controller->{ $this -> query['method'] }( $this -> query['variable'] );
			}else{
				$controller->index();
			}
		}else{
			echo "$controller_filename does not exist";
		}
	}

	function useView($view, $data, $template = 'template'){
		require_once( SERVER_ROOT . '/views/' . $template . '.php');
	}

	function useModel($model){
		require_once( SERVER_ROOT . '/model/' . $model . 'php');
		$this -> $model = new $model;
	}
}

?>
