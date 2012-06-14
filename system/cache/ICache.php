<?php

interface ICache
{

	/**
	 *
	 */
	public function add( $data, $id, $expiry );

	/**
	 *
	 */
	public function set( $data, $id, $expiry );

	/**
	 *
	 */
	public function get( $id );

	/**
	 *
	 */
	public function del( $id );

	/**
	 *
	 */
	public function flush();

}
?>
