<?php

class User extends Model
{

	public $columns = array(
		'table' 	=> array( 'id', 'user', 'password', 'email' ),
		'form'		=> array( 'user', 'password', 'email' ),
		'profile' 	=> array( 'user', 'email' ),
	);

	function retrieve( $username, $password = null)
	{
		$this->SQL()->select('*');
		$this->SQL()->from();
		$this->SQL()->where( $username , 'user');

		if ( isset( $password ) )
			$this->SQL()->where( $password , 'password');

		$result = $this->SQL()->run();

		$this->set( 'data', $result );
		return $result;
	}

	function create( $username, $password, $email)
	{
		return $this->SQL()
			-> insert( array($username, $password,$email), $this->columns['form'] )
			-> run();
	}

	function delete( $username, $password )
	{
		$this->SQL()->remove()->where ( $username, $password ) ->run();
	}

	function update($new, $new_column, $ref, $ref_column = 'user' )
	{
		$this ->SQL()->update();
	}
}

?>
