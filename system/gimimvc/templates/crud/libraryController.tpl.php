<?

#$library = <<<lib
	/**
	 * access - Sets and enforces the permissions for each type of action. 
	 *
	 * Processed and enforced by the AccessControl 
	 */
	public function access(){
		if ( !isset ( \$this -> accessControl ) ) {
			\$this -> accessControl = new AccessControl();
			\$this -> accessControl -> defineRoles( \$this -> permissions['roles'] );
			\$this -> accessControl -> defineActions( \$this -> permissions['actions'] );
			\$this -> accessControl -> setRole( Session::get('user_type') );
		}
		return \$this -> accessControl;
	}


	public function prepareGet(){	
		\$search = null;
		if( !empty ( \$_GET ) ){
			\$_GET = array_filter( \$_GET );
			\$columns = str_replace ( '-', '.' , array_keys( \$_GET ) );
			\$values = array_values( \$_GET );
			if( !empty ( \$_GET ) )
				\$search = array ( 'columns' => \$columns , 'values' => \$values );
		}
		return $search;
	}
}

#lib;

?>
