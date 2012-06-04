Default Application
--------------------

DESCRIPTION
	The default application is meant to display basic features of the miniMVC framework.
	
SETUP
	Setup the config files config/application.php and config/database.php 
	so that the correct database, web root and base href are set.
	
	Install the database schema 'default_setup.sql' found in data/ using gimiMVC:
		./gimiMVC -a default --useconfig --readschema default_setup.sql

	Make sure logs/ is writable.
	
	And that should be it.	

