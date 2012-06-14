<?php

class Payment{

	/**
	 * driver() - Selects driver
	 *
	 * Selects driver from drivers/ directory.
	 * Each driver is an abstraction layer for a vendor payment system
	 * following the common interface IPayment.
	 *
	 * @param string $type 		Name of driver
	 * @return object 		Driver object
	 */
	public function driver( $type )
	{
		$root = dirname(__FILE__);
		require( $root . '/drivers/' . $type . '.php');

		return $this->driver = new $type;
	}

}

?>
