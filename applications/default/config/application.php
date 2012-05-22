<?php

return array (

/* 
 * ----------------------------------------------------------------------
 * Prefix for methods directly accessible by HTTP
 * ----------------------------------------------------------------------
 * Prefix for controller methods if they are to be accessed via HTTP (POST/GET).
 */
	'http_access_prefix'	=> 'action',

/*
 * ----------------------------------------------------------------------
 * Application defaults
 * ----------------------------------------------------------------------
 */
	'default_controller'	=> 'test',
	'default_method' 	=> 'index',
	'default_template' 	=> 'bootstrap-single',
	'base_href' 		=> 'http://localhost/miniMVC/applications/default/public_html/',
	'web_root' 		=> 'http://localhost/miniMVC/applications/default/public_html/',
/*
 * ----------------------------------------------------------------------
 * Site information
 * ----------------------------------------------------------------------
 */
	'default_template' 	=> 'bootstrap-single',
	'default_javascript'	=>  array( 'jquery,bootstrap' ),
	'default_css' 		=>  array( 'bs/bootstrap-superhero' ),
	'site_name' 		=> 'minimvc',
	'site_tag' 		=> 'an upstart app-starter',
	'site_email' 		=> 'info@alemmedia.com',
	'site_admin' 		=> 'admin',
	#default_logo_path =>  'media/img/logo.png',
	'meta_description' 	=> 'minimvc is a super lightweight mvc framework written in php.',
	'meta_keywords' 	=> 'minimvc, php mvc',
	'company' 		=> 'alemmedia',
	'company_website' 	=> 'http://alemmedia.com',
);

?>
