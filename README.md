miniMVC - A light-weight PHP MVC framework.
===============================================
(c) Alem - Alemmedia.com


DESCRIPTION
---------------

miniMVC is an MVC framework for PHP designed to provide a simple base for application developement,
 giving you the freedom to hack your way to the rest. 

It features:
* Clean, documented, object-oriented code
* Simple URI-routing
* Search engine friendly URLs
* A parameterized query builder
* A template-based MVC scaffold generator
* Automated 'lazy-loading' of mvc components
* Multiple application hosting 
* Templating support
* Extension by third-party modules/classes, 
* Built-in bootstrap and jQuery integration for agile front-end development


SETUP
---------------

1. Create the database for your application.

2. Generate the application skeleton using gimiMVC.

	./gimiMVC.php -a MYAPP --generate

3. Define the relevant information in the new application's config/app.php file.

4. Use controller by visiting the application's index.php in your browser

	http://localhost/miniMVC/applications/YOURAPP/public_html/controller/method/variable

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

	applications/ 				- Contains your applications. 
		default/
			config/			- Contains configuration files for application.
			controllers/ 		- Contains controllers
			models/			- Contains models
			views/ 			- Contains views and templates
			libs/			- Contains library classes defining reusable methods.
			modules/  		- Contains modules for extending the application
			public_html/		- Contains index.php and other publicly-viewable files.
				media/ 		- Holds css, js, and imgs.
				index.php	- The "boot strapping" script
	system/ 				- Contains essential system classes.
	gimiMVC 				- Generates MVC scaffolds
	README.md 				- You're reading it.
	.htaccess 				- Rewrites the url.
