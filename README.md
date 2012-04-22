miniMVC - A light-weight PHP MVC framework.
===============================================
(c) Alemmedia - Alemmedia.com


DESCRIPTION
---------------

miniMVC is a simple MVC framework for PHP designed to provide a minimalist, bare-bones base for your own specific 
application/framework giving you the freedom to hack your way to the rest. 

It features URI-routing, a parameterized query builder, an MVC scaffold generator, automated 'lazy-loading' of models/views/modules, extension by third-party modules/classes, and built-in bootstrap integration for agile front-end development.

To view miniMVC's request routing and processing, take a look at the image located in: public_html/media/img/miniMVC.jpg


SETUP
---------------

1. Create the database for the application.

2. Define the relevant information in config.php

3. Generate a basic scaffold controller, model and view with generate.php
	./generate.php --mvc foo --table foo

4. Use controller by visiting http://localhost/YOURAPP/public_html/controller/method/variable
	http://localhost/miniMVC/public_html/foo/index

5. Hack away.


CONVENTIONS
---------------

To make best use of the automatic loading methods and pre-defined parameters, 
it is highly recommended to adhere to these conventions.

For the MVC units 'foo' and 'foo bar': 
###### Controller
* Class Name: FooController 		FooBarController
* File Name: controllers/foo.php 	controllers/fooBar.php

###### Model
* Class Name: Foo 			FooBar
* File Name: models/foo.php 		models/fooBar.php

###### View
* File Name: views/foo/index.php	view/fooBar/index.php

###### Table and Column
* Table: foos 				foo_bars
* Column: foo 				foo_bar


FILE SYSTEM
---------------

	applications/ 			- Contains your applications. 
		default/
			config/		- Contains configuration files for application.
			controllers/ 	- Contains controllers
			models/		- Contains models
			views/ 		- Contains views
			libs/		- Contains library classes defining reusable methods.
			modules/  	- Contains modules for extending the application
	config/  			- Contains configuration files for system.
	public_html/			- Holds publicly-viewable files.
		media/  		- Holds css, js, and imgs.
			css/
			cache/
			js/
			index.php	- The "boot strapping" script
	system/ 			- Contains essential system classes.
	gimiMVC 			- Generates MVC scaffolds
	README.md 			- You're reading it.
	.htaccess 			- Rewrites the url.
