<?php

class CommonData
{

	/**
	 * setSessionData - Loads relevant sesion data to model
	 */
	function setSessionData( Session $session, Model $model = null )
	{
		if ( $model === null )
			$model = $this->model();

		$model->set( 'logged_in', $session->get('logged_in') );
		$model->set( 'username', $session->get('username') );
		$model->set( 'email',	 $session->get('email') );
	}

	/**
	 * setSessionData - Loads config data to model
	 */
	function setConfigData( Config $config, Model $model = null )
	{
		if ( $model === null )
			$model = $this->model();

		$model->set( 'defaults', $config->load('application') );
	}

}

?>
