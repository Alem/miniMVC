
miniMVC - A super light-weight PHP MVC framework.
(c) Alemmedia - Alemmedia.com
===============================================

README Contents
# Description
# Setup
# Conventions
# File System
-----------------------------------------------

### DESCRIPTION

miniMVC is a simple MVC framework for PHP designed to provide a minimalist, bare-bones base for your own specific 
application/framework and to allow you the freedom to hack your way to the rest. 

It features URI-routing, an Object-Oriented query builder, PDO parameterized SQL queries, a MVC scaffold generator,
automatic and manual loading of models/views/modules, extension by third-party modules/classes, and basic bootstrap
templates.

To view how miniMVC  routes and processes requests take a look at the image:
![miniMVC Diagram](public_html/media/img/miniMVC.jpg)


### SETUP

1. Create the MYSQL database for the application.

2. Define the relevant information in config.php

3. Generate a basic scaffold controller, model and view with generate.php
>	ex. ./generate.php --mvc foo --table foo

4. Use controller by visiting http://localhost/YOURAPP/public_html/?controller/method/variable
>	ex. http://localhost/miniMVC/public_html/?foo/index

5. Hack away.


### CONVENTIONS

To make the best use of automatic loading of methods and pre-defined parameters, 
it is highly recommended to adhere to these conventions.

For the MVC scaffold "foo": 
Controllers:
	Class Name: ExampleController
	File Name: controllers/foo.php
Models:
	Class Name: Example
	File Name: models/foo.php
Views:
	File Name: views/foo/index.php	

Database:
	Table: foos
	Column:	foo


### FILE SYSTEM

* controllers/ 		- Holds created controllers.
* lib/ 			- Contains essential parent classes.
* models/		- Holds created models.
* modules/ 		- Holds module classes
* views/ 		- Holds created views
* public_html/		- Holds publicly-viewable files.
* 	media/  	- Holds css, js, and imgs.
*	index.php	- The "boot strapping" script
* config.php  		- Single configuration file for application.
* generate.php 		- Generates MVC scaffolds
* README.md 		- You're reading it.
* .htaccess 		- Rewrites the url.




