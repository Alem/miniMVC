<?php

class Controller{

	function __construct($query){

		/* Make request query a controller object. */
		$this->query = $query;

		/* Set default query controller to 'default.php'.
		 * Define the controller filename using the name given in the query. 
		 */
		if ( empty( $query['controller'] ) ) { $this -> query['controller'] = 'default'; }
		$controller_filename = SERVER_ROOT . '/controllers/' . $this->query['controller'] . '.php';

		/* If method exists execute, otherwise call index() method.
		 * Set parameter to null if not defined in query. 
		 */
		if ( file_exists($controller_filename) ) { 
			require_once($controller_filename);
			$controllerClass = ( $this -> query['controller'] ."Controller" );
			$controller = new $controllerClass; 
			if ( isset($query['method']) && (method_exists($controller, $this -> query['method'])) ) {
				if ( isset($this->query['variable']) ) {
					$controller -> { $this -> query['method'] }( $this -> query['variable'] );
				}else{
					$controller -> { $this -> query['method'] }();
				}
			}else{
				$controller -> index();
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
