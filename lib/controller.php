<?php

class Controller{

	function __construct($query){

		$this->query = $query;

		if (empty($query['controller']) ){ $this->query['controller'] = 'default'; }

		$controller_filename = SERVER_ROOT . '/controllers/' . $this->query['controller'] . '.php';

		if ( file_exists($controller_filename) ) { 
			require_once($controller_filename);
			$controllerClass = ( $this -> query['controller'] ."Controller" );
			$controller = new $controllerClass; 

			if ( isset($query['method']) && (method_exists($controller, $this -> query['method'])) ) {
				$param = ( isset($this->query['variable']) ) ? $this->query['variable'] : ''; 
					$controller->{ $this -> query['method'] }( $param );
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
		require_once( SERVER_ROOT . '/models/' . $model . '.php');
		$this -> model = new $model;
	}
}

?>
