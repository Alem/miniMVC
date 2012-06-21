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
 * Resource Loading
 * ----------------------------------------------------------------------
 * Set the default resource to load if no particular request is made.
 * Set the base_href to provide the absolute href root for relative href attributes. 
 * 	For use with HTML base tag. ( ex. 'http://localhost/') 
 * Set the web_root to provide the web server root for relative redirects. 
 * 	( ex: '/applications/YOURAPP/public_html/'  or '/' )
 */
	'default_controller'	=> 'TestController',
	'default_method' 	=> 'index',
	'base_href' 		=> '',
	'web_root' 		=> '',
/*
 * ----------------------------------------------------------------------
 * Application Defaults
 * ----------------------------------------------------------------------
 */
	'default_template' 	=> 'bootstrap-single',
	'default_javascript'	=>  array( 
		'js/js/jquery.js',
		'js/js/bootstrap.js',
	),
	'default_css' 		=>  array( 
		'css/bs/bootstrap-superhero.css',
	),
/*
 * ----------------------------------------------------------------------
 * Site information
 * ----------------------------------------------------------------------
 */
	'site_name' 		=> 'minimvc',
	'site_tag' 		=> 'an upstart app-starter',
	'site_email' 		=> 'info@alemmedia.com',
	'site_admin' 		=> 'admin',
	'meta_description' 	=> 'minimvc is a super lightweight mvc framework written in php.',
	'meta_keywords' 	=> 'minimvc, php mvc',
	'company' 		=> 'alemmedia',
	'company_website' 	=> 'http://alemmedia.com',
);

?>
