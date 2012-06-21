<?php

interface ISession
{
	public $session_id;

	public function __construct();

	public function open( $path, $name );

	public function close();

	public function read( $session_id );

	public function write( $session_id, $data );

	public function destroy( $session_id );

	public function gc( $lifetime );
}

?>
